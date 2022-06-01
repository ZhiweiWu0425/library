<?php namespace October\Rain\Foundation\Providers;

use Illuminate\Log\LogServiceProvider as LogServiceProviderBase;

/**
 * LogServiceProvider
 */
class LogServiceProvider extends LogServiceProviderBase
{
    /**
     * register the service provider.
     */
    public function register()
    {
        parent::register();

        // After registration
        $this->app->booting(function () {
            $config = $this->app->make('config');
            $this->configureDefaultLogger($config);
            $this->configureDefaultPermissions($config);
        });
    }

    /**
     * configureDefaultLogger channel for the application
     * when no configuration is supplied.
     */
    protected function configureDefaultLogger($config)
    {
        if ($config->get('logging.default', null) !== null) {
            return;
        }

        // Set default values as single log file
        $config->set('logging.default', 'single');

        $config->set('logging.channels.single', [
            'driver' => 'single',
            'path' => storage_path('logs/system.log'),
            'level' => 'debug',
        ]);
    }

    /**
     * configureDefaultPermissions
     */
    protected function configureDefaultPermissions($config)
    {
        if ($config->get('logging.channels.single.permission', null) === null) {
            $config->set('logging.channels.single.permission', $this->app['files']->getFilePermissions());
        }

        if ($config->get('logging.channels.daily.permission', null) === null) {
            $config->set('logging.channels.daily.permission', $this->app['files']->getFilePermissions());
        }
    }
}
