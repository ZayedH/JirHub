<?php

namespace App\OutdatedLibrariesToElastic;

use App\OutdatedLibrariesToElastic\ElasticInput\ComposerOutdated;
use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ComposerOutdatedToElastic extends Command
{
    /** @var string */
    protected static $defaultName = 'composer:outdated-libraries';

    private Client $elasticsearchClient;
    private ComposerOutdated $ComposerOutdated;

    public function __construct(ComposerOutdated $ComposerOutdated, Client $elasticsearchClient)
    {
        parent::__construct();

        $this->ComposerOutdated    = $ComposerOutdated;
        $this->elasticsearchClient = $elasticsearchClient;
    }

    protected function configure()
    {
        $this->setDescription('sending composer outdated libraries to elasticsearsh');
        $this->addArgument('path', InputArgument::REQUIRED, 'a path to your json file is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $json   = $this->ComposerOutdated->getComposerJson($input->getArgument('path'));
        $params = ['index' => 'tiime-chronos(API)-outdated-libraries', 'body' => $json];

        $this->elasticsearchClient->index($params);

        return 0;
    }
}
