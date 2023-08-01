<?php

function attack(array $player, array $adversaire, array $combats)
{

    if ($player && $adversaire) {
        $playerAttackPoints = $player['attaquePoints'];
        $adversaireAttackPoints = $adversaire['attaquePoints'];

        if ($playerAttackPoints > $adversaireAttackPoints) {
            $adversaire['santePoints'] -= 50;
            //  return $adversaire['santePoints'];
            $combats[] = $adversaire['name'] . " a perdu 50 points de santé" . "<br>";
        } else if ($playerAttackPoints < $adversaireAttackPoints) {
            $player['santePoints'] -= 50;
            // return $player['santePoints'];
            $combats[] = $player['name'] . " a perdu 50 points de santé" . "<br>";
        } else {
            $combats[] = "L'attaque est un match nul" . "<br>";
        }
        $_SESSION['adversaire'] = $adversaire;
        $_SESSION['player'] = $player;
        $_SESSION['combats'] = $combats;
    }
}

function soigner(array $player)
{
    $player["manaPoints"] -= 20;
    $player["santePoints"] += 100;
    $soigner[] = $player["name"] . " s'est soigné de 100 points <br>";

    $_SESSION['player'] = $player;
    $_SESSION["soigner"] = $soigner;
}

?>
