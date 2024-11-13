<?php

namespace App\Controller;

use App\Entity\Pair;
use App\Entity\Swipe;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/tinder/{id?}', name: 'app_tinder')]
    public function swipe(UserRepository $userRepository, Request $request, ManagerRegistry $managerRegistry, $id = null): Response
    {
        $userA = $this->getUser();
        if ($id == null) {
            $nextUser = $userRepository->findUserExcludeCurrent($userA);
            if (!$nextUser) {
                return $this->render('page/tinder.html.twig', [
                    'usuario' => null,
                ]);
            }
            return $this->redirectToRoute('app_tinder', ['id' => $nextUser->getId()]);

        }


        $userB = $userRepository->find($id);

        if ($userA == $userB) {
            return $this->redirectToRoute('app_tinder');
        }
        $action = $request->get('action');

        $entityManager = $managerRegistry->getManager();

        if ($action != null) {

            $existingSwipe = $entityManager->getRepository(Swipe::class)->findOneBy(['userA' => $userA, 'userB' => $userB]);

            if (!$existingSwipe) {
                $swipe = new Swipe();
                $swipe->setUserA($userA);
                $swipe->setUserB($userB);
                $swipe->setAction((bool) $action);
                $entityManager->persist($swipe);

                if ($swipe->getAction()) {
                    foreach ($userB->getSwipesAsUserA() as $swipeB) {
                        if ($swipeB->getUserB() === $userA && $swipeB->getAction()) {
                            $pairA = new Pair();
                            $pairA->setUserA($userA);
                            $pairA->setUserB($userB);
                            $entityManager->persist($pairA);

                            $pairB = new Pair();
                            $pairB->setUserA($userA);
                            $pairB->setUserB($userB);
                            $entityManager->persist($pairB);
                        }
                    }
                }

                $entityManager->flush();
            }
        }
        return $this->render('page/tinder.html.twig', [
            'usuario' => $userB,
        ]);

    }

}
