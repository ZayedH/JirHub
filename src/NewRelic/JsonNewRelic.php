<?php
namespace App\NewRelic;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JsonNewRelic
{
    private HttpClientInterface $newRelicClient;
    private int $appId;
    private string $Relickey;   //. config
    private string $RelicHost; ///

    public function __construct(HttpClientInterface $newRelicClient, int $appId , string $Relickey,string $RelicHost )
    {
        $this->newRelicClient= $newRelicClient;
        $this->appId=$appId;
        $this->Relickey=$Relickey;
        $this->RelicHost=$RelicHost;
    }

    public function fetchInformation()      
    {
        $response = $this->newRelicClient->request(
            'GET',
            $this->RelicHost.$this->appId.'.json',    
            [
                'headers' => [
                    'X-Api-Key' => $this->Relickey,
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