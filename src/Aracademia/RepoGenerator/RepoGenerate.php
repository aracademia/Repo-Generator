<?php namespace Aracademia\RepoGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
/**
 * Created by PhpStorm.
 * User: rrafia
 * Date: 8/1/14
 * Time: 8:50 AM
 */

class RepoGenerate {

    public $rootDir;
    public $repoDir;
    /**
     * @var
     */
    private $filesystem;

    function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }


    public function makeDir()
    {
        $this->filesystem->makeDirectory(app_path().'/'.$this->rootDir.'/'.$this->repoDir);
    }

    public function makeModel($modelName)
    {
        $template = $this->getTemplate($modelName, Config::get('RepoGenerator::template_model_path'));
        $this->filesystem->put(app_path().'/'.$this->rootDir.'/'.$this->repoDir.'/'.$modelName.'.php', $template);
    }
    
    public function makeInterfaceImplementationAndServiceProvider($repoInterface, $repoImplementation, $repoServiceProvider)
    {
        $templateInterface = $this->getTemplate($repoInterface, Config::get('RepoGenerator::template_interface_path'));
        $this->filesystem->put(app_path().'/'.$this->rootDir.'/'.$this->repoDir.'/'.$repoInterface.'.php', $templateInterface);

        $templateImplementation = $this->getTemplate($repoImplementation, Config::get('RepoGenerator::template_implementation_path'), $repoInterface);
        $this->filesystem->put(app_path().'/'.$this->rootDir.'/'.$this->repoDir.'/'.$repoImplementation.'.php', $templateImplementation);

        $interfacePath = '/app/'.$this->rootDir.'/'.$this->repoDir.'/'.$repoInterface;
        $implementationPath = '/app/'.$this->rootDir.'/'.$this->repoDir.'/'.$repoImplementation;
        $templateServiceProvider = $this->getTemplate($repoServiceProvider, Config::get('RepoGenerator::template_serviceProvider_path'), null, $interfacePath, $implementationPath);
        $this->filesystem->put(app_path().'/'.$this->rootDir.'/'.$this->repoDir.'/'.$repoServiceProvider.'.php', $templateServiceProvider);
    }

    private function getTemplate($modelName, $tempType, $implementation = null, $interfacePath = null, $implementationPath = null)
    {
        $modelTemp = $this->filesystem->get($tempType);
        return str_replace(['{{NAMESPACE}}','{{CLASS}}','{{IMPLEMENTATION}}','{{INTERFACEPATH}}','{{IMPLEMENTATIONPATH}}'], [$this->rootDir.'\\'.$this->repoDir, $modelName, $implementation, $interfacePath, $implementationPath], $modelTemp);
    }
} 