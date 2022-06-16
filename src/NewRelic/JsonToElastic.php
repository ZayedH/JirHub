<?php
namespace App\NewRelic;

use App\NewRelic\JsonNewRelic;
use Elasticsearch\Client;
use  Elasticsearch\ClientBuilder;
//use Symfony\Component\Console\Command\Command;
//use Symfony\Component\Console\Input\InputInterface;   // pour le moment pas de ligne de commande pour faire des tests via Reliccontrôleur  
//use Symfony\Component\Console\Output\OutputInterface;

class JsonToElastic
{
    private Client $elasticsearchClient;

    private JsonNewRelic $JsonNewRelic;


    public function __construct(Client $client,JsonNewRelic $JsonNewRelic )
    {
        $this->elasticsearchClient=$client;

        $this->JsonNewRelic=$JsonNewRelic;
    }

    public function  pushInformation()
    {   

        /*$client=ClientBuilder::create()
               ->setHosts(['localhost:9200'])
               ->build();**/                              // No node ??????
         $json=$this->JsonNewRelic->fetchInformation();
         $now     = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        
        $params  = ['index' =>"tiime-chronos-newrelic-production",'body' => $json];
       $this->elasticsearchClient->index($params);
        return $this->elasticsearchClient;

    }

}

