
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


$localhost = "localhost"; #localhost
$dbusername = "root"; #username of phpmyadmin
$dbpassword = "";  #password of phpmyadmin
$dbname = "knowitall";  #database name

$conn = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
}

date_default_timezone_set("Europe/Amsterdam");
setlocale(LC_TIME, array('nl_NL.UTF-8','nl_NL@euro','nl_NL','dutch'));


$weetjes = array();
$vandaag = date("d-m-Y");

$sqs = "SELECT * FROM weetjesDB WHERE geb_datum='$vandaag'";

$result = $conn->query($sqs);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['status'] == 'goedgekeurd') {
            array_push($weetjes,$row["weetjes"]);
        }


    }
} else {
    echo "0 results";
}

if (isset($_SESSION['gebruikersnaam'])) {
    $gebruikersnaam = $_SESSION['gebruikersnaam'];
} else {
    $gebruikersnaam = "gast";
}

if (count($weetjes) == 0) {
    array_push($weetjes, "Geen weetje bro");
}
$conn->close();
?>
    <header>
        <div id="styleSwitch">
            <p id="switchText"></p>
            <label id="styleSlider" class="switch">
                <input id="sliderCheck" type="checkbox" onclick="styleSlider()">
                <span id="slider" class="slider norm round nintendo"></span>
              </label>
    </div>
    <a class="titel navKnop" href="index.php">TheKnowItAll</a>
    <div class="navKnoppen">
        <a href="content/weetjesCat.php" class="navKnop headerNavKnop">weetjes catalogus</a>
        <a href="content/index.php" class="navKnop headerNavKnop">profiel</a>
    </div>

        <?php  if ($gebruikersnaam == 'gast') : ?>
            <a href="content/login.php" class="navKnop logKnop">login/registreer</a>
        <?php endif ?>
        <?php  if ($gebruikersnaam !== 'gast') : ?>
            <a href="index.php?logout='1'" class="navKnop logKnop">uitloggen</a>
        <?php endif ?>

    </header>

    <main>
        <div id="labels">
            <label>Feitje van <?php echo strftime("%A %d %B") ?>:</label>
            <label>Informatie over de KnowItAll:</label>
        </div>
        <div id="boxen">
            
            <div class="grootBox">
                <?php
                echo '<p>'. $weetjes[mt_rand(0, count($weetjes)-1)] .'</p>';
                ?>
            </div>
            
            <div class="grootBox">
                
                <p>Welkom op de KnowItAll website. Meer informatie volgt</p>
            </div>
        </div>

    </main>

    <footer>
        
    </footer>
    <script src="content/mainScript.js"></script>
    <script>styleSlider(getCookie("achtergrondSlider"));</script>

</body>
</html>