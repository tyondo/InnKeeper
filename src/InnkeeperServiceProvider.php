<?php

namespace Tyondo\Innkeeper;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Tyondo\Innkeeper\Console\CreateInnkeeperTenantCommand;
use Tyondo\Innkeeper\Console\MigrationCommand;
use Tyondo\Innkeeper\Console\PolicyImporterCommand;
use Tyondo\Innkeeper\Http\Middleware\TenantMiddleware;

class InnkeeperServiceProvider  extends ServiceProvider
{
    protected static $packageName = 'innkeeper';
    protected $providers = [

    ];

    protected $aliases = [];

    protected $commands = [
        MigrationCommand::class,
        PolicyImporterCommand::class,
        CreateInnkeeperTenantCommand::class
    ];

    protected $defer = false;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Resources/Views', self::$packageName);
        $this->bootAdditionWebMiddleware();

        $this->bootInnkeeperDbConnection();
    }

    private function bootInnkeeperDbConnection(){
        $connectionInfo = [
          'driver' => 'mysql',
          'host' => config('innkeeper.connections.innkeeper.mysql.host'),
          'database' => config('innkeeper.connections.innkeeper.mysql.database'),
          'username' => config('innkeeper.connections.innkeeper.mysql.username'),
          'password' => config('innkeeper.connections.innkeeper.mysql.password'),
        ];
        Config::set('database.connections.innkeeper', $connectionInfo);
    }
    private function bootAdditionWebMiddleware(){
        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('web', TenantMiddleware::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServiceProviders();
        $this->registerAliases();
        $this->registerCommands();
        $this->registerConfigs();
        $this->registerRoutes();
        $this->bootInnkeeperDbConnection();
    }

    private function registerRoutes(){
        Route::middleware(['web'])
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
            });
        Route::middleware('api')
            ->prefix('api')
            ->name('api.')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
            });
    }

    private function registerConfigs()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/'.self::$packageName.'.php', self::$packageName
        );
    }

    private function registerServiceProviders()
    {
        foreach ($this->providers as $provider)
        {
            $this->app->register($provider);
        }
    }
    private function registerAliases()
    {
        $loader = AliasLoader::getInstance();
        foreach ($this->aliases as $key => $alias)
        {
            $loader->alias($key, $alias);
        }
    }
    private function registerCommands(){
        $this->commands($this->commands);
    }

}
