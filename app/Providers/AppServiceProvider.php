<?php

namespace App\Providers;

use App\Contracts\ScrapedDataTransformerServiceInterface;
use App\Contracts\ScraperServiceInterface;
use App\Services\GoutteScraperService;
use App\Services\ScrapedDataTransformerService;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ScraperServiceInterface::class, GoutteScraperService::class);
        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(ScrapedDataTransformerServiceInterface::class, ScrapedDataTransformerService::class);
    }
}
