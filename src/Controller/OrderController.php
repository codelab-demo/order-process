<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Order;
use App\Message\newOrder;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\LogService;

class OrderController extends AbstractController
{
    /**
     * @Route("/neworder", name="neworder")
     */
    public function newOrder(ProductRepository $productRepository, EntityManagerInterface $entityManager, LogService $log, MessageBusInterface $messageBus)
    {
        $faker = \Faker\Factory::create();


        $product = $productRepository->find(($faker->numberBetween(1,5)));
        $order = new Order();
        $order->setStatus(1);
        $order->setQuantity($faker->numberBetween(1,3));
        $order->setStatus(1);
        $order->setProduct($product);
        $order->setCreatedAt(new \DateTime());
        $entityManager->persist($order);
        $entityManager->flush();
        $log->delete();
        $log->addLog('New order '.$order->getId().' created','order',1,'ecommerce');

        $message = new newOrder($order->getId());
        $envelope = new Envelope($message, [
            new DelayStamp(1000)
        ]);
        $messageBus->dispatch($envelope);
        $log->addLog('Order '.$order->getId().' sent to order queue','order',2,'ecommerce');
        return new JsonResponse(['result' => 'ok']);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(EntityManagerInterface $entityManager) {


        return $this->render('index.html.twig', [
            'order' => $entityManager->getRepository(Log::class)->findBy([],['createdAt'=>'desc','id'=>'desc'])
        ]);
    }

    /**
     * @Route("/log", name="log")
     */
    public function getlog(EntityManagerInterface $entityManager, Request $request) {
        $lastId = $request->getContent();
        $lastId = json_decode($lastId, true)['lastid'];


        if($lastId)
            $log = $entityManager->getRepository(Log::class)->getNewLogs($lastId);
        else {
            $log = $entityManager->getRepository(Log::class)->findBy([], ['createdAt' => 'asc', 'id' => 'asc']);

        }
        $results = [];
        foreach($log as $pos) {
            $tmpRes = [];
            $tmpRes['id'] = $pos->getId();
            $tmpRes['createdAt'] = $pos->getCreatedAt();
            $tmpRes['info'] = $pos->getInfo();
            $tmpRes['step'] = $pos->getStep();
            $results[] = $tmpRes;
        }


        return new JsonResponse($results);
    }
}
