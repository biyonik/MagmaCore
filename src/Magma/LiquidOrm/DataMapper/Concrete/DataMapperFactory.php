<?php
declare(strict_types = 1);

namespace Magma\LiquidOrm\DataMapper\Concrete;

use Magma\DatabaseConnection\Abstraction\DatabaseConnectionInterface;
use Magma\LiquidOrm\DataMapper\Abstraction\DataMapperInterface;
use Magma\LiquidOrm\DataMapper\Exception\DataMapperException;

class DataMapperFactory
{

    /**
     * Main constructor
     * @return void
     */
    public function __construct() {}

    /**
     * @throws DataMapperException
     */
    public function create(string $databaseConnectionString, string $dataMapperEnvironmentConfiguration): DataMapperInterface
    {
        $credentials = (new $dataMapperEnvironmentConfiguration([]))->getDatabaseCredentials('mysql');
        $databaseConnectionObject = new $databaseConnectionString($credentials);
        if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
            throw new DataMapperException($databaseConnectionString. ' is not a valid database connection object!');
        }
        return new DataMapper($databaseConnectionObject);
    }
}