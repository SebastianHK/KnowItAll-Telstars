<?php
session_start();
require "classes/weetjeZender.php";
$huidigPage = 0;
if (isset($_GET["pagina"])) {
    $huidigPage = $_GET["pagina"];
    if ($huidigPage < 0) {
        $huidigPage = 0;
    }
}
include 'functies.php';
$sqs = zoeken("weetjesCat");

// sessions voor de zoekbalk
if (isset($_SESSION["pSorteer"])) {
    $pSorteer = $_SESSION["pSorteer"];
} else {
    $pSorteer = "";
}if (isset($_SESSION["pAscDesc"])) {
    $pAscDesc = $_SESSION["pAscDesc"];
} else {
    $pAscDesc = "";
}
if (isset($_SESSION['rank'])) {
    $rank = $_SESSION['rank'];
} else {
    $rank = "gast";
}

if (isset($_SESSION['gebruikersnaam'])) {
    $gebruikersnaam = $_SESSION['gebruikersnaam'];
} else {
    $gebruikersnaam = "gast";
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['gebruikersnaam']);
    header("location: login.php");
}

require 'connectie.php';
if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <script src="mainScript.js"></script>

    <meta http-equiv="Content-Type"content="text/html;charset=UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>KnowItAll</title>
    <link rel="stylesheet" href="mainStyles.css">
    <link rel="icon" href="images/alleen_doos_logo.png" type="image/icon type">

</head>
<div id="xboxAchtergrond" class="xboxStyle achtergrond" style="opacity: 0;"></div>
<div id="nintendoAchtergrond" class="nStyle achtergrond" style="opacity: 0;"></div>
<div id="psAchtergrond" class="psStyle achtergrond" style="opacity: 0;"></div>

<body>

<header>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Top Navigation Menu -->
    <div class="topnav">
        <a href="#home" class="active">ddd</a>
        <!-- Navigation links (hidden by default) -->
        <div id="myLinks">
            <a href="../index.php">TheKnowItAll</a>
            <?php  if ($gebruikersnaam == 'gast') : ?>
                <a href="login.php">Login/Registreer</a>
            <?php endif ?>
            <?php  if ($gebruikersnaam !== 'gast') : ?>
                <a href="index.php" >Profiel</a>
            <?php endif ?>
            <?php  if ($rank == 'admin') : ?>
                <a href="admin_control_panel.php" id="adminCPK">Admin Control Panel</a>
            <?php endif ?>
            <?php  if ($gebruikersnaam !== 'gast') : ?>
                <a href="index.php?logout='1'">Uitloggen</a>
            <?php endif ?>
        </div>
        <!-- "Hamburger menu" / "Bar icon" to toggle the navigation links -->
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
    </div>

    <div id="styleSwitch">
        <p id="switchText"></p>
        <label id="styleSlider" class="switch">
            <input id="sliderCheck" type="checkbox" onclick="styleSlider()">
            <span id="slider" class="slider norm round nintendo"></span>
        </label>
    </div>
    <a class="titel navKnop" href="../index.php">TheKnowItAll</a>
    <div class="navKnoppen">
        <a href="" class="navKnop headerNavKnop">Weetjes catalogus</a>
        <?php  if ($gebruikersnaam !== 'gast') : ?>
            <a href="index.php?logout='1'" class="navKnop logKnop">Uitloggen</a>
            <a href="index.php" class="navKnop headerNavKnop">Profiel</a>
            <a class="navKnop headerNavKnop" onclick="document.getElementById('weetjeStuurder').style.display = 'block'">Weetje toevoegen</a>
        <?php endif ?>
        <?php  if ($gebruikersnaam == 'gast') : ?>
            <a href="index.php" class="navKnop logKnop">Login/Registreer</a>
        <?php endif ?>
        <?php  if ($rank == 'admin') : ?>
            <a href="admin_control_panel.php" class="navKnop headerNavKnop" id="adminCPK">Admin control panel</a>
        <?php endif ?>

    </div>

</header>

<main id="altMain">
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="error success">
            <h3>
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </h3>
        </div>
    <?php endif ?>

    <p class="titelText">Welkom bij de weetjes catalogus, <strong class="titelText"><?php echo $gebruikersnaam; ?></strong></p>

    <form style="display: none;" method="POST" id="weetjeStuurder" action="index.php" enctype="multipart/form-data">
        <div onclick="document.getElementById('weetjeStuurder').style.display = 'none'" id="wegKnopWeetjeStuurder">x</div>
        <input type="text" required name="titel" id="titel" placeholder="Titel" maxlength="50"></input><br>
        <textarea required name="weetje" id="weetje" placeholder="Weetje" maxlength="400"></textarea><br>
        <p>Datum van gebeurtenis</p>
        <input name="datum" type="date">
        <div id="fileInputContainer">
            <input hidden id="fileInput" name="plaatje" type="file" name="image" /><br>
            <label id="fileInputLabel" for="fileInput">Bladeren...</label>
            <span id="file-chosen">Geen bestand gekozen</span>
        </div>
        <input class="submitKnop" type="submit" name="submit" value="VERSTUUR">
    </form>
    <div id="restContainer">
        <form id="zoekCentrum" action="" method="post">
            <label for="sorteer">Sorteer</label>
            <label></label>
            <label for="gebDatum">Datum gebeurtenis</label>


            <select id="sorteerInput" class="zoekInput" name="sorteer">
                <option id="plaats_datum" value="plaats_datum">Datum Geplaatst</option>
                <option id="geb_datum" value="geb_datum">Ingevoerde Datum</option>
                <option id="gebruikersnaam" value="gebruikersnaam">Gebruikersnaam</option>
            </select>

            <select id="ascDescInput" class="ascDesc zoekInput" name="ascDesc">
                <option id="ASC" value="ASC">Oplopend</option>
                <option id="DESC" value="DESC">Aflopend</option>
            </select>
            <input class="zoekInput" type="date" name="gebDatum" id="gebDatum">

            <input type="reset" value="Reset">
            <input type="submit" value="Zoek" name="zoek" class="zoekInput">
        </form>
        <script>
            <?php
            if($pSorteer!=''){
                echo 'document.getElementById("sorteerInput").selectedIndex = document.getElementById("sorteerInput").options.namedItem("' . $pSorteer . '").index;';
            } if($pAscDesc!=''){
                echo 'document.getElementById("ascDescInput").selectedIndex = document.getElementById("ascDescInput").options.namedItem("' . $pAscDesc . '").index;';
            }

            ?>
        </script>
        <div style="height: 100%;" class="weetjeDiv weetjeInfo">
            <p class="tooltip">Titel
                <span class="tooltiptext">Titel van het weetje.</span>
            </p> -
            <p class="tooltip">Datum geplaatst
                <span class="tooltiptext">Datum dat het weetje geplaatst is.</span>
            </p> -
            <p class="tooltip">Datum gebeurtenis
                <span class="tooltiptext">De datum die de gebruiker heeft ingevoerd van wanneer het weetje gebeurt is.</span>
            </p> -
            <p class="tooltip">Status
                <span class="tooltiptext">Satus van het weetje.</span>
            </p>
        </div>
        <?php

        $gebruiker = $gebruikersnaam;
        if(isset($_POST["submit"])) {
            $image = $_FILES['plaatje']['name'];
            if (new zendWeetje(htmlspecialchars($_POST["titel"]),htmlspecialchars($_POST["weetje"]),htmlspecialchars($_POST["datum"]),$image,$gebruiker)) {
                $target = "images/images_user/".basename($image);
                move_uploaded_file($image['plaatje']['tmp_name'], $target);
                echo "kkkkkkkkkkkkkkkkkkkkkkkkkk";
            } else {
                echo "grfgdfgsdfgdfsshdgfdfghdhgf";
            }

            //stuur();
        }
        $huidigPage = 0;



        $result = $conn->query($sqs);
        $numRows = $conn->query("SELECT COUNT(id) FROM weetjesdb WHERE status='goedgekeurd'");

        $numRows = $numRows->fetch_assoc();
        $numRows = $numRows['COUNT(id)'];

        $weetjesArr = Array();

        if ($result->num_rows > 0) {
            $i=0;
            while($row = $result->fetch_assoc()) {
                $ID = $row['id'];
                $gebruikersnaam = $row['gebruiker'];
                $titel = $row['titel'];
                array_push($weetjesArr,'weetje.'.$ID);
                if ($row['geb_datum'] == "0000-00-00") {
                    $geb_datum = "NVT";
                } else {
                    $geb_datum = date('d-m-Y',strtotime($row['geb_datum']));
                }
                $weetjesArr['weetje.'.$ID][] = $row['weetjes'];

                echo '<div id=weetjeDiv'.$i.' class="weetjeDiv">
                        <div class="weetjeInfo">
                        <p>'.$titel.'</p> - <p>'. $row['plaats_datum'] .'</p> - <p>'.$row['geb_datum'].'</p>  '."
                        </div>
                        <hr>
                        <p class='weetje'>". $row['weetjes']."</p>
                        <button id='op-btn-$i' class='op-btn' onclick='openWeetje(this.parentElement.id,this.id)'>▼</button>
                       <div class ='extent'>".( $row['plaatje'] != null ? "<img src='images/images_user/".$row['plaatje']."'>" : "")."
                        </div>
                 </div>";
             $i++;
         }

        } else {
            echo "<div class='error'><p>Geen weetjes gevonden</p></div>";
        }

        if (count($weetjesArr) > 0) {
            $huidigPage1 = $huidigPage-1;
            $huidigPage2 = $huidigPage+1;
            echo "<div class='limitBar'>";
            if ($huidigPage != 0) {
                echo "<form method='get'>
                        <input name='pagina' type='hidden' value='$huidigPage1'>
                        <input class='limitKnop huidig' type='submit' value='❮'>
                      </form>";
            }

            for ($x = 0; $x <= $numRows/15; $x++) {
                $s = $x+1;

                if ($x == $huidigPage){$c = 'huidig';} else {$c='';}
                echo "<form method='get'>
                        <input name='pagina' type='hidden' value='$x'>
                        <input class='limitKnop $c' type='submit' value='$s'>
                      </form>";
            }
            if ($huidigPage <=! $numRows/15+1 && $numRows/15 <=! 1) {
                echo "<form method='get'>
                        <input name='pagina' type='hidden' value='$huidigPage2'>
                        <input class='limitKnop huidig' type='submit' value='❯'>
                  </form>";
            }

            echo "</div>";

        }

        //echo '<pre>' . var_export($weetjesArr, true) . '</pre>';

        //om rows te verwijderen
        if (isset($_POST["verwijder"])) {

            $ID = $_POST["ID"];


            if(strtolower($_SESSION["gebruikersnaam"]) == strtolower($_POST["gebruikersnaam"]) && in_array('weetje.'.$_POST["ID"], $weetjesArr) || $_SESSION["rank"] == 'admin') {
                $ID = $_POST["ID"];
                $sql = "DELETE FROM weetjesdb WHERE ID='$ID'";
                if (mysqli_query($conn, $sql)) {
                    echo '<script>errorr(false, "Weetje '.$ID.' is succesvol verwijderd.")</script>';



                } else {
                    echo '<script>errorr(true, "Er ging iets fout bij het verwijderen van weetje '.$ID.'.")</script>';
                }
            } else {
                echo '<script>errorr(true, "Stout, je mag weetje '.$ID.' niet verwijderen.")</script>';

            }

        }
        ?>

    </div>
</main>

<footer>

</footer>

<script>styleSlider(getCookie("achtergrondSlider"));</script>
<script type="text/javaScript">
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    const actualBtn = document.getElementById('fileInput');

    const fileChosen = document.getElementById('file-chosen');

    actualBtn.addEventListener('change', function(){
        fileChosen.textContent = this.files[0].name
    })

</script>

</body>
</html>