<?php
session_start();

// sessions voor de zoekbalk
if (isset($_SESSION["pGebruikersnaam"])) {
    $pGebruikersnaam = $_SESSION["pGebruikersnaam"];
} else {
    $pGebruikersnaam = "";
}if (isset($_SESSION["pSorteer"])) {
    $pSorteer = $_SESSION["pSorteer"];
} else {
    $pSorteer = "";
}if (isset($_SESSION["pAscDesc"])) {
    $pAscDesc = $_SESSION["pAscDesc"];
} else {
    $pAscDesc = "";
}if (isset($_SESSION["pFilter"])) {
    $pFilter = $_SESSION["pFilter"];
} else {
    $pFilter = "";
}

// check of je wel ingelogd bent en of je wel een admin bent
$rank = $_SESSION['rank'];
if (!isset($_SESSION['gebruikersnaam'])) {
    $_SESSION['msg'] = "Je moet eerst inloggen";
    header('location: login.php');
}
if ($rank !== 'admin') {
    $_SESSION['msg'] = "Je moet eerst inloggen";
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['gebruikersnaam']);
    unset($_SESSION['rank']);
    header("location: login.php");
}

// connect naar de database
$localhost = "localhost"; #localhost
$dbusername = "root"; #username of phpmyadmin
$dbpassword = "";  #password of phpmyadmin
$dbname = "knowitall";  #database name

$conn = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
}
$sqs = "SELECT id,gebruiker,email,rank FROM `gebruikers`";
$result = $conn->query($sqs);

$gebruikerArr = Array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $RID = $row['id'];
        $rGebruikersnaam = $row['gebruiker'];
        $rRmail = $row['email'];
        $rRank = $row['rank'];

        array_push($gebruikerArr,$rGebruikersnaam);

        $gebruikerArr[$rGebruikersnaam]["id"] = $RID;
        $gebruikerArr[$rGebruikersnaam]["email"] = $rRmail;
        $gebruikerArr[$rGebruikersnaam]["rank"] = $rRank;

    }
} else {
    echo '<script>errorr(true, "Geen resultaten gevonden")</script>';
}

include "functies.php";
$gebruiker = $_SESSION['gebruikersnaam'];
if(isset($_POST["submit"])) {
    stuur();
}
$huidigPage = 0;
if (isset($_GET["pagina"])) {
    $huidigPage = $_GET["pagina"];
    if ($huidigPage < 0) {
        $huidigPage = 0;
    }
}
$queryString = zoeken();

$numRowsQuery = numRowsQuery();
//echo "<h1>".$numRowsQuery."</h1>";
$RSQS = "SELECT COUNT(id) FROM weetjesdb";
$numRows = $conn->query($numRowsQuery);
$numRows = $numRows->fetch_assoc();
$numRows = $numRows['COUNT(id)'];
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
    <link rel="icon" href="" type="image/icon type">
    <link rel="icon" href="images/alleen_doos_logo.png" type="image/icon type">
</head>
<div id="xboxAchtergrond" class="xboxStyle achtergrond" style="opacity: 0;"></div>
<div id="nintendoAchtergrond" class="nStyle achtergrond" style="opacity: 0;"></div>
<div id="psAchtergrond" class="psStyle achtergrond" style="opacity: 0;"></div>

<body>

<header>
    <div id="styleSwitch">
        <p id="switchText"></p>
        <label id="styleSlider" class="switch">
                <input id="sliderCheck">
            <span id="slider" class="slider norm round nintendo"></span>
        </label>
    </div>
    <a class="titel navKnop" href="../index.php">TheKnowItAll</a>
    <div class="navKnoppen">
        <a href="weetjesCat.php" class="navKnop headerNavKnop">Weetjes Catalogus</a>
        <a href="index.php" class="navKnop headerNavKnop">Profiel</a>
        <a class="navKnop headerNavKnop" onclick="document.getElementById('weetjeStuurder').style.display = 'block'">Weetje toevoegen</a>
        <?php  if ($_SESSION['rank'] == 'admin') : ?>
            <a href="admin_control_panel.php" class="navKnop headerNavKnop" id="adminCPK">Admin Control Panel</a>
        <?php endif ?>
    </div>
    <a href="index.php?logout='1'" class="navKnop logKnop">Uitloggen</a>

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
    <?php if (isset($_POST['verwijder']) || isset($_POST['ban']) || isset($_POST['edit']) || isset($_POST['editKlaar'])) : ?>
        <div id="errorDiv" class="error success" >
            <h3 id="errorText">

            </h3>
        </div>
    <?php endif ?>

    <?php if (isset($_SESSION['gebruikersnaam']) && $rank === 'admin') : ?>

    <p class="titelText">Welkom op de Admin Control Panel, <strong class="titelText"><?php echo $_SESSION['gebruikersnaam']; ?></strong></p>

    <form style="display: none;" method="POST" id="weetjeStuurder" action="index.php">
        <div onclick="document.getElementById('weetjeStuurder').style.display = 'none'" id="wegKnopWeetjeStuurder">x</div>
        <input type="text" required name="titel" id="titel" placeholder="titel" maxlength="50"></input><br>
        <textarea required name="weetje" id="weetje" placeholder="weetje" maxlength="400"></textarea><br>
        <p>datum van gebeurd</p>
        <input name="datum" type="date">
        <div id="fileInputContainer">
            <input hidden id="fileInput" name="plaatje" type="file" name="image[]" /><br>
            <label id="fileInputLabel" for="fileInput">Bladeren...</label>
            <span id="file-chosen">Geen file gekozen</span>
        </div>
        <input class="submitKnop" type="submit" name="submit" value="VERSTUUR">
    </form>
    <div id="restContainer">

        <form id="zoekCentrum" action="" method="post">
            <label for="gebruiker">Gebruikersnaam</label>
            <label for="sorteer">Sorteer</label>
            <label></label>
            <label for="gebDatum">Datum gebeurd</label>
            <label for="filter">Filter</label>
            <!--<input class="zoekInput" type="text" name="gebruiker" placeholder="gebruiker">-->
            <input list="gebruikers" value="" name="gebruiker" id="gebruikerDataList" class="zoekInput">
            <datalist id="gebruikers">
                <?php
                foreach ($gebruikerArr as $fGebruiker) {
                    if (is_string($fGebruiker)) {
                        echo "<option value='$fGebruiker'>";

                    }
                }
                ?>
            </datalist>

            <select id="sorteerInput" class="zoekInput" name="sorteer">
                <option id="plaats_datum" value="plaats_datum">Datum Geplaatst</option>
                <option id="geb_datum" value="geb_datum">Ingevoerde Datum</option>
                <option id="status" value="status">Status</option>
                <option id="gebruikersnaam" value="gebruikersnaam">Gebruikersnaam</option>
            </select>

            <select id="ascDescInput" class="ascDesc zoekInput" name="ascDesc">
                <option id="ASC" value="ASC">Oplopend</option>
                <option id="DESC" value="DESC">Aflopend</option>
            </select>
            <input class="zoekInput" type="date" name="gebDatum" id="gebDatum">
            <select id="filterInput" class="zoekInput" name="filter">
                <option id="uit" value="uit">uit</option>
                <option id="goedgekeurd" value="goedgekeurd">Goedgekeurd</option>
                <option id="niet_reviewed" value="niet_reviewed">Niet reviewed</option>
                <option id="afgekeurd" value="afgekeurd">Afgekeurd</option>
            </select>
            <input type="reset" value="reset">
            <input type="submit" value="zoek" name="zoek" class="zoekInput">
        </form>
        <script>
        <?php
        if($pGebruikersnaam!=''){
            echo 'document.getElementById("gebruikerDataList").value = "'.$pGebruikersnaam.'";';
        }if($pSorteer!=''){
            echo 'document.getElementById("sorteerInput").selectedIndex = document.getElementById("sorteerInput").options.namedItem("' . $pSorteer . '").index;';
        } if($pAscDesc!=''){
            echo 'document.getElementById("ascDescInput").selectedIndex = document.getElementById("ascDescInput").options.namedItem("' . $pAscDesc . '").index;';
        } if($pFilter!=''){
            echo 'document.getElementById("filterInput").selectedIndex = document.getElementById("filterInput").options.namedItem("' . $pFilter . '").index;';
        }

        ?>
        </script>
        <div style="height: 100%;" class="weetjeDiv weetjeInfo">
            <p class="tooltip">ID
                <span class="tooltiptext">ID van het weetje</span>
            </p> -
            <p class="tooltip">gebruiker
                <span class="tooltiptext">Naam van gebruiker</span>
            </p> -
            <p class="tooltip">titel
                <span class="tooltiptext">Titel van het weetje</span>
            </p> -
            <p class="tooltip">plaats datum
                <span class="tooltiptext">Datum dat het weetje geplaatst is</span>
            </p> -
            <p class="tooltip">ingevoerde datum
                <span class="tooltiptext">Datum dat de gebruiker heeft ingevoerd van wanneer het gebeurt is</span>
            </p> -
            <p class="tooltip">status
                <span class="tooltiptext">Satus van het weetje</span>
            </p> -
            <p class="tooltip">edit tools
                <span class="tooltiptext">Verwijder of pas een weetje aan</span>
            </p>
        </div>
        <?php


        $sqs = $queryString;
        //TODO gebruiker status toevoegen;
        if ($pGebruikersnaam != "") {
            echo "<div id='persInfo'>Weetjes van $pGebruikersnaam <form class='invis' onsubmit='return kill()' method='POST' action=''><input type='hidden' name='ID' value=''><input type='hidden' name='gebruikersnaam' value='$pGebruikersnaam'><input class='verwijder' name='ban' value='' type='submit'></form></div>";
        }
        echo "<p>Resultaten: $numRows</p>";

        //echo "<h1>".$sqs."</h1>";

        $result = $conn->query($sqs);

        $weetjesArr = Array();

        if ($result->num_rows > 0) {
            $c = 0;
            while($row = $result->fetch_assoc()) {

                $ID = $row['id'];
                $gebruikersnaam = $row['gebruiker'];
                $titel = $row['titel'];
                array_push($weetjesArr,'weetje.'.$ID);

                $weetjesArr['weetje.'.$ID] = $row['weetjes'];

                echo '<div class="weetjeDiv">
                        <div class="weetjeInfo">
                        <p>'.$ID.'</p> - <p>'. $gebruikersnaam .'</p> - <p>'.$titel.'</p> - <p>'. $row['plaats_datum'] .'</p> - <p>'.$row['geb_datum'].'</p> - <p>'. $row['status']."</p>
                            <div id='editKnoppen'>
                                 <form class='invis editForm' method='POST' action=''>
                                       <input type='hidden' name='ID' value='$ID'>
                                       <input type='hidden' name='index' value='$c'>
                                       <input type='hidden' name='gebruikersnaam' value='$gebruikersnaam'>
                                       <input class='edit' name='edit' value='' type='submit'>
                                 </form>
                                 <form class='invis' onsubmit='return kill()' method='POST' action=''><input type='hidden' name='ID' value='$ID'>
                                       <input type='hidden' name='gebruikersnaam' value='$gebruikersnaam'>
                                       <input class='verwijder' name='verwijder' value='' type='submit'>
                                 </form>
                             </div>
                        </div>
                           <hr>
                           <p class='weetje'>". $row['weetjes']."</p>
                    </div>";
                $c++;
            }
        } else {
            echo "<div class='error'><p>Je hebt nog geen weetjes</p></div>";
        }
        if (isset($_POST["edit"])) {
            edit($weetjesArr, $conn);
        }
        if (isset($_POST["editKlaar"])) {
            editKlaar($weetjesArr, $conn);
        }

            $huidigPage1 = $huidigPage-1;
            $huidigPage2 = $huidigPage+1;
            echo "<div class='limitBar'>";
            if ($huidigPage != 0) {
                echo "<form method='get'>
                        <input name='pagina' type='hidden' value='$huidigPage1'>
                        <input class='limitKnop huidig' type='submit' value='<'>
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
            if ($huidigPage <=! $numRows/15+1) {
                echo "<form method='get'>
                        <input name='pagina' type='hidden' value='$huidigPage2'>
                        <input class='limitKnop huidig' type='submit' value='>'>
                  </form>";
            }

            echo "</div>";



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
        if (isset($_POST["ban"]) && $_SESSION["rank"] == "admin") {
            $persoonn = $_POST["gebruikersnaam"];
            echo '<script>errorr(true, "Je wilde '.$persoonn.' bannen maar deze functie is nog niet gemaakt")</script>';
            //TODO ban ding maken
            /*$sql = "SELECT * FROM weetjesdb WHERE ID='$ID'";
            if (mysqli_query($conn, $sql)) {
                echo '<script>errorr(false, "Weetje '.$ID.' is succesvol verwijderd.")</script>';
            } else {
                echo '<script>errorr(true, "Er ging iets fout bij het verwijderen van weetje '.$ID.'.")</script>';
            }*/
        }
        ?>
        <?php endif ?>

    </div>
</main>

<footer>

</footer>

<script>styleSlider("nintendo");</script>
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