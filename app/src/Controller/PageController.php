<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/tinder/{id}', name: 'app_tinder', defaults: ['id' => 1])]
    public function prueba($id, UserRepository $userRepository): Response
    {
        $usuario = $userRepository->find($id);
        if (!$usuario) {
            //cambiar esto por una plantilla
            throw $this->createNotFoundException('Usuario no encontrado.');
        }
        return $this->render('page/tinder.html.twig', [
            'usuario' => $usuario,
        ]);
    }


}
