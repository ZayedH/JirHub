<?php

namespace App\OutdatedLibrariesToElastic;

use App\OutdatedLibrariesToElastic\ElasticInput\AndroidOutdated;
use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AndroidOutdatedLibrariesToElastic extends Command
{
    /** @var string */
    protected static $defaultName = 'android:outdated-libraries';

    private Client $elasticsearchClient;
    private AndroidOutdated $AndroidOutdated;

    public function __construct(AndroidOutdated $AndroidOutdated, Client $elasticsearchClient)
    {
        parent::__construct();

        $this->AndroidOutdated     = $AndroidOutdated;
        $this->elasticsearchClient = $elasticsearchClient;
    }

    protected function configure()
    {
        $this->setDescription('sending android outdated libraries to elasticsearsh');
        $this->addArgument('path', InputArgument::REQUIRED, 'a path to your txt file is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $json   = $this->AndroidOutdated->getAndroidJson($input->getArgument('path'));
        $params = ['index' => 'tiime-chronos(android)-outdated-libraries', 'body' => $json];

        $this->elasticsearchClient->index($params);

        return 0;
    }
}
