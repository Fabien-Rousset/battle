<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require "lib.php";

$player = $_SESSION["player"] ?? [];
$adversaire = $_SESSION["adversaire"] ?? [];
$combats = $_SESSION["combats"] ?? [];
$soigner = $_SESSION["soigner"] ?? [];

// list($player, $adversaire, $combat, $soigner) = getInfoInSession();


// si je poste le formulaire du debut
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fight'])) {
    $player = array('name' => '', 'attaquePoints' => 0, 'santePoints' => 0, "manaPoints" => 0);
    $adversaire = array('name' => '', 'attaquePoints' => 0, 'santePoints' => 0, "manaPoints" => 0);

    $player = [
        'name' => trim($_POST['player']["name"]),
        'attaquePoints' => intval($_POST['player']['attaque']),
        'santePoints' => intval($_POST['player']['sante']),
        "manaPoints" => intval($_POST['player']["mana"]),
    ];

    $adversaire = [
        'name' => trim($_POST['adversaire']["name"]),
        'attaquePoints' => intval($_POST['adversaire']['attaque']),
        'santePoints' => intval($_POST['adversaire']['sante']),
        "manaPoints" => intval($_POST['adversaire']["mana"]),
    ];

    $_SESSION["player"] = $player;
    $_SESSION["adversaire"] = $adversaire;
}



// si je clique sur attaquer
if (isset($_POST["attaque"])) {
    attack($player, $adversaire, $combats);
}

//si je clique sur "Stopper le combat" ou nouveau combat
if (isset($_POST["restart"])) {
    session_destroy();
}

//si je clique sur "Se soigner"
if (isset($_POST["soin"])) {
    soigner($player);
}

//si je clique sur "Stopper le combat" et nouveau combat
if (isset($_POST["restart"])) {
    session_destroy();
}


dump($GLOBALS, $combats, $adversaire);

sess

// list($player, $adversaire, $combat, $soigner) = getInfoInSession();
?>

<html lang="fr">

<head>
    <title>Battle</title>
    <link rel="stylesheet" href="public/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

</head>

<body>
    <div class="container">
        <audio id="fight-song" src="fight.mp3"></audio>
        <audio id="hadoudken-song" src="Haduken.mp3"></audio>
        <audio id="fatality-song" src="fatality.mp3"></audio>
        <h1 class="animate__animated animate__rubberBand">Battle</h1>


        <?php if (empty($adversaire) || empty($player)) { ?>
            <div id="prematch">
                <form id='formFight' action="<?php $_SERVER["PHP_SELF"]; ?>" method="post">
                    <div>
                        Joueur <br>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Name</label>
                                <input required type="text" class="form-control" name="player[name]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Attaque</label>
                                <input required type="number" class="form-control" value="100" name="player[attaque]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Mana</label>
                                <input required type="number" class="form-control" value="100" name="player[mana]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Santé</label>
                                <input required type="number" class="form-control" value="100" name="player[sante]">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        Adversaire <br>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Name</label>
                                <input required type="text" class="form-control" name="adversaire[name]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Attaque</label>
                                <input required type="number" class="form-control" value="100" name="adversaire[attaque]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Mana</label>
                                <input required type="number" class="form-control" value="100" name="adversaire[mana]">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Santé</label>
                                <input required type="number" class="form-control" value="100" name="adversaire[sante]">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="d-flex justify-content-center">
                            <input name="fight" type="submit" value="FIGHT">
                        </div>
                    </div>
                </form>
            </div>


        <?php } else { ?>
            <div id="match" class="row gx-5">
                <h2>Match</h2>
                <div class="col-6 ">
                    <div class="position-relative float-end">
                        <img id="player" src="https://api.dicebear.com/6.x/lorelei/svg?flip=false&seed=test" alt="Avatar" class="avatar float-end">
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo  $player["santePoints"]; ?>
                        </span>
                        <ul>
                            <li>Name : <?php echo $player["name"]; ?></li>
                            <li>Attaque :<?php echo $player["attaquePoints"]; ?> </li>
                            <li>Mana : <?php echo $player["manaPoints"]; ?></li>
                        </ul>
                    </div>
                </div>
                <div class="col-6" id="adversaire">
                    <div class="position-relative float-start">
                        <img src="https://api.dicebear.com/6.x/lorelei/svg?flip=true&seed=test2" alt="Avatar" class="avatar">
                        <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                            <?php echo $adversaire["santePoints"]; ?>
                        </span>
                        <ul>
                            <li>Name : <?php echo $adversaire["name"]; ?></li>
                            <li>Attaque :<?php echo $adversaire["attaquePoints"]; ?> </li>
                            <li>Mana : <?php echo $adversaire["manaPoints"]; ?></li>
                        </ul>
                    </div>
                </div>
                <div id="combats">
                    <h2>Combat</h2>
                    <ul>
                        <?php foreach ($combats as $combat) { ?>
                            <li>
                                <i class="fa-solid fa-khanda p-1"></i><?php echo $combat; ?>
                            </li>
                        <?php } ?>
                        <?php foreach ($soigner as $soin) { ?>
                            <li>
                                <i class="fa-solid fa-khanda p-1"></i><?php echo  $soin; ?>
                            </li>
                        <?php } ?>
                    </ul>
                    <form id='actionForm' action="index.php" method="post">
                        <div class="d-flex justify-content-center">
                            <input id="attaque" name="attaque" type="submit" value="Attaquer">
                            <input id="soin" name="soin" type="submit" value="Se soigner">
                        </div>
                        <div class="d-flex justify-content-center">
                            <input id="restart" name="restart" type="submit" value="Stopper le combat">
                        </div>
                    </form>
                </div>

            <?php

        } ?>
             <?php
            
            if ($resultat = true) { ?>
                <div id="Resultats">
                    <h1>Résultat</h1>
                    <?php echo resultat($player, $adversaire);  ?>
                    <form class="d-flex justify-content-center" action="" method="post">
                        <input id="restart" name="restart" type="submit" value="Nouveau combat">
                    </form>
                </div>
            <?php
            }
            ?>
            </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let submitFight = document.querySelector("#fight");
            if (submitFight) {
                submitFight.addEventListener("click", function(event) {
                    event.preventDefault();
                    submitFight.classList.add("animate__animated");
                    submitFight.classList.add("animate__rubberBand");
                    setTimeout(function() {
                        submitFight.classList.remove("animate__rubberBand");
                    }, 1000);
                    let fight_song = document.getElementById("fight-song");
                    fight_song.play();
                    setTimeout(function() {
                        document.forms["formFight"].submit();
                    }, 500);
                })
            }

            let submitAttaque = document.querySelector("#attaque");
            let alreadyPlaySong = false;
            if (submitAttaque) {
                submitAttaque.addEventListener("click", function(event) {
                    if (alreadyPlaySong)
                        return true;
                    event.preventDefault();
                    let player = document.querySelector("#player")
                    player.classList.add("animate__animated");
                    player.classList.add("animate__rubberBand");
                    submitAttaque.classList.add("animate__animated");
                    submitAttaque.classList.add("animate__rubberBand");
                    setTimeout(function() {
                        submitAttaque.classList.remove("animate__rubberBand");
                        player.classList.remove("animate__rubberBand");
                    }, 1000);
                    let hadouken_song = document.getElementById("hadoudken-song");
                    hadouken_song.play();
                    alreadyPlaySong = true;
                    setTimeout(function() {
                        submitAttaque.click();
                    }, 1000);
                })
            }

            let submitRestart = document.querySelector("#restart");
            let alreadyPlaySongRestart = false;
            if (submitRestart) {
                submitRestart.addEventListener("click", function(event) {
                    if (alreadyPlaySongRestart)
                        return true;
                    event.preventDefault();
                    let fatality_song = document.getElementById("fatality-song");
                    fatality_song.play();
                    alreadyPlaySongRestart = true;
                    setTimeout(function() {
                        submitRestart.click();
                    }, 2000);
                })
            }
        });
    </script>
</body>
<style>
    .avatar {
        vertical-align: middle;
        width: 100px;
        border-radius: 50%;
    }
</style>

</html>