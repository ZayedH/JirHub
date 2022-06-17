<?php
namespace App\NewRelic;

use App\NewRelic\NewRelicAppReportingPuller;
use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RecordNewRelicMetrics extends Command
{ 
    /** @var string */
    protected static $defaultName = 'record:newrelic-metrics';

    private Client $elasticsearchClient;
    private NewRelicAppReportingPuller $jsonNewRelic;


    public function __construct(Client $client,NewRelicAppReportingPuller $jsonNewRelic )
    {   
        parent::__construct();

        $this->elasticsearchClient=$client;
        $this->jsonNewRelic=$jsonNewRelic;
    }

    protected function configure()
    {
        $this->setDescription('newrelic');
    }

    public function  execute(InputInterface $input, OutputInterface $output):int
    {   

        
         $json=$this->jsonNewRelic->fetchInformation();
         $now     = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        
        $params  = ['index' =>"tiime-chronos-newrelic-production",'body' => $json];
                                                          
     
         $this->elasticsearchClient->index($params);

         return 0;
     

    }

}

