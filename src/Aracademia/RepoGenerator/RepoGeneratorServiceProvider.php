<?php namespace Aracademia\RepoGenerator;

use \Illuminate\Support\ServiceProvider;

class RepoGeneratorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

        $this->app['make.repo'] = $this->app->share(function($app)
        {
            $repoGenerate = $this->app->make('Aracademia\RepoGenerator\RepoGenerate');
            $filesystem = $this->app->make('Illuminate\Filesystem\Filesystem');
            return new RepoGeneratorCommand($repoGenerate, $filesystem);
        });

        $this->commands('make.repo');

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
