<?php

namespace App\Controller;

use App\Repository\SwipeRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikesController extends AbstractController
{
    #[Route('/likes', name: 'app_likes')]
    public function likes(SwipeRepository $swipeRepository): Response
    {
        $user = $this->getUser();
        $likes = $swipeRepository->findBy(['userB' => $user, 'action' => '1']);
        return $this->render('likes/index.html.twig', [
            'likes' => $likes,
        ]);

    }
}
