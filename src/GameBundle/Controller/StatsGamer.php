<?php

namespace GameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class StatsGamer
{
    public function update($gamer, $finalScore) {
        $cumulScore = $gamer->getCumulScore();
        $cumulScore += $finalScore;
        $gamer->setCumulScore($cumulScore);

        $bestScore = $gamer->getBestScore();
        if ($finalScore > $bestScore) {
            $gamer->setBestScore($finalScore);
        }

        $gamePlayedNb = $gamer->getGamePlayedNb();
        $gamePlayedNb++;
        $gamer->setGamePlayedNb($gamePlayedNb);

        $level = $gamer->getLevel();
        if ($cumulScore > 1500 * ($level + 1)) {
            $level++;
            $gamer->setLevel($level);
        }
    }
}