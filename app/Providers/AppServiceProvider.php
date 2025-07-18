<?php

namespace App\Providers;

use App\Contracts\CepServiceInterface;
use App\Services\ViaCepService;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind dinâmico do serviço de CEP baseado na configuração
        $this->app->bind(CepServiceInterface::class, function ($app) {
            $serviceName = config('cep.service', 'viacep');
            $serviceClass = config("cep.services.{$serviceName}");
            
            if (!$serviceClass) {
                throw new InvalidArgumentException("Serviço de CEP '{$serviceName}' não encontrado.");
            }
            
            return $app->make($serviceClass);
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
