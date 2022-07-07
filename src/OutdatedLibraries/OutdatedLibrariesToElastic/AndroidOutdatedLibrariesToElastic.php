<?php

namespace App\OutdatedLibraries\OutdatedLibrariesToElastic;

use App\OutdatedLibraries\OutdatedLibrariesToElastic\ElasticInput\AndroidOutdated;
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
        $this->addArgument('name', InputArgument::REQUIRED, 'the name of your project is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if ($this->verifyNameAndroid($name)) {
            $json   = $this->AndroidOutdated->getAndroidJson($input->getArgument('path'), $name);
            $params = ['index' => 'tiime-chronos-outdated-libraries', 'body' => $json];

            $this->elasticsearchClient->index($params);

            return 0;
        } else {
            throw new \LogicException('Make sure the name of your project is in "Invoice Android, Accounts Android".');

            return 1;
        }
    }

    private function verifyNameAndroid(string $name): bool
    {
        if ('Invoice Android' === $name || 'Accounts Android' === $name) {
            return true;
        }

        return false;
    }
}
