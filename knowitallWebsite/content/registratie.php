<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta http-equiv="Content-Type"content="text/html;charset=UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>KnowItAll</title>
    <link rel="stylesheet" href="mainStyles.css">
    <link rel="icon" href="" type="image/icon type">
    
</head>
    <div id="xboxAchtergrond" class="xboxStyle achtergrond" style="opacity: 0;"></div>
    <div id="nintendoAchtergrond" class="nStyle achtergrond" style="opacity: 0;"></div>
    <div id="psAchtergrond" class="psStyle achtergrond" style="opacity: 0;"></div>
    
<body>
    
    <header>
        <div id="styleSwitch">
            <p id="switchText"></p>
            <label id="styleSlider" class="switch">
                <input id="sliderCheck" type="checkbox" onclick="styleSlider()">
                <span id="slider" class="slider norm round nintendo"></span>
              </label>
    </div>
    <a class="titel navKnop" href="../index.php">TheKnowItAll</a>
    <div class="navKnoppen">
        <a href="weetjesCat.php" class="navKnop headerNavKnop">feitjes</a>

    </div>


    </header>

    <main>

        <form class="registratieContainer" method="post" action="registratie.php">
            <?php include('errors.php'); ?>
            <h1>Aanmelden</h1>
            
            <div class="inputVeld">
                <label>Gebruikersnaam:</label>
                <input type="text" name="gebruikersnaam" value="<?php echo $gebruikersnaam; ?>">
            </div>
            <div class="inputVeld">
                <label>Email-adres:</label>
                <input type="text" name="email" value="<?php echo $email; ?>">
            </div>
            <div class="inputVeld">
                <label>Wachtwoord:</label>
                <input type="password" name="wachtwoord_1">
            </div>
            <div class="inputVeld">
                <label>Wachtwoord herhalen:</label>
                <input type="password" name="wachtwoord_2">
            </div>
            <p>
                Heb je al een account? <a href="login.php">Log in</a>
            </p>
            <div id="buttonDiv">
                <button class="submitKnop" id="submitKnop1" type="submit" name="reg_gebruiker">Registreren</button>
            </div>
            
        </form>
    </main>

    <footer>
        
    </footer>
    <script src="mainScript.js"></script>
    <script>styleSlider(getCookie("achtergrondSlider"));</script>

</body>
</html>