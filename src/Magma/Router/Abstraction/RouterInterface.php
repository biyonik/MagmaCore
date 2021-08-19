<?php
declare(strict_types=1);


namespace Magma\Router\Abstraction;

/**
 *  RouterInterface
 */
interface RouterInterface
{
    /**
     * @en Simple add a route to the routing table
     * @tr Rota tablosuna basit bir rota eklemesi yapan metod
     * @param string $route
     * @param array $params
     * @return void
     */
    public function add(string $route, array $params = array()): void;

    /**
     * @en Dispatch route and create controller object and execute the default method on that controller object
     * @tr Rotaya göre bir kontrolcü nesnesi oluşturur ve bu nesne örneğindeki varsayılan metodu çalıştırır
     *
     * @param string $url
     * @return void
     */
    public function dispatch(string $url): void;

}