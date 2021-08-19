<?php
declare(strict_types = 1);
namespace Magma\DatabaseConnection\Exception;

use PDOException;
use Throwable;

class DatabaseConnectionException extends PDOException
{

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = null, $code = null, Throwable $previous = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->previous = $previous;
    }
}