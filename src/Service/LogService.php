<?php


namespace App\Service;
use \App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;

class LogService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }
    public function addLog($text,$queue,$step,$dept)
    {
        $log = new Log();
        $log->setInfo($text);
        $log->setQueue($queue);
        $log->setDept($dept);
        $log->setStep($step);
        $log->setCreatedAt(new \DateTime());
        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function delete() {
        $this->entityManager->getRepository(Log::class)->deleteLogs();
    }

}