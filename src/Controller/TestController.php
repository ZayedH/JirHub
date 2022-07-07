<?php

namespace App\Controller;

use App\OutdatedLibraries\OutdatedLibrariesToElastic\ElasticInput\AndroidOutdated;
use App\OutdatedLibraries\OutdatedLibrariesToElastic\ElasticInput\CocoaPodsOutdated;
use App\OutdatedLibraries\OutdatedLibrariesToElastic\ElasticInput\ComposerOutdated;
use App\OutdatedLibraries\OutdatedLibrariesToElastic\ElasticInput\NpmOutdated;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/json",methods={"GET"})
     */
    public function index(ComposerOutdated $ComposerOutdated, NpmOutdated $NpmOutdated, CocoaPodsOutdated $CocoaPodsOutdated, AndroidOutdated $AndroidOutdated): Response
    {
        dd($ComposerOutdated->getComposerJson('../src/FichiersTest/exemple.json', 'chronos'));
        // dd($NpmOutdated->getNpmJson('../src/FichiersTest/npm.txt','npm'));
        // dd($CocoaPodsOutdated->getCocoaPodsJson('../src/FichiersTest/ios.txt','ios'));
        // dd($AndroidOutdated->getAndroidJson('../src/FichiersTest/android.txt', 'android'));

        return new response('json');
    }
}
