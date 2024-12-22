<?php

namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Link>
 *
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    /**
     * Trouve les liens par mot-clé.
     *
     * @param string
     * @return Link[] 
     */
    public function findByKeywordName(string $keywordName): array
    {
        return $this->createQueryBuilder('l')
            ->join('l.keyword', 'k')
            ->andWhere('k.name = :keywordName')
            ->setParameter('keywordName', $keywordName)
            ->orderBy('l.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les liens par une partie du nom ou de la description.
     *
     * @param string 
     * @return Link[] 
     */
    public function searchByNameOrDescription(string $searchTerm): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.name LIKE :searchTerm OR l.description LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('l.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
