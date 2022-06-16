<?php

namespace App\Controller;
use App\NewRelic\JsonNewRelic;
use App\NewRelic\JsonToElastic;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class RelicController extends AbstractController{


    /**
     * @Route("/Relic", name="Relic")
     */
    public function index(JsonNewRelic $json,JsonToElastic $JsonToElastic ):Response
    {
       dd($JsonToElastic->pushInformation());
        //dd($json->fetchInformation());
        
        return new Response("ggg");

    }
}
