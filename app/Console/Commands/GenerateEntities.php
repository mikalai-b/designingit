<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\Application;
use Tenet\Generator\EntityGenerator;

class GenerateEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenet:generate:classes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate entities using tenet.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Application $app, Config $config, EntityGenerator $generator)
    {
        $this->app    = $app;
        $this->config = $config;
        $this->generator = $generator;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $base_ns       = $this->config->get('doctrine.base_namespace', 'Base');
        $entity_parent = $this->config->get('doctrine.entity_parent_class', 'Entity');
        $entity_root   = $this->config->get('doctrine.entity_root', $this->app->path('Entities'));
        $repo_parent   = $this->config->get('doctrine.repository_parent_class', 'Repository');
        $repo_root     = $this->config->get('doctrine.repository_root', $this->app->path('Repositories'));

        $this->generator->setBaseNamespace($base_ns);
        $this->generator->setEntityParentClass($entity_parent);
        $this->generator->setEntityRoot($entity_root);
        $this->generator->setRepositoryParentClass($repo_parent);
        $this->generator->setRepositoryRoot($repo_root);

        $this->generator->build();
    }
}
