<?php


namespace App\Command;


use App\Entity\Order;
use App\Message\newOrder;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\LogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class NewOrderCommand extends Command
{
    protected static $defaultName = 'app:neworder';

    private $orderRepository;

    private $entityManager;
    private $messageBus;
    private $productRepository;
    private $log;


    public function __construct(OrderRepository $orderRepository, LogService $log, EntityManagerInterface $entityManager, ProductRepository $productRepository, MessageBusInterface $messageBus)
    {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->messageBus = $messageBus;
        $this->log = $log;
        parent::__construct();

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $faker = \Faker\Factory::create();


        $product = $this->productRepository->find(($faker->numberBetween(1,5)));
        $order = new Order();
        $order->setStatus(1);
        $order->setQuantity($faker->numberBetween(1,3));
        $order->setStatus(1);
        $order->setProduct($product);
        $order->setCreatedAt(new \DateTime());
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        $this->log->delete();
        $this->log->addLog('New order '.$order->getId().' created','order',1,'ecommerce');

        $message = new newOrder($order->getId());
        $envelope = new Envelope($message, [
            new DelayStamp(1000)
        ]);
        $this->messageBus->dispatch($envelope);
        $this->log->addLog('Order '.$order->getId().' sent to order queue','order',2,'ecommerce');
        return Command::SUCCESS;

    }
}