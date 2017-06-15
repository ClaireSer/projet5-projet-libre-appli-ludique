<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class StatsQuestion extends Controller
{
    public function getInfo($em) {
        $subjects = $em->getRepository('GameBundle:Subject')->findAll();
        $subjectIds = [];
        foreach ($subjects as $subject) {
            $subjectIds[] = $subject->getId();
        }
        
        $allSchoolClass = $em->getRepository('GameBundle:SchoolClass')->findAll();
        $schoolClassIds = [];
        foreach ($allSchoolClass as $schoolClass) {
            $schoolClassIds[] = $schoolClass->getId();
        }

        $nbSchoolClassBySubject = [];
        $schoolClassBySubject = [];
        $nbQuestions = [];
        foreach ($subjectIds as $key=>$id0) {
            // count number of schoolLevels by subject
            $nbSchoolClassBySubject[] = $em->getRepository('GameBundle:SchoolClass')->countBySubject($id0);
            // get schoolClass by subject
            $schoolClassBySubject[] = $em->getRepository('GameBundle:SchoolClass')->getBySubject($id0);
            // count number of questions by schoolLevel and by subject
            foreach ($schoolClassIds as $id1) {
                $nbQuestions[$key][] = $em->getRepository('GameBundle:Question')->count($id0, $id1);
            }
        }
        return 2;
    }
}
