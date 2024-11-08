<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/tinder', name: 'app_tinder')]
    public function index(): Response
    {
        return $this->render('page/tinder.html.twig', []);
    }
}
