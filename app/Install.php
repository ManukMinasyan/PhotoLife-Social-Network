<?php

namespace App;

use MadWeb\Initializer\Contracts\Runner;

class Install
{
    public function production(Runner $run)
    {
        return $run
            ->external('composer', 'install', '--no-dev', '--prefer-dist', '--optimize-autoloader')
            ->artisan('key:generate', ['--force' => true])
            ->artisan('migrate', ['--force' => true])
            ->artisan('db:seed', ['--force' => true])
            ->artisan('passport:install', ['--force' => true])
            ->artisan('storage:link')
            ->external('npm', 'install', '--production')
            ->artisan('route:cache')
            ->artisan('config:cache')
            ->artisan('event:cache')
            ->external('npm', 'run', 'production')
            ->dispatch(new \MadWeb\Initializer\Jobs\MakeEchoServerConfig(config('broadcasting.server')))
            ->dispatch(new \MadWeb\Initializer\Jobs\Supervisor\MakeQueueSupervisorConfig)
            ->dispatch(new \MadWeb\Initializer\Jobs\Supervisor\MakeSocketSupervisorConfig);
    }

    public function local(Runner $run)
    {
        return $run
            ->external('composer', 'install')
            ->artisan('key:generate')
            ->artisan('migrate')
            ->artisan('db:seed')
            ->artisan('passport:install')
            ->artisan('storage:link')
            ->external('npm', 'install')
            ->external('npm', 'run', 'development')
            ->dispatch(new \MadWeb\Initializer\Jobs\MakeEchoServerConfig(config('broadcasting.server')))
            ->dispatch(new \MadWeb\Initializer\Jobs\Supervisor\MakeQueueSupervisorConfig)
            ->dispatch(new \MadWeb\Initializer\Jobs\Supervisor\MakeSocketSupervisorConfig);
    }
}