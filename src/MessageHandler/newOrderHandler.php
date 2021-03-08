<?php


namespace App\MessageHandler;

use App\Message\modifyOrder;
use App\Message\newDelivery;
use App\Message\newOrder;
use App\Repository\OrderRepository;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Service\LogService;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class newOrderHandler implements MessageHandlerInterface
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
    public function __invoke(newOrder $newOrder)
    {
        if($newOrder instanceof newOrder) {
            /** @var Order $order */
            $order = $this->orderRepository->find($newOrder->getOrderId());

            if($order) {
                $this->log->addLog('Order '.$order->getId().' received from order queue','order',3,'Warehouse');
                $product = $order->getProduct();
                if($product->getQuantity() < $order->getQuantity()) {

                        $message = new modifyOrder($order->getId(),0);
                        $envelope = new Envelope($message, [
                            new DelayStamp(5000)
                        ]);
                        $this->messageBus->dispatch($envelope);
                        $this->log->addLog('Sending cancel order request to order queue (No stock)','order',4,'Warehouse');
                        if($product->getStatus()) {
                            $product->setStatus(0);
                            $this->entityManager->persist($product);
                            $this->entityManager->flush();
                            $this->callDelivery($product);
                            $this->log->addLog('Sending requested delivery to delivery queue (No stock)','delivery',5,'Warehouse');
                        }

                    } else {
                    $product->setQuantity($product->getQuantity() - $order->getQuantity());
                    $this->entityManager->persist($product);
                    $this->entityManager->flush();
                    $message = new modifyOrder($order->getId(),2);
                    $envelope = new Envelope($message, [
                        new DelayStamp(5000)
                    ]);
                    $this->messageBus->dispatch($envelope);

                    $this->log->addLog('Sending complete order request to order queue','order',7,'Warehouse');
                    if($product->getQuantity() == 0) {
                        $this->callDelivery($product);
                        $this->log->addLog('Sending product delivery request to delivery queue (stock reached 0)','delivery',8,'Warehouse');
                    }
                }
            }
        }

    }

    private function callDelivery($product) {
        $message = new newDelivery($product->getId());
        $envelope = new Envelope($message, [
            new DelayStamp(10000)
        ]);
        $this->messageBus->dispatch($envelope);
    }

}