<?php

namespace App\Controller;

use App\Entity\Pair;
use App\Entity\Swipe;
use App\Repository\SwipeRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PageController extends AbstractController
{
    #[Route('/tinder', name: 'app_tinder')]
    public function swipe(UserRepository $userRepository, ManagerRegistry $managerRegistry): Response
    {
        $userA = $this->getUser();
        $entityManager = $managerRegistry->getManager();

        $gender = ($userA->getGender() === 'man') ? 'woman' : 'man';

        $swipedUsers = $entityManager->getRepository(Swipe::class)
            ->createQueryBuilder('s')
            ->select('IDENTITY(s.userB) as userB')
            ->where('s.userA = :userA')
            ->setParameter('userA', $userA)
            ->getQuery()
            ->getResult();

        $excludedIds = array_column($swipedUsers, 'userB');

        $nextUser = $userRepository->findNextUser($userA, $excludedIds, $gender);

        return $this->render('page/tinder.html.twig', [
            'usuario' => $nextUser,
            'nextUserId' => $nextUser ? $nextUser->getId() : null,
        ]);
    }


    #[Route('/like/{id?}', name: 'app_like')]
    public function like(UserRepository $userRepository, Request $request, ManagerRegistry $managerRegistry, int $id, SwipeRepository $swipeRepository): Response
    {
        if ($id === null) {
            return $this->redirectToRoute('app_tinder');
        }

        $userA = $this->getUser();
        $userB = $userRepository->find($id);

        if (!$userB || $userB === $userA) {
            return $this->redirectToRoute('app_tinder');
        }

        $entityManager = $managerRegistry->getManager();

        $existSwipe = $entityManager->getRepository(Swipe::class)->findOneBy([
            'userA' => $userA,
            'userB' => $userB,
        ]);

        if (!$existSwipe) {
            $swipe = new Swipe();
            $swipe->setUserA($userA);
            $swipe->setUserB($userB);
            $swipe->setAction(true);
            $entityManager->persist($swipe);

            $match = $entityManager->getRepository(Swipe::class)->findOneBy([
                'userA' => $userB,
                'userB' => $userA,
                'action' => true,
            ]);

            if ($match) {
                $pairA = new Pair();
                $pairA->setUserA($userA);
                $pairA->setUserB($userB);
                $entityManager->persist($pairA);

                $pairB = new Pair();
                $pairB->setUserA($userB);
                $pairB->setUserB($userA);
                $entityManager->persist($pairB);
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_tinder');
    }

    #[Route('/unlike/{id?}', name: 'app_unlike')]
    public function unlike(UserRepository $userRepository, Request $request, ManagerRegistry $managerRegistry, int $id): Response
    {
        if ($id === null) {
            return $this->redirectToRoute('app_tinder');
        }

        $userA = $this->getUser();
        $userB = $userRepository->find($id);

        if (!$userB || $userB === $userA) {
            return $this->redirectToRoute('app_tinder');
        }

        $entityManager = $managerRegistry->getManager();

        $existSwipe = $entityManager->getRepository(Swipe::class)->findOneBy([
            'userA' => $userA,
            'userB' => $userB,
        ]);

        if (!$existSwipe) {
            $swipe = new Swipe();
            $swipe->setUserA($userA);
            $swipe->setUserB($userB);
            $swipe->setAction(false);
            $entityManager->persist($swipe);

            foreach ($userB->getSwipesAsUserA() as $swipeB) {
                if ($swipeB->getUserB() === $userA && $swipeB->getAction()) {
                    $pairA = new Pair();
                    $pairA->setUserA($userA);
                    $pairA->setUserB($userB);
                    $entityManager->persist($pairA);

                    $pairB = new Pair();
                    $pairB->setUserA($userB);
                    $pairB->setUserB($userA);
                    $entityManager->persist($pairB);
                }
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_tinder');
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('Symfony maneja el proceso de logout automáticamente.');
    }

}
