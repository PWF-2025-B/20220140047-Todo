<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Scramble::configure()->routes(function (Route $route){
            return Str::startsWith($route->uri, 'api/');
        });
    }
}
