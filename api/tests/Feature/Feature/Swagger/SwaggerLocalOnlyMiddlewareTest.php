<?php

use App\Http\Middleware\SwaggerLocalMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('blocks swagger outside local environment', function () {
    app()->detectEnvironment(fn () => 'production');

    $middleware = new SwaggerLocalMiddleware;

    expect(fn () => $middleware->handle(
        Request::create('/api/documentation', 'GET'),
        fn (Request $request) => response('ok')
    ))->toThrow(NotFoundHttpException::class);
});

it('allows swagger on local environment', function () {
    app()->detectEnvironment(fn () => 'local');

    $middleware = new SwaggerLocalMiddleware;

    $response = $middleware->handle(
        Request::create('/api/documentation', 'GET'),
        fn (Request $request) => response('ok')
    );

    expect($response->getStatusCode())->toBe(200);
});
