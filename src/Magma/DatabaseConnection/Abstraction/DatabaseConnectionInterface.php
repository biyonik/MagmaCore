<?php
declare(strict_types=1);

namespace Magma\DatabaseConnection\Abstraction;

use PDO;

interface DatabaseConnectionInterface
{
    /**
     * @en Create a new database connection
     * @tr Yeni bir veritabanı bağlantısı oluşturur
     * @return PDO
     */
    public function open(): PDO;

    /**
     * @en Close database connection
     * @tr Veritabanı bağlantısını kapatır
     * @return mixed
     */
    public function close(): void;
}