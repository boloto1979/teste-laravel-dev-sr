<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\TicketRepository;
use App\Services\CategoryService;
use App\Services\TicketService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository($app->make(\App\Models\Category::class));
        });

        $this->app->bind(TicketRepository::class, function ($app) {
            return new TicketRepository($app->make(\App\Models\Ticket::class));
        });

        $this->app->bind(CategoryService::class, function ($app) {
            return new CategoryService($app->make(CategoryRepository::class));
        });

        $this->app->bind(TicketService::class, function ($app) {
            return new TicketService($app->make(TicketRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
