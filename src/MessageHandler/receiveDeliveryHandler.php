<?php


namespace App\MessageHandler;


use App\Message\receiveDelivery;
use App\Repository\ProductRepository;
use App\Service\LogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class receiveDeliveryHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $log;

    private $productRepository;

    private $messageBus;

    public function __construct(EntityManagerInterface $entityManager, LogService $log, ProductRepository $productRepository, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->log = $log;
        $this->productRepository = $productRepository;
        $this->messageBus = $messageBus;
    }
    public function __invoke(receiveDelivery $receiveDelivery)
    {
        if ($receiveDelivery instanceof receiveDelivery) {

            $product = $this->productRepository->find($receiveDelivery->getProductId());

            if ($product) {
                $this->log->addLog('Received new delivery for product id ' . $product->getId() . ' from delivery queue, quantity '.$receiveDelivery->getQuantity(), 'delivery',11,'Warehouse');

                $product->setQuantity($receiveDelivery->getQuantity());
                $product->setStatus(1);
                $this->entityManager->persist($product);
                $this->entityManager->flush();

                $this->log->addLog('Delivery completed, product id ' . $product->getId() . ' activated, added quantity:' . $product->getQuantity(), 'delivery',12,'Warehouse');

            }

        }

    }

}