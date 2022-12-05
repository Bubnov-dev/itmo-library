<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save($book, bool $flush = true): void
    {
        $this->getEntityManager()->persist($book);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getUniques($id, $name, $year, $isbn)
    {
        $q =  $this->createQueryBuilder('p')->where('p.name = :name and p.year = 
        :year')->orWhere('p.name = :name and p.ISBN = :isbn')->setParameters([
            'name' => $name, 'year' => $year, 'isbn' => $isbn]);

        if($id){
            $q = $q->andWhere('p.id != :id')->setParameter('id', $id);
        }
        $q = $q->getQuery()->execute();
        return $q;
    }

    public function searchByName($name){
        return $this->createQueryBuilder('p')->where('p.name like :name')
            ->setParameters([
            'name' => '%'.$name.'%'])->getQuery()->execute();
    }

}
