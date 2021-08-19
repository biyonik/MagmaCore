<?php
declare(strict_types = 1);
namespace Magma\LiquidOrm\DataMapper\Concrete;

use Magma\DatabaseConnection\Abstraction\DatabaseConnectionInterface;
use Magma\LiquidOrm\DataMapper\Abstraction\DataMapperInterface;
use Magma\LiquidOrm\DataMapper\Exception\DataMapperException;
use PDO;
use PDOStatement;
use Throwable;

class DataMapper implements DataMapperInterface
{
    /**
     * @var DatabaseConnectionInterface
     */
    private DatabaseConnectionInterface $databaseConnection;

    /**
     * @var PDOStatement
     */
    private PDOStatement $statement;

    /**
     * @param DatabaseConnectionInterface $databaseConnection
     */
    public function __construct(DatabaseConnectionInterface $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }


    /**
     * @throws DataMapperException
     */
    private function isEmpty($value, string $errorMessage = null): bool {
        if (empty($value)) {
            throw new DataMapperException($errorMessage);;
        }
        return true;
    }

    private function isArray(array $value): bool {
        if (!is_array($value)) {
            throw new DataMapperException('Your argument needs to be an array!');;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $sqlQuery): DataMapperInterface
    {
        $this->statement = $this->databaseConnection->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bind($value)
    {
        try {
            switch ($value) {
                case is_bool($value):
                case intval($value):
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $dataType = PDO::PARAM_NULL;
                    break;
                default:
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch (DataMapperException $exception) {
            throw $exception;
        }
    }

    /**
     * @en Binds a value to corresponding name or question mark placeholder in the SQL statement that was used to prepared statement
     * @tr Hazırlanmış olan SQL sorgu cümlesindeki karşılık gelen ad veya soru işareti yer tutucusuna bir değeri eşleştirir, bağlar
     * @param array $fields
     * @return PDOStatement
     * @throws DataMapperException
     */
    protected function bindValues(array $fields): PDOStatement
    {
        if ($this->isArray($fields)) {
            foreach ($fields as $key => $value) {
                $this->statement->bindValue(':'.$key, $value, $this->bind($value));
            }
        }
        return $this->statement;
    }

    /**
     * @inheritDoc
     * @throws DataMapperException
     */
    public function bindParameters(array $fields, bool $isSearch = false): DataMapperInterface
    {
        $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
        if ($type) {
            return $this;
        }
        return new DataMapperInterface::class;
    }

    /**
     * @inheritDoc
     */
    public function numRows(): int
    {
        if ($this->statement) {
            return $this->statement->rowCount();
        }
        return -1;
    }

    /**
     * @inheritDoc
     */
    public function execute(): bool
    {
        if ($this->statement) {
            return $this->statement->execute();
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function result(): object
    {
        if ($this->statement) {
            return $this->statement->fetch(PDO::FETCH_OBJ);
        }
        return new \stdClass();
    }

    /**
     * @inheritDoc
     */
    public function results(): array
    {
        if ($this->statement) {
            return $this->statement->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function getLastId(): int
    {
        try {
            if ($this->databaseConnection->open()) {
                $last_id = $this->databaseConnection->open()->lastInsertId();
                if(!empty($last_id)) {
                    return (int)$last_id;
                }
            }
        } catch (Throwable $ex) {
            throw $ex;
        }

        return -1;
    }
}