<?php


namespace rccjr\utils;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use rccjr\utils\Database\Console\DatabaseToJson;
use rccjr\utils\Database\Console\PrettyRoutes;
use rccjr\utils\Database\Console\DatabaseToTurtle;

class UtilsProvider extends ServiceProvider
{


    public function boot(Filesystem $filesystem)
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DatabaseToJson::class,
                PrettyRoutes::class,
                DatabaseToTurtle::class,
            ]);
            $this->loadViewsFrom(__DIR__.'/templates/', 'rccjr-utils');
        }



    }

    /**
     * Registers the routes for the APIs.
     */
    protected function registerRoutes()
    {

    }

    /**
     * Get the Situation Report route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            # 'namespace' => '\jmack\sitrep\Controllers',
            #'domain' => null,
            #'prefix' => 'api/v1/situation-reporting',
            # 'middleware' => ['api', 'auth:sanctum'],
        ];
    }

    public function register()
    {
        parent::register();

    }

    public function registerCommands() {

    }

    public function registerPolicies()
    {

    }

    protected function getMigrationFileName(Filesystem $filesystem, string $filename): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $filename) {
                return $filesystem->glob($path."*_$filename.php");
            })->push($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR."{$timestamp}_$filename.php")
            ->first();
    }
}
