<?php

namespace App\Controller;
use App\NewRelic\NewRelicAppReportingPuller;
use App\NewRelic\RecordNewRelicMetrics;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class RelicController extends AbstractController{


    /**
     * @Route("/Relic", name="Relic")
     */
    public function index(NewRelicAppReportingPuller $json,RecordNewRelicMetrics $JsonToElastic ):Response
    {
       //dd($JsonToElastic->pushInformation());
        dd($json->fetchInformation());
        
        return new Response("ggg");

    }
}