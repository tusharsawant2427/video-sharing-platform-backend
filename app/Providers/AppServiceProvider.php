<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const MIGRATIONS_DIR = [
        __DIR__ . "/../Features/Users/Migrations",
        __DIR__ . "/../Features/Posts/Migrations",

    ];

    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFromDirectories();
    }

    protected function loadMigrationsFromDirectories(): void
    {
        $this->loadMigrationsFrom(self::MIGRATIONS_DIR);
    }
}
