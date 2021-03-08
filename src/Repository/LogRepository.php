<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function deleteLogs() {
        $this->createQueryBuilder('log')
            ->delete()
            ->getQuery()
            ->execute();
    }

    public function getNewLogs($lastId)
    {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('e')
            ->from('App:Log', 'e')
            ->where('e.id > :lastId')
            ->setParameter('lastId', $lastId);

        $entities = $qb->getQuery()->getResult();

        return $entities;
    }


}
