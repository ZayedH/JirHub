<?php

namespace App\OutdatedLibrariesToElastic;

use App\OutdatedLibrariesToElastic\ElasticInput\NpmOutdated;
use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NpmOutdatedToElastic extends Command
{
    /** @var string */
    protected static $defaultName = 'npm:outdated-libraries';

    private Client $elasticsearchClient;
    private NpmOutdated $NpmOutdated;

    public function __construct(NpmOutdated $NpmOutdated, Client $elasticsearchClient)
    {
        parent::__construct();

        $this->NpmOutdated         = $NpmOutdated;
        $this->elasticsearchClient = $elasticsearchClient;
    }

    protected function configure()
    {
        $this->setDescription('sending npm outdated libraries to elasticsearsh');
        $this->addArgument('path', InputArgument::REQUIRED, 'a path to your txt file is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $json   = $this->NpmOutdated->getNpmJson($input->getArgument('path'));
        $params = ['index' => 'tiime-chronos(web)-outdated-libraries', 'body' => $json];

        $this->elasticsearchClient->index($params);

        return 0;
    }
}
