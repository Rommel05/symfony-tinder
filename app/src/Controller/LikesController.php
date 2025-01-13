<?php

namespace App\Controller;

use App\Repository\PairRepository;
use App\Repository\SwipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikesController extends AbstractController
{
    #[Route('/likes', name: 'app_likes')]
    public function likes(SwipeRepository $swipeRepository, PairRepository $pairRepository): Response
    {
        $user = $this->getUser();

        $usersMatched = $pairRepository->createQueryBuilder('p')
            ->select('IDENTITY(p.userB)')
            ->where('p.userA = :user')
            ->orWhere('p.userB = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        $excludeIds = array_column($usersMatched, 1);

        $likes = $swipeRepository->createQueryBuilder('l')
            ->where('l.userB = :user')
            ->andWhere('l.action = true')
            ->andWhere('l.userA NOT IN (:excludeIds)')
            ->setParameter('user', $user)
            ->setParameter('excludeIds', $excludeIds ?: [0])
            ->getQuery()
            ->getResult();

        return $this->render('likes/index.html.twig', [
            'likes' => $likes,
        ]);
    }

}
