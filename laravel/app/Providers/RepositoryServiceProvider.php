<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Interface and Repository
        $path = app_path('Repositories');

        $repositories = array_diff(scandir($path), array('.', '..', 'Impl'));

        if ( ! $repositories) {
            return;
        }

        foreach ($repositories as $repository) {
            $this->app->bind(
                'App\Repositories\\' . str_replace('.php', '', $repository),
                'App\Repositories\\Impl\\' . str_replace('RepoInterface.php', 'Repository', $repository),
            );
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
