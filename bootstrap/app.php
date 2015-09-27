<?php
$startBootstraping = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv::load(__DIR__ . '/../');

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new \App\Libraries\Application(
    realpath(__DIR__ . '/../')
);

$app->withFacades();

if (!class_exists('Artisan')) {
    class_alias(Illuminate\Support\Facades\Artisan::class, 'Artisan');
}

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);



/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    Illuminate\Cookie\Middleware\EncryptCookies::class,
    Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    Illuminate\Session\Middleware\StartSession::class,
    Illuminate\View\Middleware\ShareErrorsFromSession::class,
    //Laravel\Lumen\Http\Middleware\VerifyCsrfToken::class,
    LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware::class,
    Clockwork\Support\Lumen\ClockworkMiddleware::class
]);


$app->routeMiddleware([
    'check-authorization-params' => LucaDegasperi\OAuth2Server\Middleware\CheckAuthCodeRequestMiddleware::class,
    'csrf' => Laravel\Lumen\Http\Middleware\VerifyCsrfToken::class,
    'auth' => \App\Http\Middleware\AuthMiddleware::class,
    'acl' => \App\Http\Middleware\AclMiddleware::class,
    'oauth' => LucaDegasperi\OAuth2Server\Middleware\OAuthMiddleware::class,
    'oauth-owner' => LucaDegasperi\OAuth2Server\Middleware\OAuthClientOwnerMiddleware::class
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(App\Providers\AssetsProvider::class);
$app->register(Clockwork\Support\Lumen\ClockworkServiceProvider::class);
$app->register(App\Providers\AclProvider::class);
$app->register(Appzcoder\LumenRoutesList\RoutesCommandServiceProvider::class);
$app->register(LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider::class);
$app->register(LucaDegasperi\OAuth2Server\Lumen\OAuth2ServerServiceProvider::class);
$app->register(Barryvdh\Cors\LumenServiceProvider::class);
$app->register(TwigBridge\ServiceProvider::class);


$app->configure('profiler');
$app->configure('oauth2');
$app->configure('cors');
$app->configure('twigbridge');

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

if (!class_exists('Authorizer')) {
    class_alias(\LucaDegasperi\OAuth2Server\Facades\Authorizer::class, 'Authorizer');
}

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__ . '/../app/Http/routes.php';
});

Clockwork::addEvent('app.bootstrap', 'App bootstraping', $startBootstraping, microtime(true));

return $app;
