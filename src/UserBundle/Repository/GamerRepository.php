<?php

namespace UserBundle\Repository;

/**
 * GamerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GamerRepository extends \Doctrine\ORM\EntityRepository
{
    public function getGamersByUserCount($userCount) {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.schoolClass', 's')
            ->addSelect('s')
            ->where('g.userCount = :userCount')
            ->setParameter('userCount', $userCount)
            ->getQuery()
            ->getResult()
		;
    }

    public function getGamers() {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.userCount', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult()
		;
    }

    public function getGamersBySchoolClass($id) {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.userCount', 'u')
            ->leftJoin('g.schoolClass', 's')
            ->addSelect('u')
            ->addSelect('s')
            ->where('s.id = :id')
            ->andWhere('g.role = :role')
            ->setParameter('id', $id)
            ->setParameter('role', 'Élève')
            ->getQuery()
            ->getResult()
		;
    }

    public function getOtherGamers() {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.userCount', 'u')
            ->addSelect('u')
            ->where('g.role != :role')
            ->setParameter('role', 'Élève')
            ->getQuery()
            ->getResult()
		;
    }

    public function findGamerInArray($id) {
        return $this->createQueryBuilder('g')
            ->where('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult()
		;       
    }
}
