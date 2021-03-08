<?php


namespace App\MessageHandler;

use App\Message\email;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Service\LogService;
use Symfony\Component\Messenger\MessageBusInterface;


class emailHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $log;

    private $orderRepository;

    private $messageBus;

    public function __construct(EntityManagerInterface $entityManager, LogService $log, OrderRepository $orderRepository, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->log = $log;
        $this->orderRepository = $orderRepository;
        $this->messageBus = $messageBus;
    }
    public function __invoke(email $email)
    {
        $this->log->addLog('Received request from mailer queue, email for order id  '.$email->getOrderId().' with status '.$email->getStatus().' has been sent','email',15,'Mailer');


    }


}