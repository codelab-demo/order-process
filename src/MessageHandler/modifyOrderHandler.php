<?php


namespace App\MessageHandler;

use App\Message\email;
use App\Message\modifyOrder;
use App\Repository\OrderRepository;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Service\LogService;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;


class modifyOrderHandler implements MessageHandlerInterface
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
    public function __invoke(modifyOrder $modifyOrder)
    {
        if($modifyOrder instanceof modifyOrder) {
            /** @var Order $order */
            $order = $this->orderRepository->find($modifyOrder->getOrderId());

            if($order) {

                $order->setStatus($modifyOrder->getOrderStatus());
                $this->entityManager->persist($order);
                $this->entityManager->flush();
                $this->log->addLog('Received request to change order id '.$order->getId().' status to '.$order->getStatus(),'order',13,'ecommerce');

                $message = new email($order->getId(),$order->getStatus());
                $envelope = new Envelope($message, [
                    new DelayStamp(2000)
                ]);
                $this->messageBus->dispatch($envelope);
                $this->log->addLog('Order status changed, sending request to mailer','email',14,'ecommerce');
            }
        }
    }

}