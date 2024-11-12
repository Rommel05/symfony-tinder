<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/tinder/{id}', name: 'app_tinder', defaults: ['id' => 3])]
    public function tinder($id, UserRepository $userRepository): Response
    {
        $usuario = $userRepository->find($id);
        /*if (!$usuario) {
            //cambiar esto por una plantilla
            throw $this->createNotFoundException('Usuario no encontrado.');
        }*/
        return $this->render('page/tinder.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/swipe', name: 'swipe')]
    public function swipe(UserRepository $userRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $userAId = $request->get('userA');
        $userBId = $request->get('userB');
        $action = $request->get('action');

        $userA = $userRepository->find($userAId);
        $userB = $userRepository->find($userBId);

        if ($userA && $userB && ($action == "0" || $action == "1")) {
            $swipe = new Swipe();
            $swipe->setUserA($userA);
            $swipe->setUserB($userB);
            $swipe->setAction($action);
            $managerRegistry->getManager()->persist($swipe);

            if ($swipe->getAction()) {

            }

        }

    }

}
