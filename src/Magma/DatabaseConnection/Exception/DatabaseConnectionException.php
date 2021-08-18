<?php
declare(strict_types = 1);
namespace Magma\DatabaseConnection\Exception;

use PDOException;
use Throwable;

class DatabaseConnectionException extends PDOException
{
    protected $message;
    protected $code;
    protected Throwable $previous;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->previous = $previous;
    }
}