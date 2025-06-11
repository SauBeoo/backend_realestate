<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Property\Repositories\PropertyRepositoryInterface;
use App\Infrastructure\Repositories\PropertyRepository;
use App\Domain\Property\Services\PropertySearchService;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Repositories\UserRepository;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Bind repositories
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        
        // Register domain services
        $this->app->bind(PropertySearchService::class, function ($app) {
            return new PropertySearchService(
                $app->make(PropertyRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
} 