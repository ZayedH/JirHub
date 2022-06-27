<?php

namespace App\OutdatedLibraries;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class OutdatedLibraries extends Command
{

    /** @var string */
    protected static $defaultName = 'collect:outdated-libraries';

    protected function configure():void
    {
        $this->setDescription('collecting outdated libraries');
        $this->addArgument('path', InputArgument::REQUIRED, 'a path to your json file is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {


        $path=$input->getArgument('path');
        $content = json_decode(file_get_contents($path), true);
        $tab = [];
        foreach ($content['installed'] as $value) {
            $name = $value['name'];
            $version = $value['version'];
            $latestVersion = $value['latest'];
            $latestStatus = $value['latest-status'];
            $isAbandoned = $value['abandoned'];
            if ($isAbandoned || is_string($isAbandoned)) {

                $tab[] =$this-> pattern($name,$version);
            } else {

                $pieces = explode('/', $name);
                if ($pieces[0] == 'symfony') {
                    if ($pieces[1] == "http-kernel") {
                        array_unshift($tab, $this->pattern( $pieces[0]  , $version  , $latestVersion ));
                    }
                } else if ($latestStatus != 'semver-safe-update') {
                    $tab[] = $this->pattern($name, $version , $latestVersion );
                }
            }
        }

        array_unshift($tab, '| Chronos (API) | version  | version disponible |', '| --- | --- | --- |');
        $output->writeln($tab);


        return 0;
    }

    private function pattern($name,$version,$latestVersion='abandonné'){


        return "| ⚠️" . " " . " $name  | $version  | $latestVersion |";
    }
}
