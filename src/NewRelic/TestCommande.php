<?php
namespace App\NewRelic;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: '1')]

class TestCommande extends Command  {

    public bool $requirePassword ;

   
    protected static $defaultName = '1';

    function __construct( bool $requirePassword = false){

        $this->requirePassword=$requirePassword ;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output):int
    {
        $password=$this->requirePassword;

        $output->writeln([
            '<info>==========================</>',
            $input->getArgument('password')
           
            
        ]);
      

        return 0;


        
    }
    protected function configure(): void
    {
        $this ->setHelp('This command does nothing for instance ...')
              ->addArgument('password', $this->requirePassword ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'User password')
              ;
    }





}