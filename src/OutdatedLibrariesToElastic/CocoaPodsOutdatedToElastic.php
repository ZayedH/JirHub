<?php

namespace App\OutdatedLibrariesToElastic;

use App\OutdatedLibrariesToElastic\ElasticInput\CocoaPodsOutdated;
use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CocoaPodsOutdatedToElastic extends Command
{
    /** @var string */
    protected static $defaultName = 'CocoaPods:outdated-libraries';

    private Client $elasticsearchClient;
    private CocoaPodsOutdated $CocoaPodsOutdated;

    public function __construct(CocoaPodsOutdated $CocoaPodsOutdated, Client $elasticsearchClient)
    {
        parent::__construct();

        $this->CocoaPodsOutdated   = $CocoaPodsOutdated;
        $this->elasticsearchClient = $elasticsearchClient;
    }

    protected function configure()
    {
        $this->setDescription('sending CocoaPods outdated libraries to elasticsearsh');
        $this->addArgument('path', InputArgument::REQUIRED, 'a path to your txt file is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $json   = $this->CocoaPodsOutdated->getCocoaPodsJson($input->getArgument('path'));
        $params = ['index' => 'tiime-chronos(ios)-outdated-libraries', 'body' => $json];

        $this->elasticsearchClient->index($params);

        return 0;
    }
}
