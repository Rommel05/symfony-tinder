<?php

namespace App\Controller;

use App\Repository\PairRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    #[Route('/match', name: 'app_match')]
    public function match(PairRepository $pairRepository): Response
    {
        $user = $this->getUser();
        $pairs = $pairRepository->findBy(['userA' => $user]);
        return $this->render('match/match.html.twig', [
            'pairs' => $pairs,
        ]);
    }
}
