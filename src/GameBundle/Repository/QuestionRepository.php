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

}
