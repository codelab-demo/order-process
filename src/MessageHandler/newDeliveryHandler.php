<?php


namespace App\MessageHandler;


use App\Message\newDelivery;
use App\Message\receiveDelivery;
use App\Service\LogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class newDeliveryHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $log;


    private $messageBus;

    public function __construct(EntityManagerInterface $entityManager, LogService $log, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->log = $log;
        $this->messageBus = $messageBus;
    }
    public function __invoke(newDelivery $newDelivery)
    {
        if ($newDelivery instanceof newDelivery) {

                $this->log->addLog('Delivery request received for product id ' . $newDelivery->getProductId() . ' from delivery queue', 'delivery',9,'Supplier');
                $faker = \Faker\Factory::create();
                $quantity = $faker->numberBetween(1, 5);
                sleep(2);
                $message = new receiveDelivery($newDelivery->getProductId(), $quantity);
                $envelope = new Envelope($message, [
                    new DelayStamp(10000)
                ]);
                $this->messageBus->dispatch($envelope);

                $this->log->addLog('Sending new delivery to delivery queue, quantity:' . $quantity, 'delivery',10,'Supplier');

        }

    }

}