<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/tinder', name: 'app_tinder')]
    public function index(): Response
    {
        return $this->render('page/tinder.html.twig', []);
    }

    private $images = ["images/image2.jpg", "images/image3.jpg", "images/image4.jpg", "images/image1.jpg"];
    private $currentIndex = 0;
    #[Route('/tinder/image', name: 'app_tinder_image')]
    public function getImage(): JsonResponse
    {
        if ($this->currentIndex >= count($this->images)) {
            $this->currentIndex = 0;
        }

        //$imagePath = $this->images[$this->currentIndex];
        $imagePath = $this->generateUrl($this->images[$this->currentIndex]);
        $this->currentIndex++;
        return new JsonResponse(['image' => $imagePath]);

    }
}
