<?php

test('swagger documents sanctum authentication endpoints', function () {
    $this->artisan('l5-swagger:generate')
        ->assertSuccessful();

    $documentation = json_decode(file_get_contents(storage_path('api-docs/api-docs.json')), true);

    expect($documentation)
        ->toBeArray()
        ->and($documentation['paths'])->toHaveKeys([
            '/sanctum/csrf-cookie',
            '/api/auth/login',
            '/api/auth/logout',
        ])
        ->and($documentation['paths']['/api/auth/login'])->toHaveKey('post')
        ->and($documentation['paths']['/api/auth/logout'])->toHaveKey('post')
        ->and($documentation['components']['securitySchemes'])->toHaveKeys([
            'sanctumBearer',
            'sanctumCookie',
            'xsrfToken',
        ]);
});
