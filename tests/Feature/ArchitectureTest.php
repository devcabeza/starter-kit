<?php

declare(strict_types=1);

arch('domain layer does not depend on infrastructure or http')
    ->expect('App\Domain')
    ->not->toUse([
        'App\Infrastructure',
        'App\Http',
        'App\Livewire',
        'Illuminate\Database\Eloquent\Model',
    ]);

arch('application layer does not depend on infrastructure or presentation')
    ->expect('App\Application')
    ->not->toUse([
        'App\Infrastructure',
        'App\Http',
        'App\Livewire',
    ]);

arch('no debugging functions are used in code')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->not->toBeUsed();

arch('strict types are declared in domain, application and ports')
    ->expect([
        'App\Domain',
        'App\Application',
        'App\Ports',
    ])
    ->toUseStrictTypes();
