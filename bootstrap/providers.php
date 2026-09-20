<?php

use App\Providers\AppServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\TelescopeServiceProvider;
use Laravel\Horizon\HorizonApplicationServiceProvider;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

return [
    AppServiceProvider::class,
    ...class_exists(HorizonApplicationServiceProvider::class)
        ? [HorizonServiceProvider::class]
        : [],
    ...class_exists(TelescopeApplicationServiceProvider::class)
        ? [TelescopeServiceProvider::class]
        : [],
];
