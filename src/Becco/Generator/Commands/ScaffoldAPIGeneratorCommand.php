<?php

namespace Becco\Generator\Commands;

use Becco\Generator\CommandData;
use Becco\Generator\Generators\API\APIControllerGenerator;
use Becco\Generator\Generators\Common\MigrationGenerator;
use Becco\Generator\Generators\Common\ModelGenerator;
use Becco\Generator\Generators\Common\RepositoryGenerator;
use Becco\Generator\Generators\Common\RequestGenerator;
use Becco\Generator\Generators\Common\RoutesGenerator;
use Becco\Generator\Generators\Scaffold\ViewControllerGenerator;
use Becco\Generator\Generators\Scaffold\ViewGenerator;

class ScaffoldAPIGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'becco.generator:scaffold_api';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full CRUD for given model with initial views and APIs';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_SCAFFOLD_API);
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if (!$this->commandData->skipMigration) {
            $migrationGenerator = new MigrationGenerator($this->commandData);
            $migrationGenerator->generate();
        }

        $modelGenerator = new ModelGenerator($this->commandData);
        $modelGenerator->generate();

        $requestGenerator = new RequestGenerator($this->commandData);
        $requestGenerator->generate();

        $repositoryGenerator = new RepositoryGenerator($this->commandData);
        $repositoryGenerator->generate();

        $repoControllerGenerator = new APIControllerGenerator($this->commandData);
        $repoControllerGenerator->generate();

        $viewsGenerator = new ViewGenerator($this->commandData);
        $viewsGenerator->generate();

        $repoControllerGenerator = new ViewControllerGenerator($this->commandData);
        $repoControllerGenerator->generate();

        $routeGenerator = new RoutesGenerator($this->commandData);
        $routeGenerator->generate();

        if ($this->confirm("\nDo you want to migrate database? [y|N]", false)) {
            $this->call('migrate');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array_merge(parent::getArguments(), []);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), []);
    }
}
