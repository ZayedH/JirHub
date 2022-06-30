<?php

namespace App\Controller;

use App\NpmOutdated\test;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NpmController extends AbstractController
{
    /**
     * @Route("/npm", methods={"GET"})
     */
    public function index(test $test)
    {
        return $test->test();
    }
}
