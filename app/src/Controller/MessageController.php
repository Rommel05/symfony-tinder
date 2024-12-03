<?php

namespace App\Controller;

use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message/{id}', name: 'app_message')]
    public function chat($id, MessageRepository $messageRepository, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sender = $this->getUser();
        $receiver = $userRepository->find($id);

        if (!$receiver) {
            throw $this->createNotFoundException('user not found');
        }

        $messages = $messageRepository->createQueryBuilder('m')
            ->where('(m.sennder = :idSender AND m.receiver = :idReceiver)')
            ->orWhere('(m.sennder = :idReceiver AND m.receiver = :idSender)')
            ->orderBy('m.date', 'ASC')
            ->setParameter('idSender', $sender->getId())
            ->setParameter('idReceiver', $receiver->getId())
            ->getQuery()
            ->getResult();

        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $message->setSennder($sender);
            $message->setReceiver($receiver);
            $message->setDate(new \DateTime('now'));
            $message->setIsRead(false);

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_message', ['id' => $id]);

        }

        return $this->render('message/index.html.twig', [
            'messages' => $messages,
            'form' => $form->createView(),

        ]);

    }
}
