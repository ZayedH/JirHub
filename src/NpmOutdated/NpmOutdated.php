<?php

namespace App\NpmOutdated;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NpmOutdated extends Command
{
    use PatternTrait;
    /** @var string */
    protected static $defaultName = 'npm:oudated';

    protected function configure()
    {
        $this->setDescription('npm collectting outdated libraries ');
    }


    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $tab = ['| Chronos (web) | version  | version disponible |', '| --- | --- | --- |'];
        $array = explode("\n", file_get_contents('src/NpmOutdated/fichier.txt'));
        $array=array_filter($array);
        $num = count($array);
        for ($i = 1; $i < $num; $i++) { 
            $tab[] = $this->patternLigne(explode(' ', $array[$i]));
        }
        $output->writeln($tab);
        return 0;
    }

    private function patternLigne(array $ligne): string
    {
        $name = $ligne[0];
        $num = count($ligne);
        $k = 0;
        for ($i = 1; $i < $num; $i++) {

            if ($ligne[$i] != '') {
                $k++;
                if ($k == 1) {
                    $version = $ligne[$i];
                }
                if ($k == 3) {
                    $latestVersion = $ligne[$i];
                    break;
                }
            }
        }

        return $this->pattern($name,$version,$latestVersion);
    }
    
}
