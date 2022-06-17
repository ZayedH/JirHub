<?php
namespace App\NewRelic;

use App\NewRelic\JsonNewRelic;
use Elasticsearch\Client;


class JsonToElastic
{
    private Client $elasticsearchClient;
    private JsonNewRelic $jsonNewRelic;


    public function __construct(Client $client,JsonNewRelic $jsonNewRelic )
    {
        $this->elasticsearchClient=$client;

        $this->jsonNewRelic=$jsonNewRelic;
    }

    public function  pushInformation()
    {   

        
         $json=$this->jsonNewRelic->fetchInformation();
         $now     = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        
        $params  = ['index' =>"tiime-chronos-newrelic-production",'body' => $json];
                                                          
     
       $this->elasticsearchClient->index($params);
      
     
        return $this->elasticsearchClient;

    }

}

