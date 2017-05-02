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

    public function getQuestionBySubject($subject) {
        return $this->createQueryBuilder('q')
            ->leftJoin('q.topic', 't')
            ->leftJoin('t.subject', 's')
            ->addSelect('t')
            ->addSelect('s')
            ->where('t.subject = :subject')
            ->setParameter('subject', $subject)
            ->getQuery()
            ->getResult()
		;
    }

    public function getRandomQuestionBySubject($subject, $idQuestionList) {
        $randomId = array_rand($idQuestionList, 1);
        
        $qb = $this->createQueryBuilder('q');
        return $qb
            ->leftJoin('q.topic', 't')
            ->leftJoin('t.subject', 'su')
            ->leftJoin('q.answers', 'a')
            ->leftJoin('q.schoolClass', 'sc')
            ->addSelect('t')
            ->addSelect('su')
            ->addSelect('a')
            ->addSelect('sc')
            ->where('t.subject = :subject')
            ->andWhere('q.id = :randomId')
            ->setParameter('subject', $subject)
            ->setParameter('randomId', $idQuestionList[$randomId])
            ->getQuery()
            ->getArrayResult()[0]
        ;
    }

}
