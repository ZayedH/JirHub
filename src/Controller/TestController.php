<?php

namespace App\Controller;

use App\OutdatedLibrariesToElastic\ElasticInput\AndroidOutdated;
use App\OutdatedLibrariesToElastic\ElasticInput\CocoaPodsOutdated;
use App\OutdatedLibrariesToElastic\ElasticInput\ComposerOutdated;
use App\OutdatedLibrariesToElastic\ElasticInput\NpmOutdated;
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
      //   dd($ComposerOutdated->getComposerJson('../src/FichiersTest/exemple.json'));
         //  dd($NpmOutdated->getNpmJson('../src/FichiersTest/npm.txt'));
      //   dd($CocoaPodsOutdated->getCocoaPodsJson('../src/FichiersTest/ios.txt'));
        dd($AndroidOutdated->getAndroidJson('../src/FichiersTest/android.txt'));

        return new response('json');
    }
}
