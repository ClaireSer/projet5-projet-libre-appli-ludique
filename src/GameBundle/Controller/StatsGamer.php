<?php

namespace GameBundle\Controller;



class StatsGamer
{
    public function update(Gamer $gamer, $finalScore) {
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
