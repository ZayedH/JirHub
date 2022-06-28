<?php

namespace App\OutdatedLibraries;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ComposerOutdated extends Command
{
    use PatternTrait;
    /** @var string */
    protected static $defaultName = 'collect:composer-outdated-libraries';

    protected function configure(): void
    {
        $this->setDescription('collecting outdated libraries');
        $this->addArgument('path', InputArgument::REQUIRED, 'a path to your json file is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $path    = $input->getArgument('path');
        $content = json_decode(file_get_contents($path), true);
        $tab     = [];

        foreach ($content['installed'] as $value) {
            $name          = $value['name'];
            $version       = $value['version'];
            $latestVersion = $value['latest'];
            $latestStatus  = $value['latest-status'];
            $isAbandoned   = $value['abandoned'];

            if ($isAbandoned || \is_string($isAbandoned)) {
                $tab[] = $this->pattern($name, $version);
            } else {
                $pieces = explode('/', $name);

                if ('symfony' === $pieces[0]) {
                    if ('http-kernel' === $pieces[1]) {
                        array_unshift($tab, $this->pattern($pieces[0], $version, $latestVersion));
                    }
                } elseif ('semver-safe-update' !== $latestStatus) {
                    $tab[] = $this->pattern($name, $version, $latestVersion);
                }
            }
        }
        $header = $this->generateHeader('API');
        array_unshift($tab, $header[0], $header[1]);
        $output->writeln($tab);

        return 0;
    }
}
