<?php

namespace App\OutdatedLibraries;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AndroidOutdated extends Command
{
    use PatternTrait;
    /** @var string */
    protected static $defaultName = 'collect:android-outdated-libraries';

    protected function configure()
    {
        $this->setDescription('android collectting outdated libraries ');
        $this->addArgument('path', InputArgument::REQUIRED, 'a path to your txt file is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $path  = $input->getArgument('path');

        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);
        $tab   = $this->generateHeader('Chronos (android)');
        $k = 0;
        for ($i = 0; $i < $num; $i++) {
            if ($array[$i] == 'Gradle release-candidate updates:') {
                break;
            }
            if ($k == 1) {
                $tab[] = $this->patternLigne($array[$i]);
            }
            if ($array[$i] == 'The following dependencies have later milestone versions:') {
                $k = 1;
            }
        }
        $output->writeln(array_filter($tab));
        return 0;
    }
    private function patternLigne(string $ligne): string
    {
        $tab = explode(' ', $ligne);
        if ($tab[1] != '-') {
            return '';
        }
        return $this->pattern($tab[2], explode('[', $tab[3])[1], explode(']', explode('-', $tab[5])[0])[0]);
    }
}
