<?php


function getInfoInSession()
{
    $player = $_SESSION["player"] ?? [];
    $adversaire = $_SESSION["adversaire"] ?? [];
    $combats = $_SESSION["combats"] ?? [];
    $soigner = $_SESSION["soigner"] ?? [];
    return [$player, $adversaire, $combats, $soigner];
}

function attack(array $player, array $adversaire, array $combats):void
{

    if ($player && $adversaire) {
        list($player, $adversaire, $combat) = getInfoInSession();
        $playerAttackPoints = $player['attaquePoints'];
        $adversaireAttackPoints = $adversaire['attaquePoints'];

        if ($playerAttackPoints > $adversaireAttackPoints) {
            $adversaire['santePoints'] -= 50;
            $combats[] = $adversaire['name'] . " a perdu 50 points de santé" . "<br>";
        } else if ($playerAttackPoints < $adversaireAttackPoints) {
            $player['santePoints'] -= 50;
            $combats[] = $player['name'] . " a perdu 50 points de santé" . "<br>";
        } else {
            $combats[] = "L'attaque est un match nul" . "<br>";
        }
        getInfoInSession($player, $adversaire, $combat);  
    }
}

function soigner(array $player)
{
    $player["manaPoints"] -= 20;
    $player["santePoints"] += 100;
    $soigner[] = $player["name"] . " s'est soigné de 100 points <br>";

    $_SESSION['player'] = $player;
    $_SESSION["soigner"] = $soigner;

    list($player, $soigner) = getInfoInSession();
}

// function resultat(array $player, array $adversaire,)
// {
//      list($player, $adversaire) = getInfoInSession();
//     if $player["santePoints"] <= 0 {
//         echo "$adversaire[name] à gagné la partie";
//     }
//     elseif $adversaire["santePoints"] <= 0 {
//         echo "$player[name] à gagné la partie";
//     }
   
//     getInfoInSession($player, $adversaire);
// }

?>
