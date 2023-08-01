<?php


function initSession()
{
    $player = $_SESSION["player"] ?? [];
    $adversaire = $_SESSION["adversaire"] ?? [];
    $combats = $_SESSION["combats"] ?? [];
    $soigner = $_SESSION["soigner"] ?? [];
    return [$player, $adversaire, $combats, $soigner];
}

session_start();
$player = $_SESSION["player"] ?? [];
$adversaire = $_SESSION["adversaire"] ?? [];
$combats = $_SESSION["combats"] ?? [];
$soigner = $_SESSION["soigner"] ?? [];
// Call the function to initialize the session variables
[$player, $adversaire, $combats, $soigner] = initSession();

function checkErrorsForm(): array
{
    $formErrors = [];
    $player = $_POST['player'];
    $adversaire = $_POST['adversaire'];
    $player["name"] = trim($player["name"]);
    $player["sante"] = intval($player["sante"]);
    $player["mana"] = intval($player["mana"]);
    $player["attaque"] = intval($player["attaque"]);
    $adversaire["name"] = trim($adversaire["name"]);
    $adversaire["sante"] = intval($adversaire["sante"]);
    $adversaire["mana"] = intval($adversaire["mana"]);
    $adversaire["attaque"] = intval($adversaire["attaque"]);

    $format = '%s %s doit être superieur à %d.';
    if ($player["attaque"] <= 0) {
        $formErrors['player']['attaque'] = sprintf($format, "L'attaque", "du joueur", 0);
    }
    if ($player["mana"] <= 0) {
        $formErrors['player']["mana"] = sprintf($format, "Le mana", "du joueur", 0);
    }
    if ($player["sante"] <= 0) {
        $formErrors['player']["sante"] = sprintf($format, "La santé", "du joueur", 0);
    }

    if ($adversaire["attaque"] <= 0) {
        $formErrors['adversaire']["attaque"] = sprintf($format, "L'attaque", "de l'adversaire", 0);
    }
    if ($adversaire["mana"] <= 0) {
        $formErrors['adversaire']["mana"] = sprintf($format, "Le mana", "de l'adversaire", 0);
    }
    if ($adversaire["sante"] <= 0) {
        $formErrors['adversaire']["sante"] = sprintf($format, "La santé", "de l'adversaire", 0);
    }

    return [$formErrors, $player, $adversaire];
}



function attack(array $player, array $adversaire, array $combats):void
{

    if ($player && $adversaire) {
        // list($player, $adversaire, $combat) = getInfoInSession();
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
        $_SESSION["player"] =  $player;
        $_SESSION["adversaire"] = $adversaire;
        $_SESSION["combats"] = $combats;

        // getInfoInSession($player, $adversaire, $combat);  
    }
}

function soigner(array $player)
{
    $player["manaPoints"] -= 20;
    $player["santePoints"] += 100;
    $soigner[] = $player["name"] . " s'est soigné de 100 points <br>";

    $_SESSION['player'] = $player;
    $_SESSION["soigner"] = $soigner;

    // list($player, $soigner) = getInfoInSession();
}

function resultat(array $player, array $adversaire,)
{
    //  list($player, $adversaire) = getInfoInSession();
    $player = $_SESSION["player"] ?? [];
    $adversaire = $_SESSION["adversaire"] ?? [];

    if ($player["santePoints"] <= 0) {
        echo "$adversaire[name] à gagné la partie";
    }
    elseif ($adversaire["santePoints"] <= 0) {
        echo "$player[name] à gagné la partie";
    }

    return true;
}

?>
