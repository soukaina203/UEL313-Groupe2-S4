<?php

namespace App\Repository;

use App\Entity\Keyword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Keyword>
 *
 * @method Keyword|null find($id, $lockMode = null, $lockVersion = null)
 * @method Keyword|null findOneBy(array $criteria, array $orderBy = null)
 * @method Keyword[]    findAll()
 * @method Keyword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeywordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Keyword::class);
    }

    /**
     * Trouve un mot-clé par son nom.
     *
     * @param string 
     * @return Keyword|null
     */
    public function findOneByName(string $name): ?Keyword
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère tous les mots-clés associés à des liens, triés par ordre alphabétique.
     *
     * @return Keyword[] 
     */
    public function findAllSortedByName(): array
    {
        return $this->createQueryBuilder('k')
            ->orderBy('k.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère tous les mots-clés et leurs liens associés.
     *
     * @return Keyword[] 
     */
    public function findAllWithLinks(): array
    {
        return $this->createQueryBuilder('k')
            ->leftJoin('k.links', 'l')
            ->addSelect('l')
            ->orderBy('k.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
