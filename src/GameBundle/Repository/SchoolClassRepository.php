<?php

namespace GameBundle\Repository;


/**
* SchoolClassRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SchoolClassRepository extends \Doctrine\ORM\EntityRepository
{
	public function orderBy() {
		return $this->createQueryBuilder('s')
		->orderBy('s.id', 'ASC')
		;
	}
}
