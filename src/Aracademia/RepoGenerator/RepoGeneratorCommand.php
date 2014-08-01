<?php namespace Aracademia\RepoGenerator;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;

class RepoGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'make:repo';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a repository';
    /**
     * @var RepoGenerate
     */
    protected $repoGenerate;
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(RepoGenerate $repoGenerate, Filesystem $filesystem)
	{
		parent::__construct();
        $this->repoGenerate = $repoGenerate;
        $this->filesystem = $filesystem;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{


        $rootDir = $this->ask('What is the name of the directory under the app folder where your repository will be placed? ');

        $repoDir = $this->ask('What is the name of your repository? ');

        if($this->filesystem->isDirectory(app_path().'/'.$rootDir.'/'.$repoDir))
        {
            $this->info('The '.$repoDir.' already exists.');
            if($this->confirm('Would you like to proceed setting up your files under this repository? [yes|no]'))
            {
                $this->repoGenerate->rootDir = ucfirst($rootDir);
                $this->repoGenerate->repoDir = ucfirst($repoDir);
                $this->setRepoModel($repoDir);

                $this->setRepoInterfaceAndImplementation($repoDir);
            }
            elseif($this->confirm('Would you like to create a new repository? [yes|no]'))
            {
                $repoDir = $this->ask('What is the name of your new repository? ');

                $this->setRepoDir($rootDir, $repoDir);

                $this->setRepoModel($repoDir);

                $this->setRepoInterfaceAndImplementation($repoDir);
            }
        }
        else
        {
            $this->setRepoDir($rootDir, $repoDir);

            $this->setRepoModel($repoDir);

            $this->setRepoInterfaceAndImplementation($repoDir);
        }

	}
    
    public function setRepoDir($rootDir, $repoDir)
    {
        if($this->confirm('Would you like to create a repository folder under '.$rootDir.' called '.ucfirst($repoDir).'? [yes|no]'))
        {
            $this->repoGenerate->rootDir = ucfirst($rootDir);
            $this->repoGenerate->repoDir = ucfirst($repoDir);
            $this->repoGenerate->makeDir();
        }
        else
        {
            $repoDir = $this->ask('Please, enter a name for your repository folder: ');
            $this->repoGenerate->rootDir = $rootDir;
            $this->repoGenerate->repoDir = $repoDir;
            $this->repoGenerate->makeDir();
        }
    }
    
    public function setRepoModel($repoDir)
    {
        if($this->confirm('Would you like to create a model under '.$repoDir.'? [yes|no]'))
        {
            $modelName = $this->ask('What is your model name? ');

            $this->repoGenerate->makeModel(ucfirst($modelName));
        }
    }
    
    public function setRepoInterfaceAndImplementation($repoDir)
    {
        if($this->confirm('Would you like to create an interface, implementation, and a service provider under '.$repoDir.'? [yes|no]'))
        {
            $repoInterface = $this->ask('What is your interface name? ');
            $repoImplementation = $this->ask('What is your implementation name? ');
            $repoServiceProvider = $this->ask('What is your service provider name? ');

            $this->repoGenerate->makeInterfaceImplementationAndServiceProvider($repoInterface, $repoImplementation, $repoServiceProvider);
        }
    }

}
