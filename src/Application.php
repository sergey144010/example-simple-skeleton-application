<?php

namespace App;

use App\Controllers\UserController;
use App\Services\UserManager;
use Aura\Di\Container;
use Aura\Di\ContainerBuilder;
use Aura\SqlQuery\QueryFactory;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;
use Aura\Router\RouterContainer;

class Application
{
    private Container $di;

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        $builder = new ContainerBuilder();
        $this->di = $builder->newInstance($builder::AUTO_RESOLVE);

        $this->di->params[Config::class]['path'] = __DIR__ . '/../config/app.php';
        $this->di->set(Config::class, $this->di->lazyNew(Config::class));

        $this->di->params[QueryFactory::class]['db'] = $this->di->lazy(function () {
            /** @var Config $config */
            $config = $this->di->get(Config::class);
            return $config->get()['app']['db']['driver'];
        });
        $this->di->set(QueryFactory::class, $this->di->lazyNew(QueryFactory::class));

        $this->di->set('request', $this->di->lazy(function () {
            return ServerRequestFactory::fromGlobals(
                $_GET,
                $_POST,
            );
        }));

        $this->di->set(RouterContainer::class, $this->di->lazyNew(RouterContainer::class));
        $this->di->set(UserManager::class, $this->di->lazyNew(UserManager::class));
    }

    public function run(): void
    {
        /** @var ServerRequestInterface $request */
        $request = $this->di->get('request');
        /** @var RouterContainer $routerContainer */
        $routerContainer = $this->di->get(RouterContainer::class);

        $map = $routerContainer->getMap();

        $map->post('usersByName', '/api/v1/users/find/name', UserController::usersByName());
        $map->post('usersByNames', '/api/v1/users/find/list', UserController::usersByNames());
        $map->post('createUser', '/api/v1/users/create/name', UserController::createUser());
        $map->post('createUsers', '/api/v1/users/create/list', UserController::createUsers());
        $map->get('usersOlder', '/api/v1/users/older/{age}', UserController::usersOlder());

        $matcher = $routerContainer->getMatcher();

        $route = $matcher->match($request);
        if (! $route) {
            echo "No route found for the request.";
            exit;
        }

        foreach ($route->attributes as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        $callable = $route->handler;
        $response = $callable($request, $this->di);

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        http_response_code($response->getStatusCode());
        echo $response->getBody();
    }
}
