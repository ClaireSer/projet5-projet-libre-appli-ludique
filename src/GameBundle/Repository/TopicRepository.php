<?php

namespace GameBundle\Repository;

/**
 * TopicRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TopicRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTopicFromSubject($pattern) {
        return $this
        ->createQueryBuilder('t')
        ->leftJoin('t.subject', 'topic')
        
        ;
    }
}
