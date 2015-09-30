#!/usr/bin/env php
<?php

include('game.php');

print "Player A, enter your secret (4 Digits separated with SPACE):\n";
$secret = trim(getSecret(true));
$game = new Game();
$game->setPlayerNumbers(new GameNumberSet(explode(' ', $secret)));

while (true) {

    try{
        $hits = $game->getHits(new GameNumberSet(explode(' ',trim(readline("\nPlayer B move:\n")))));
        print implode(' ', $hits);
        if($hits == [4,0]){
            print "\n\nPlayer B made it within {$game->getTries()} tries. Congratulations.\n\n";
            break;
        }
    }
    catch(Exception $e){
        print $e->getMessage()."\n";
    }

}


