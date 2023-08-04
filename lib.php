<?php

function getInfoInSession()
{
    $player = $_SESSION["player"] ?? [];
    $adversaire = $_SESSION["adversaire"] ?? [];
    $combats = $_SESSION["combats"] ?? [];
    $soigner = $_SESSION["soigner"] ?? [];
    return [$player, $adversaire, $combats, $soigner];
}

// function setInfoInSession(?array $player, ?array $adversaire, ?array $combats, ?array $soigner): void
// {
//     $_SESSION["player"] = $player;
//     $_SESSION["adversaire"] = $adversaire;
//     $_SESSION["combats"] = $combats;
//     $_SESSION["soigner"] = $soigner;
// }

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



function attack(array $player, array $adversaire, array $combats): void
{
    if ($player && $adversaire) {
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

function resultat(): ?string
{
     list($player, $adversaire) = getInfoInSession();

    if ($player && $player["santePoints"] <= 0) {
        return "$adversaire[name] à gagné la partie";
    } elseif ($adversaire && $adversaire["santePoints"] <= 0) {
        return "$player[name] à gagné la partie";
    }
    return null;
}


function insertPlayer(array $player): int
{
    $mysql_host = "localhost";
    $mysql_database = "game";
    $mysql_user = "root";
    $mysql_password = "";
    $dbh = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);


    // Prepare the SQL statement
    $sql = ("INSERT INTO players (Name, Attack, Health, Mana) VALUES (:name, :attaquePoints, :santePoints, :manaPoints)");
    $stmt = $dbh->prepare($sql);

    // Bind the data to the parameters
    $stmt->bindParam(':name', $player['name']);
    $stmt->bindParam(':attaquePoints', $player['attaquePoints']);
    $stmt->bindParam(':santePoints', $player['santePoints']);
    $stmt->bindParam(':manaPoints', $player['manaPoints']);

    // Execute the statement
    $stmt->execute();

    // Close the connection
    // $conn = null;


    $id =$dbh->lastInsertId();
    return $id;
    dump($id);
}

function insertAdversaire(array $adversaire)
{
    $mysql_host = "localhost";
    $mysql_database = "game";
    $mysql_user = "root";
    $mysql_password = "";
    $dbh = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);


    // Prepare the SQL statement
    $sql = ("INSERT INTO players (Name, Attack, Health, Mana) VALUES (:name, :attaquePoints, :santePoints, :manaPoints)");
    $stmt = $dbh->prepare($sql);

    // Bind the data to the parameters
    $stmt->bindParam(':name', $adversaire['name']);
    $stmt->bindParam(':attaquePoints', $adversaire['attaquePoints']);
    $stmt->bindParam(':santePoints', $adversaire['santePoints']);
    $stmt->bindParam(':manaPoints', $adversaire['manaPoints']);

    // Execute the statement
    $stmt->execute();

    // Close the connection
    // $conn = null;


    // $id_adversaire = $dbh->lastInsertId();
    
}

function insertWinner(array $winner): int
{
    $mysql_host = "localhost";
    $mysql_database = "game";
    $mysql_user = "root";
    $mysql_password = "";
    $dbh = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);


    // Prepare the SQL statement
    $dbh->query("SELECT player_ID FROM players");
    $sql = ("INSERT INTO fight () VALUES ()");
    $stmt = $dbh->prepare($sql);

    // Bind the data to the parameters
    $stmt->bindParam(':winner', $winner['name']);

    // Execute the statement
    $stmt->execute();

    // Close the connection
    // $conn = null;


    $id_winner = $dbh->lastInsertId();
    return $id_winner;
}






  
