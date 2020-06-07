<?php

namespace GameBundle\Controller;

use Doctrine\ORM\EntityManager;


class StatsQuestion
{
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function getInfo() {
        $subjects = $this->em->getRepository('GameBundle:Subject')->findAll();
        $subjectIds = [];
        foreach ($subjects as $subject) {
            $subjectIds[] = $subject->getId();
        }
        
        $allSchoolClass = $this->em->getRepository('GameBundle:SchoolClass')->findAll();
        $schoolClassIds = [];
        foreach ($allSchoolClass as $schoolClass) {
            $schoolClassIds[] = $schoolClass->getId();
        }

        $nbSchoolClassBySubject = [];
        $schoolClassBySubject = [];
        $nbQuestions = [];
        foreach ($subjectIds as $key=>$id0) {
            // count number of schoolLevels by subject
            $nbSchoolClassBySubject[] = $this->em->getRepository('GameBundle:SchoolClass')->countBySubject($id0);
            // get schoolClass by subject
            $schoolClassBySubject[] = $this->em->getRepository('GameBundle:SchoolClass')->getBySubject($id0);
            // count number of questions by schoolLevel and by subject
            foreach ($schoolClassIds as $id1) {
                $nbQuestions[$key][] = $this->em->getRepository('GameBundle:Question')->countBySubjectAndLevel($id0, $id1);
            }
        }
        return array(
            'subjects'                  => $subjects, 
            'nbSchoolClassBySubject'    => $nbSchoolClassBySubject, 
            'schoolClassBySubject'      => $schoolClassBySubject, 
            'nbQuestions'               => $nbQuestions
        );
    }
}
