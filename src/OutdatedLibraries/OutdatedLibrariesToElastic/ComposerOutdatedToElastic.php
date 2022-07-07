<?php

namespace App\OutdatedLibraries\OutdatedLibrariesToElastic;

use App\OutdatedLibraries\OutdatedLibrariesToElastic\ElasticInput\ComposerOutdated;
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
        $this->addArgument('name', InputArgument::REQUIRED, 'the name of your project is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if ($this->verifyNameChronos($name)) {
            $json   = $this->ComposerOutdated->getComposerJson($input->getArgument('path'), $name);
            $params = ['index' => 'tiime-chronos-outdated-libraries', 'body' => $json];
            $this->elasticsearchClient->index($params);

            return 0;
        } else {
            throw new \LogicException('Make sure the name of your project is in "Chronos".');

            return 1;
        }
    }

    private function verifyNameChronos(string $name): bool
    {
        if ('Chronos' === $name) {
            return true;
        }

        return false;
    }
}
