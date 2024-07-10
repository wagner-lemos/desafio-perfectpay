<?php

declare(strict_types=1);

namespace App\Providers;

use App\Clients\Asaas;
use App\Http\Controllers\ProductController;
use App\Models\PaymentResponse;
use App\Repositories\AsaasRepository;
use App\Repositories\Interfaces\AsaasRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\PaymentRepository;
use App\Repositories\ProductRepository;
use App\Services\AsaasService;
use App\Services\PaymentService;
use App\Services\ProductService;
use App\Supports\AsaasMapper;
use App\Supports\Interfaces\MapperInterface;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerClients();
        $this->registerModels();
        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    public function registerClients(): void
    {
        $this->app->singleton(Client::class, fn () => new Client([
            'base_uri' => config('asaas.url')[config('asaas.environment') == 'production' ? 'production' : 'sandbox'],
            'http_errors' => false,
            'headers' => [
                'access_token' => config('asaas.token'),
                'content-type' => 'application/json',
            ],
            'connect_timeout' => config('asaas.timeout'),
        ]));

        $this->app->singleton(Asaas::class, fn (Application $app) => new Asaas($app->make(Client::class)));
    }

    public function registerModels(): void
    {

        $this->app->singleton(PaymentResponse::class, fn (Application $app) => new PaymentResponse());
    }

    public function registerRepositories(): void
    {
        $this->app->singleton(AsaasRepositoryInterface::class, fn (Application $app) => new AsaasRepository($app->make(Asaas::class)));

        $this->app->when(PaymentRepository::class)
            ->needs(Asaas::class)
            ->give(Asaas::class);
        $this->app->when(PaymentRepository::class)
            ->needs(Model::class)
            ->give(PaymentResponse::class);
        $this->app->when(PaymentRepository::class)
            ->needs(MapperInterface::class)
            ->give(AsaasMapper::class);

    }

    public function registerServices(): void
    {
        $this->app->when(ProductController::class)
            ->needs(ProductService::class)
            ->give(ProductService::class);

        $this->app->when(AsaasService::class)
            ->needs(AsaasRepositoryInterface::class)
            ->give(AsaasRepository::class);

        $this->app->when(PaymentService::class)
            ->needs(PaymentRepositoryInterface::class)
            ->give(PaymentRepository::class);

        $this->app->when(ProductService::class)
            ->needs(ProductRepositoryInterface::class)
            ->give(ProductRepository::class);
    }
}
