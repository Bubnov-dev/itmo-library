<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(Author $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Author $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * get entities,by condition, that should be unique
     * @param $name
     * @param $surname
     * @param $patronymic
     * @return mixed
     */
    public function getUniques($id, $name, $surname, $patronymic)
    {
        $q =  $this->createQueryBuilder('p')->where('p.name = :name and p.surname = 
        :surname and p.patronymic = :patronymic');


        if($id){
            $q = $q->andWhere('p.id != :id')->setParameter('id', $id);
        }

        $q = $q->setParameters([
            'name' => $name, 'surname' => $surname, 'patronymic' => $patronymic])
            ->getQuery()
            ->execute();
    }
}
