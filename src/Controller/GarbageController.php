<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Garbage\test;

class GarbageController extends AbstractController {

    

 /**
     * @Route("/Garbage", methods={"GET"})
     */


public function index(test $test){

    dd($test->test());

}

}