<?php

namespace App\QuickBooks;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class QuickBooksServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRoutes();
        $this->loadViewsFrom(resource_path('views/vendor/quickbooks'), 'quickbooks');
    }

    public function register(): void
    {
        $this->app->bind(Client::class, function (Application $app) {
            $token = ($app->auth->user()->quickBooksToken)
                ?: $app->auth->user()->quickBooksToken()->make();

            return new Client($app->config->get('quickbooks'), $token);
        });
    }

    protected function registerRoutes(): void
    {
        $config = $this->app->config->get('quickbooks.route');

        $this->app->router->prefix($config['prefix'])
                          ->as('quickbooks.')
                          ->middleware($config['middleware']['default'])
                          ->group(function (Router $router) use ($config) {
                              $router->get($config['paths']['connect'], [QuickBooksController::class, 'connect'])
                                     ->middleware($config['middleware']['authenticated'])
                                     ->name('connect');

                              $router->delete($config['paths']['disconnect'], [QuickBooksController::class, 'disconnect'])
                                     ->middleware($config['middleware']['authenticated'])
                                     ->name('disconnect');

                              $router->get($config['paths']['token'], [QuickBooksController::class, 'token'])
                                     ->name('token');
                          });
    }
}
