<?php

namespace Modules\Video\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Video\App\Models\VideoUrlCode;
use Modules\Video\App\Repositories\Contract\SectionRepositoryInterface;
use Modules\Video\App\Repositories\Contract\VideoRepositoryInterface;
use Modules\Video\App\Repositories\Contract\VideoUrlCodeInterface;
use Modules\Video\App\Repositories\Eloquent\SectionRepository;
use Modules\Video\App\Repositories\Eloquent\VideoRepository;
use Modules\Video\App\Repositories\Eloquent\VideoUrlCodeRepository;
use Modules\Video\App\Services\Contract\SectionServiceInterface;
use Modules\Video\App\Services\Contract\VideoServiceInterface;
use Modules\Video\App\Services\Contract\VideoUrlCodeServiceInterface;
use Modules\Video\App\Services\Repositories\SectionServiceRepository;
use Modules\Video\App\Services\Repositories\VideoServiceRepository;
use Modules\Video\App\Services\Repositories\VideoUrlCodeServiceRepository;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class VideoServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Video';

    protected string $nameLower = 'video';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
         // Bind Repository
         $this->app->bind(
            VideoRepositoryInterface::class,
            VideoRepository::class
        );

        // Bind Service
        $this->app->bind(
            VideoServiceInterface::class,
            VideoServiceRepository::class
        );

        //Bind Section Repository here
        $this->app->bind(
            SectionRepositoryInterface::class,
            SectionRepository::class
        );

        // Bind Section Service
        $this->app->bind(
            SectionServiceInterface::class,
            SectionServiceRepository::class
        );

        // Bind Url Code Repository
        $this->app->bind(
            VideoUrlCodeInterface::class,
            VideoUrlCodeRepository::class
        );

        // Bind Url Code Service
        $this->app->bind(
            VideoUrlCodeServiceInterface::class,
            VideoUrlCodeServiceRepository::class
        );
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->nameLower.'.'.$config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end($normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ($config === 'config.php') ? $this->nameLower : implode('.', $normalized);

                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        Blade::componentNamespace(config('modules.namespace').'\\' . $this->name . '\\View\\Components', $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}