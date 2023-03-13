<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/messages')]
class MessageController extends AbstractController
{

    const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine'];

    private $entityManager;
    private $messageRepository;
    private $participantRepository;

    public function __construct(EntityManagerInterface $entityManager, MessageRepository $messageRepository, ParticipantRepository $participantRepository)
    {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;    
        $this->participantRepository = $participantRepository;
    }



    #[Route('/{id}', name: 'getMessages', methods:'GET')]
    public function index( Request $request, Conversation $conversation): Response
    {
        // verif si je peux visualiser les messages
        $this->denyAccessUnlessGranted('view', $conversation);

        $messages = $this->messageRepository->findMessagesByConversationId($conversation->getId());


        array_map(function ($message) {
            $message->setMine(
                $message->getUser()->getId() === $this->getUser()->getId()
                    ? true : false
            );
        }, $messages);

        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    #[Route('/{id}', name: 'newMessage', methods:'POST')]
    public function newMessage(Request $request, Conversation $conversation, SerializerInterface $serializer, HubInterface $hub)
    {
        $user = $this->getUser();

        $recipient = $this->participantRepository->findParticipantByConverstionIdAndUserId(
            $conversation->getId(),
            $user->getId()
        );

        $content = $request->get('content', null);
        $message = new Message();
        $message->setContent($content);
        $message->setUser($user);
        $message->setMine(true);

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($message);
            $this->entityManager->persist($conversation);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }  

        $message->setMine(false);
        $messageSerialized = $serializer->serialize($message, 'json', [
            'attributes' => ['id', 'content', 'createdAt', 'mine', 'conversation' => ['id']]
        ]);

        $update = new Update(
            [
                sprintf("/conversations/%s", $conversation->getId()),
                sprintf("/conversations/%s", $recipient->getUser()->getUsername()),
            ],
            $messageSerialized,
            
        );

        $hub->publish($update);

        return $this->json($message, Response::HTTP_CREATED, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }
}
