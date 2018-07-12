<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param Trick $trick
     * @return mixed
     */
    public function getCommentPaginate(Trick $trick)
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.trick = :trick')
            ->setParameter('trick', $trick)
            ->orderBy('c.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(2)
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @param Trick $trick
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNbTotalCommentsForTrick(Trick $trick)
    {
        $nbTotal = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.trick = :trick')
            ->setParameter('trick', $trick)
            ->getQuery()
            ->getSingleScalarResult();

        return $nbTotal;
    }
}
