<?php

namespace App\Garbage;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OldLibraries extends Command
{

    /** @var string */
    protected static $defaultName = 'collect:unupdated-libraries';

    //private string $makeComposer;

//    public function __construct()
//    {
//     parent::__construct();
//    }
    protected function configure()
    {
        $this->setDescription('collecting unupdated libraries');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        
        
        //$result = shell_exec('composer outdated --format=json > src/Garbage/exemple.json'); ??????
        // on va l'appeler à intervalles réguliers
        $content = json_decode(file_get_contents('src/Garbage/exemple.json'),true);
        $tab=['| Chronos (API) | version  | version disponible |',
            '| --- | --- | --- |'];
       foreach($content['installed'] as $value){
        $name=$value['name'];
        $version=$value['version'];
        $latestVersion =$value['latest'];   
        if($value['abandoned']){
          
           $tab[] ="| $name  | $version  | abandonné |";
           
        
        }else{
            
               $tab[]= "| $name  | $version  | $latestVersion |";      

            
            
        }

       }
       $output->writeln($tab);

        
        return 0;
    }

    
}
