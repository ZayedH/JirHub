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
        $path = $input->getArgument('path');

        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);
        $tab   = $this->generateHeader('Chronos (android)');
        $k     = 0;

        for ($i = 0; $i < $num; ++$i) {
            if ('Gradle release-candidate updates:' === $array[$i]) {
                break;
            }

            if (1 === $k) {
                $tab[] = $this->patternLigne($array[$i]);
            }

            if ('The following dependencies have later milestone versions:' === $array[$i]) {
                $k = 1;
            }
        }
        $output->writeln(array_filter($tab));

        return 0;
    }

    private function patternLigne(string $ligne): string
    {
        $tab = explode(' ', $ligne);

        if ('-' !== $tab[1]) {
            return '';
        }
        $version = explode('[', $tab[3])[1];
        $latestVersion = explode(']', explode('-', $tab[5])[0])[0];
        if (!$this->isMajor($version, $latestVersion)) {
            return '';
        }
        return $this->pattern($tab[2], $version, $latestVersion);
    }
}
