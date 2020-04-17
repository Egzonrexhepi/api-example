<?php

namespace App\Providers;

use App\Repository\ActionRepositoryInterface;
use App\Repository\CustomerRepositoryInterface;
use App\Repository\Eloquent\ActionRepository;
use App\Repository\Eloquent\CustomerRepository;
use App\Repository\Eloquent\ReportsRepository;
use App\Repository\ReportsRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CustomerRepositoryInterface::class,CustomerRepository::class);
        $this->app->bind(ActionRepositoryInterface::class, ActionRepository::class);
        $this->app->bind(ReportsRepositoryInterface::class, ReportsRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
