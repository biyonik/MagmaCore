<?php
declare(strict_types = 1);

namespace Magma\Router\Concrete;
use Magma\Router\Abstraction\RouterInterface;


class Router implements RouterInterface {

    /**
     * @en return an array of route from our routing table
     * @tr rota tablosundaki rotalarımızı bir dizi olarak tutar ve döner
     * @var array
     */
    protected array $routes = [];

    /**
     * @en return an array of route parameters
     * @tr Rota parametrelerini bir dizi olarak tutar ve döner
     * @var array
     */
    protected array $params = [];

    /**
     * @en Adds a suffix onto the controller name
     * @tr Kontrolcü isminin önüne bir ön ek ekler
     * @var string
     */
    protected string $controllerSuffix = 'controller';

    /**
     * @inheritDoc
     */
    public function add(string $route, array $params = array()): void
    {
        $this->routes[$route] = $params;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function dispatch(string $url): void
    {
        if ($this->match($url)) {
            $controllerString = $this->params['controller'];
            $controllerString = $this->transformUpperCamelCase($controllerString);
            $controllerString = $this->getNamespace($controllerString);
            if (class_exists($controllerString)) {
                $controllerObject = new $controllerString();
                $action = $this->params['action'];
                $action = $this->transformCamelCase($action);
                if (is_callable([$controllerObject, $action])) {
                    $controllerObject->$action();
                } else {
                    throw new \Exception();
                }
            } else {
                throw new \Exception();
            }
        } else {
            throw new \Exception();
        }
    }

    private function transformUpperCamelCase(string $str): string {
        return str_replace(' ','', ucwords(str_replace('-',' ', $str)));
    }

    private function transformCamelCase(string $param): string {
        return lcfirst($this->transformUpperCamelCase($param));
    }

    /**
     * @en Get the namespace for the controller class. The namespace defined wihtin the route parameters only if it was added
     * @tr Eğer rota parametrelerine isim uzayı (namespace) eklenmiş ise kontrolcü sınıfın isim uzayını alır.
     * @param string $param
     * @return string
     */
    private function getNamespace(string $param): string {
        $namespace = 'App\Controller\\';
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace']. '\\';
        }
        return $namespace;
    }

    /**
     * @en match the route to the routes in the routing table, setting the $this->params property if a route is found
     * @tr rotayı, rota tablosundaki rotalar ile eşleştirir ve eğer rota bulunarsa params özelliğine aktarır
     * @param string $url
     * @return bool
     */
    private function match(string $url): bool {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $params[$key] = $param;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
}