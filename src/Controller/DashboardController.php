<?php

namespace App\Controller;

use App\Dashboard\Handler\DashboardHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /** @var DashboardHandler */
    protected $handler;

    /**
     * DashboardController constructor.
     */
    public function __construct(DashboardHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/dashboard", name="get_dashboard", methods={"GET"})
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig', $this->handler->getData());
    }
}
