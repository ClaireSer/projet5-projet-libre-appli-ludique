<?php

namespace GameBundle\Repository;

/**
 * QuestionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getQuestionsByValidity($validity) {
        return $this->createQueryBuilder('q')
            ->leftJoin('q.topic', 't')
            ->leftJoin('q.userCount', 'u')
            ->leftJoin('t.subject', 's')
            ->addSelect('t')
            ->addSelect('u')
            ->addSelect('s')
            ->where('q.isValid = :isValid')
            ->setParameter('isValid', $validity)
            ->getQuery()
            ->getArrayResult()
		;
    }

    public function getQuestionById($id) {
        return $this->createQueryBuilder('q')
            ->leftJoin('q.topic', 't')
            ->leftJoin('q.userCount', 'u')
            ->leftJoin('q.schoolClass', 's')
            ->addSelect('t')
            ->addSelect('u')
            ->addSelect('s')
            ->where('q.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult()
		;
    }

    public function getRandomQuestion($nbQuestions) {
        $idList = [];
        for($i = 1; $i <= $nbQuestions; $i++) {
            $idList[] = rand(28, 31);
        }

        $qb = $this->createQueryBuilder('q');
        return $qb
            ->where($qb->expr()->in('q.id', $idList))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function count() {
        $qb = $this->createQueryBuilder('q');
        return (int) $qb
            ->select($qb->expr()->count('q'))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

}
