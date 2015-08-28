<?php namespace App\Libraries;

class Application extends \Laravel\Lumen\Application
{

    /**
     * Application constructor.
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param string       $resource        uri resources names
     * @param string       $controller      Controller to call
     * @param array|string $middlewares     Middlewares to apply on routes,
     *                                      [
     *                                      'delete' => 'auth',
     *                                      'create' => 'acl:admin'
     *                                      ]
     *                                      If you set give a string, it will be apply on each routes
     * @param array        $except          do not create some routes
     *
     */
    function resource($resource, $controller, $middlewares = [], array $except = [])
    {
        if (is_string($middlewares)) {
            $middlewares = [
                'index'   => $middlewares,
                'store'   => $middlewares,
                'show'    => $middlewares,
                'update'  => $middlewares,
                'destroy' => $middlewares,
            ];
        }

        $resourceName = str_replace('/', '.', $resource);

        if (!in_array('index', $except, true)) {
            $this->get($resource, ['as' => $resourceName . '.index', 'uses' => $controller . '@index', 'middleware' => in_array('index', $middlewares) ? $middlewares['index'] : null]);
        }

        if (!in_array('store', $except, true)) {
            $this->post($resource, ['as' => $resourceName . '.store', 'uses' => $controller . '@store', 'middleware' => in_array('store', $middlewares) ? $middlewares['store'] : null]);
        }

        if (!in_array('show', $except, true)) {
            $this->get($resource . '/{id}', ['as' => $resourceName . '.show', 'uses' => $controller . '@show', 'middleware' => in_array('show', $middlewares) ? $middlewares['show'] : null]);
        }

        if (!in_array('update', $except, true)) {
            $this->put($resource . '/{id}', ['as' => $resourceName . '.update', 'uses' => $controller . '@update', 'middleware' => in_array('update', $middlewares) ? $middlewares['update'] : null]);
            $this->patch($resource . '/{id}', ['as' => $resourceName . '.update', 'uses' => $controller . '@update', 'middleware' => in_array('update', $middlewares) ? $middlewares['update'] : null]);
        }

        if (!in_array('destroy', $except, true)) {
            $this->delete($resource . '/{id}', ['as' => $resourceName . '.destroy', 'uses' => $controller . '@destroy', 'middleware' => in_array('destroy', $middlewares) ? $middlewares['destroy'] : null]);
        }
    }
}