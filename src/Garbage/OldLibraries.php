<?php

namespace App\Garbage;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OldLibraries extends Command
{

    /** @var string */
    protected static $defaultName = 'collect:unupdated-libraries';
    
    protected function configure()
    {
        $this->setDescription('collecting unupdated libraries');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {



        $content = json_decode(file_get_contents('src/Garbage/exemple.json'), true);
        $tab = [];
        foreach ($content['installed'] as $value) {
            $name = $value['name'];
            $version = $value['version'];
            $latestVersion = $value['latest'];
            $latestStatus = $value['latest-status'];
            $isAbandoned = $value['abandoned'];
            if ($isAbandoned || is_string($isAbandoned)) {

                $tab[] = "| ⚠️" . " " . " $name  | $version  | abandonné |";
            } else {

                $pieces = explode('/', $name);
                if ($pieces[0] == 'symfony') {
                    if ($pieces[1] == "http-kernel") {
                        array_unshift($tab, "| ⚠️" . " " . " $pieces[0]  | $version  | $latestVersion |");
                    }
                } else if ($latestStatus != 'semver-safe-update') {
                    $tab[] = "| ⚠️" . " " . " $name  | $version  | $latestVersion |";
                }
            }
        }

        array_unshift($tab, '| Chronos (API) | version  | version disponible |', '| --- | --- | --- |');
        $output->writeln($tab);


        return 0;
    }
}
