<?php

namespace Becco\Generator\Generators\Common;

use Config;
use Becco\Generator\CommandData;
use Becco\Generator\Generators\GeneratorProvider;
use Becco\Generator\Utils\GeneratorUtils;

class RepositoryGenerator implements GeneratorProvider
{
    /** @var  CommandData */
    private $commandData;

    /** @var string */
    private $path;

    public function __construct($commandData)
    {
        $this->commandData = $commandData;
        $this->path = Config::get('generator.path_repository', app_path('/Repositories/'));
    }

    public function generate()
    {
        $templateData = $this->commandData->templatesHelper->getTemplate('Repository', 'common');

        $templateData = GeneratorUtils::fillTemplate($this->commandData->dynamicVars, $templateData);

        $fileName = $this->commandData->modelName.'Repository.php';

        if (!file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }

        $path = $this->path.$fileName;

        $this->commandData->fileHelper->writeFile($path, $templateData);
        $this->commandData->commandObj->comment("\nRepository created: ");
        $this->commandData->commandObj->info($fileName);
    }
}
