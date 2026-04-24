<?php

test('swagger documents admin users endpoints outside controller', function () {
    $this->artisan('l5-swagger:generate')
        ->assertSuccessful();

    $documentation = json_decode(file_get_contents(storage_path('api-docs/api-docs.json')), true);

    expect($documentation)
        ->toBeArray()
        ->and($documentation['paths'])->toHaveKeys([
            '/api/admin/users',
            '/api/admin/users/{user}',
        ])
        ->and($documentation['paths']['/api/admin/users'])->toHaveKeys(['get', 'post'])
        ->and($documentation['paths']['/api/admin/users/{user}'])->toHaveKeys(['get', 'put', 'delete']);
});
