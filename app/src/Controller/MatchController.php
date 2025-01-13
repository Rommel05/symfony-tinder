<?php

namespace App\Controller;

use App\Repository\PairRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    #[Route('/match', name: 'app_match')]
    public function match(PairRepository $pairRepository, Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createFormBuilder()
            ->add('search', SearchType::class, [
                'label' => 'Match',
                'attr' => [
                    'placeholder' => 'Find your match...',
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        $pairs = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $searchParameter = $form->get('search')->getData();
            $pairs = $pairRepository->findBySearchParameter($user,$searchParameter);
        } else {
            $pairs = $pairRepository->findBy(['userA' => $user]);
        }

        return $this->render('match/match.html.twig', [
            'pairs' => $pairs,
            'form' => $form->createView(),
        ]);
    }
}
