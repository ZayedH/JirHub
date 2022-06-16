<?php
namespace App\NewRelic;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JsonNewRelic
{
    private HttpClientInterface $relicClient;
    private int $RelicAppi;
    private string $Relickey;
    private string $RelicHost;

    public function __construct(HttpClientInterface $relicClient, int $RelicAppi , string $Relickey,string $RelicHost )
    {
        $this->relicClient= $relicClient;
        $this->RelicAppi=$RelicAppi;
        $this->Relickey=$Relickey;
        $this->RelicHost=$RelicHost;
    }

    public function fetchInformation()      
    {
        $response = $this->relicClient->request(
            'GET',

            $this->RelicHost.$this->RelicAppi.'.json',    
            [
                'headers' => [
                    'X-Api-Key' => $this->Relickey,
                ],
            ]                                                  

            
        );

        
        $content = $response->toArray();   
        //$json=json_decode($content);
        unset($content['links']);
        unset($content['application']['settings']);
        unset($content['application']['links']);

        $now     = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        $content['application']['@timestamp']=$now;
        $json=json_encode($content['application']);
      
        return $json;                     
                                        
    }
}