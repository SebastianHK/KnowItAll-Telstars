<!DOCTYPE html>
<html lang="nl">
<head>
    <meta http-equiv="Content-Type"content="text/html;charset=UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>KnowItAll</title>
    <link rel="stylesheet" href="content/mainStyles.css">
    <link rel="icon" href="content/images/alleen_doos_logo.png" type="image/icon type">

</head>
    <div id="xboxAchtergrond" class="xboxStyle achtergrond" style="opacity: 0;"></div>
    <div id="nintendoAchtergrond" class="nStyle achtergrond" style="opacity: 0;"></div>
    <div id="psAchtergrond" class="psStyle achtergrond" style="opacity: 0;"></div>
    
<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'content/connectie.php';
if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
}

date_default_timezone_set("Europe/Amsterdam");
setlocale(LC_TIME, array('nl_NL.UTF-8','nl_NL@euro','nl_NL','dutch'));


$weetjes = array();

$vandaag = date("m-d");
$weetjes = zoekWeetjesOfzo($vandaag);

function zoekWeetjesOfzo($dag) {
    global $conn;
    $sqs = "SELECT * FROM weetjesdb WHERE geb_datum LIKE '%$dag' AND status='goedgekeurd'";
    $result = $conn->query($sqs);
    $i = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            $weetjes[$i]["gebruiker"] = $row["gebruiker"];
            $weetjes[$i]["titel"] = $row["titel"];
            $weetjes[$i]["weetjes"] = $row["weetjes"];
            $weetjes[$i]["plaatje"] = $row["plaatje"];
            $i++;
        }
        return $weetjes;
    } else {
        return zoekWeetjesOfzo('0000-00-00');
    }
}


$vWeetjes = $weetjes[mt_rand(0, count($weetjes)-1)];



session_start();
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
    header("location: index.php");
}

if (count($weetjes) == 0) {
    array_push($weetjes, "Geen weetje bro");
}
$conn->close();
?>
    <header>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Top Navigation Menu -->
        <div class="topnav">
            <a href="#home" class="active">ddd</a>
            <!-- Navigation links (hidden by default) -->
            <div id="myLinks">
                <a href="content/weetjesCat.php">Weetjes catalogus</a>
                <?php  if ($gebruikersnaam == 'gast') : ?>
                    <a href="content/login.php">Login/Registreer</a>
                <?php endif ?>
                <?php  if ($gebruikersnaam !== 'gast') : ?>
                    <a href="content/index.php">Profiel</a>
                    <a onclick="document.getElementById('weetjeStuurder').style.display = 'block'">Weetje toevoegen</a>
                <?php endif ?>
                <?php  if ($rank == 'admin') : ?>
                    <a href="content/admin_control_panel.php">Admin Control Panel</a>
                <?php endif ?>
                <?php  if ($gebruikersnaam !== 'gast') : ?>
                    <a href="content/index.php?logout='1'">Uitloggen</a>
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
    <a class="titel navKnop" href="index.php">TheKnowItAll</a>
    <div class="navKnoppen">
        <a href="content/weetjesCat.php" class="navKnop headerNavKnop">Weetjes catalogus</a>
    </div>

        <?php  if ($gebruikersnaam == 'gast') : ?>
            <a href="content/login.php" class="navKnop logKnop">Login/Registreer</a>
        <?php endif ?>
        <?php  if ($gebruikersnaam !== 'gast') : ?>
            <a href="content/index.php" class="navKnop headerNavKnop">Profiel</a>
            <a href="index.php?logout='1'" class="navKnop logKnop">Uitloggen</a>
        <?php endif ?>
        <?php  if ($rank == 'admin') : ?>
            <a href="content/admin_control_panel.php" class="navKnop headerNavKnop" id="adminCPK">Admin Control Panel</a>
        <?php endif ?>

    </header>

    <main>
        <div id="labels">
            <label>Informatie over de KnowItAll:</label>
            <label>Weetje van <?php echo strftime("%A %d %B") ?><br>Gemaakt door <?php echo $vWeetjes["gebruiker"] ?><br></label>
        </div>
        <div id="boxen">
            <div class="grootBox">
                <p>Welkom op de KnowItAll website. De KnowItAll is een online database volledig gefocussed op jawel, video games! Op deze website kan je je eigen leuke feitjes toevoegen, met afbeelding. </p>
            </div>
            <div class="grootBox">
                <?php
                echo '<h2 style="font-weight:400;">'.$vWeetjes["titel"].'</h2>';
                echo '<p>'. $vWeetjes["weetjes"] .'</p>';
                echo ( $vWeetjes['plaatje'] != null ? "<img src='content/images/images_user/".$vWeetjes['plaatje']."'>" : "");
                ?>
            </div>
        </div>

    </main>

    <footer>
        
    </footer>
    <script src="content/mainScript.js"></script>
    <script>styleSlider(getCookie("achtergrondSlider"));</script>

</body>
</html>