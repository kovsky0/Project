<?php
/**
 * Defines the router bootstrapper
 */
namespace Project\Bootstrappers\HTTP\Routing;

use Project\HTTP\Controllers\Page;
use Opulence\Applications\Environments\Environment;
use Opulence\Framework\Bootstrappers\HTTP\Routing\RouterBootstrapper as BaseBootstrapper;
use Opulence\Routing\Router;
use Opulence\Routing\Routes\Caching\ICache;

class RouterBootstrapper extends BaseBootstrapper
{
    /**
     * Configures the router, which is useful for things like caching
     *
     * @param Router $router The router to configure
     */
    protected function configureRouter(Router $router)
    {
        $router->setMissedRouteController(Page::class);
        $routingConfig = require "{$this->paths["configs.http"]}/routing.php";
        $routesConfigPath = "{$this->paths["configs.http"]}/routes.php";

        if ($routingConfig["cache"] && $this->environment->getName() == Environment::PRODUCTION) {
            $cachedRoutesPath = "{$this->paths["routes.cache"]}/" . ICache::DEFAULT_CACHED_ROUTES_FILE_NAME;
            $routes = $this->cache->get($cachedRoutesPath, $router, $routesConfigPath);
            $router->setRouteCollection($routes);
        } else {
            require $routesConfigPath;
        }
    }
}