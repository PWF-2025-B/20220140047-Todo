<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PaginationPaginator::useTailwind();
        Gate::define('admin', function ($user) {
            return $user->is_admin == true;
        });
    }
}
