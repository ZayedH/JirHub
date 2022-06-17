<?php
namespace App\NewRelic;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewRelicAppReportingPuller
{
    private HttpClientInterface $newRelicClient;
    private int $appId;
    private string $apikey;   
    private string $host; 

    public function __construct(HttpClientInterface $newRelicClient, int $appId , string $apikey,string $host )
    {
        $this->newRelicClient= $newRelicClient;
        $this->appId=$appId;
        $this->apikey=$apikey;    
        $this->host=$host;
    }

    public function fetchInformation() :  string
    {
        $response = $this->newRelicClient->request(
            'GET',
            $this->host.$this->appId.'.json',    
            [
                'headers' => [
                    'X-Api-Key' => $this->apikey,
                ],
            ]                                                  
        );

        
        $content = $response->toArray();   
       
        unset(
            $content['links'],
            $content['application']['settings'],
            $content['application']['links']
        );

        $now     = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        $content['application']['@timestamp']=$now;
        $json=json_encode($content['application']);
      
        return $json;                     
                                        
    }
}
