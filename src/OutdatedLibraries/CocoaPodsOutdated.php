<?php

namespace App\OutdatedLibraries;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CocoaPodsOutdated extends Command
{
    use PatternTrait;
    /** @var string */
    protected static $defaultName = 'collect:cocopods-outdated-libraries';

    protected function configure()
    {
        $this->setDescription('npm collectting outdated libraries ');
        $this->addArgument('path', InputArgument::REQUIRED, 'a path to your txt file is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $path  = $input->getArgument('path');

        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);
        $tab   = $this->generateHeader('Chronos (ios)');
        for ($i = 3; $i < $num; ++$i) {
            $tab[] = $this->patternLigne(explode(' ', $array[$i]));
        }
        $output->writeln(array_filter($tab));


        return 0;
    }
    private function patternLigne(array $ligne): string
    {
        $ligne = array_values(array_filter($ligne));
        $version = $ligne[2];
        $latestVersion = $ligne[4];
        if ($ligne[4] == '(unused)') {
            return  $this->pattern($ligne[1], $version);
        }
        if (!$this->isMajor($version, $latestVersion)) {
            return '';
        }
        return $this->pattern($ligne[1], $version, $latestVersion);
    }
}
