<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @return Message[] Returns an array of Message objects
     */
    public function findByEmailField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.email = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findOneMessageById($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
