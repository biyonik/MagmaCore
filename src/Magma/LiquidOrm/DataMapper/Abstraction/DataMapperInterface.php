<?php
declare(strict_types=1);

namespace Magma\LiquidOrm\DataMapper\Abstraction;

interface DataMapperInterface
{
    /**
     * @en Prepare the query string
     * @tr Bağlantı cümlesini hazırlar
     *
     * @param string $sqlQuery
     * @return $this
     */
    public function prepare(string $sqlQuery): self;

    /**
     *
     * @param mixed $value
     */
    public function bind($value);

    /**
     * @param array $fields
     * @param bool $isSearch
     * @return mixed
     */
    public function bindParameters(array $fields, bool $isSearch = false): self;

    /**
     * @return int
     */
    public function numRows(): int;

    /**
     *
     */
    public function execute(): bool;

    /**
     * @return object
     */
    public function result(): object;

    /**
     * @return array
     */
    public function results(): array;

    /**
     * @return int
     */
    public function getLastId(): int;
}