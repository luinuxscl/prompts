<?php

namespace Luinuxscl\Prompts;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Luinuxscl\Prompts\Services\PromptService;

class PromptsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        // Publicar la configuración
        $this->publishes([
            __DIR__ . '/config/prompts.php' => config_path('prompts.php'),
        ], 'config');

        // Cargar migraciones directamente
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Cargar la configuración
        $this->mergeConfigFrom(__DIR__ . '/config/prompts.php', 'prompts');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Registrar el servicio principal como singleton
        $this->app->singleton('prompts', function ($app) {
            return new PromptService();
        });
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param string $migrationFileName
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make('files');

        return Collection::make([$this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR])
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path . '*_' . $migrationFileName);
            })
            ->push($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $timestamp . '_' . $migrationFileName)
            ->first();
    }
}
