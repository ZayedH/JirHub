<?php

namespace App\Controller;

use App\Handler\ChangelogHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangelogController extends AbstractController
{
    /** @var ChangelogHandler */
    private $handler;

    public function __construct(ChangelogHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/changelog", name="get_changelog", methods={"GET"})
     */
    public function index()
    {
        $response = new Response(implode(PHP_EOL, $this->handler->getProductionChangelog()));
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }

    /**
     * @Route("/commits", name="commits", methods={"GET"})
     */
    public function commits()
    {
        return $this->render('dashboard/commits.html.twig', $this->handler->getCommitsLinks());
    }
}
