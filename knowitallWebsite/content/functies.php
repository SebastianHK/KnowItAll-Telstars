<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'classes/mail.php';
require_once 'connectie.php';
$errors = [];
//function om bericht te sturen
//unset($_SESSION["geplaatst"]);
//unset($_SESSION["oudTijd"]);
//unset($_SESSION["nieuwTijd"]);
/*function stuur()
{
    global $gebruiker, $conn;
    // if (isset($_SESSION["geplaatst"])) {
        //echo "Sessie Geplaatst: ".$_SESSION["geplaatst"];
        // $nieuwMin = $_SESSION["nieuwTijd"][0];
        // $oudMin = $_SESSION["oudTijd"][0];
        // $nieuwH = $_SESSION["nieuwTijd"][1];
        // $oudH = $_SESSION["oudTijd"][1];
        // $nieuwD = $_SESSION["nieuwTijd"][2];
        // $oudD = $_SESSION["oudTijd"][2];

        //echo '<p>' . $_SESSION["geplaatst"] . '</p>';
    //     if ($_SESSION["geplaatst"] > 10 && abs($nieuwMin - $oudMin) < 20 || $nieuwH !== $oudH || $nieuwD !==$oudD) {
    //         echo "<div class='error'><p>Je hebt te veel berichten geplaatst in een korte tijd!</p></div>";
    //         if (abs($nieuwMin - $oudMin) > 20 || $nieuwH !== $oudH || $nieuwD !==$oudD) {
    //             $_SESSION["geplaatst"] = 0;
    //         }
    //         return;
    //     }
    // }

    $weetje = htmlspecialchars($_POST["weetje"]);
    $datum = htmlspecialchars($_POST["datum"]);
    $titel = htmlspecialchars($_POST["titel"]);
    $image = $_FILES['plaatje']['name'];
    $target = "images/images_user/".basename($image);
    move_uploaded_file($_FILES['plaatje']['tmp_name'], $target);

    $sql = "INSERT INTO weetjesdb (weetjes, titel, gebruiker, geb_datum, plaatje) VALUES ('$weetje', '$titel','$gebruiker','$datum','$image')";

    if (mysqli_query($conn, $sql)) {

        // if (isset($_SESSION["geplaatst"])) {
        //     $_SESSION["geplaatst"] = $_SESSION["geplaatst"] + 1;
        //     $_SESSION["nieuwTijd"] = explode("-",date("i-h-d"));
        // } else {
        //     $_SESSION["nieuwTijd"] = explode("-",date("i-h-d"));
        //     $_SESSION["oudTijd"] = explode("-",date("i-h-d"));
        //     $_SESSION["geplaatst"] = 0;

        // }
    }
}*/

function delZSession() {
    unset($_SESSION["pGebruikersnaam"]);
    unset($_SESSION["pSorteer"]);
    unset($_SESSION["pAscDesc"]);
    unset($_SESSION["pFilter"]);
    unset($_SESSION["zoekUser"]);
}

function edit($weetjesArr, $conn) {
    $ID = $_POST["ID"];
    $index = $_POST["index"];
    $editGebruikersnaam = $_POST["gebruikersnaam"];
    if($_SESSION["rank"] == 'admin') {
        $sqs = "SELECT * FROM `weetjesdb` WHERE ID=$ID";
        $result = $conn->query($sqs);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $wID = $row['id'];
                $wGebruikersnaam = $row['gebruiker'];
                $weetje = $row['weetjes'];
                $plaats_datum = $row['plaats_datum'];
                $wGeb_datum = $row['geb_datum'];
                $wPlaatje = $row['plaatje'];
                $wStatus = $row['status'];
                $comment =$row['comment'];
                $wtitel =$row['titel'];
                echo '
<div id="editFormBackground">
    <form id="editForm" method="post">
        <div class="editFormTexts">
            <label for="eID">ID:</label>
            <input name="eID" id="eID" readonly type="text" value="'.$wID.'">
            <label for="eGebruikersnaam">Gebruiker:</label>
            <input name="eGebruikersnaam" id="eGebruikersnaam" readonly type="text" value="'.$wGebruikersnaam.'">
            <label for="ePlaats_datum">Plaats datum:</label>
            <input name="ePlaats_datum" id="ePlaats_datum" readonly type="date" value="'.$plaats_datum.'">
            <label for="eGeb_datum">Ingevoerde datum:</label>
            <input name="eGeb_datum" id="eGeb_datum" type="date" value="'.$wGeb_datum.'">
            <label for="ePlaatje">Plaatje:</label>
            <input name="ePlaatje" id="ePlaatje" type="text" value="'.$wPlaatje.'">
            <label for="eStatus">Status:</label>
            <select name="eStatus" id="eStatus">
                <option '. (($wStatus=='goedgekeurd')?'selected="selected"':"") .' value="goedgekeurd">goedgekeurd</option>
                <option '. (($wStatus=='afgekeurd')?'selected="selected"':"") .' value="afgekeurd">afgekeurd</option>
                <option '. (($wStatus=='niet_reviewed')?'selected="selected"':"") .' value="niet_reviewed">niet_reviewed</option>
            </select>
        </div>
        <label for="eTitel">Titel:</label>
        <input name="eTitel" id="eTitel" type="text" value="'.$wtitel.'">
        <textarea name="eWeetje">'.$weetje.'</textarea>
        <textarea name="eComment" placeholder="bericht aan gebruiker...">'. $comment .'</textarea>
        <input class="submitKnop" type="reset" value="reset" >
        <input class="submitKnop" type="submit" value="cancel" name="editCancel">
        <input class="submitKnop" type="submit" value="klaar" name="editKlaar">
    </form>
</div>
                ';
            }
        } else {
            echo '<script>errorr(true, "weetje niet gevonden")</script>';
        }

    } else {
        echo '<script>errorr(true, "je mag dit weetje niet aanpassen")</script>';
    }

}

function editKlaar($weetjesArr, $conn)
{
    if($_SESSION["rank"] != 'admin') {
        echo '<script>errorr(true, "je mag dit weetje niet aanpassen")</script>';
        return;
    }
    $eID = $_POST["eID"];
    if ($_POST["eGeb_datum"] == null) {
        $eGeb_datum = "0000-00-00";
    } else {
        $eGeb_datum = $_POST["eGeb_datum"];
    }
    $ePlaatje = $_POST["ePlaatje"];
    $eStatus = $_POST["eStatus"];
    $eWeetje = $_POST["eWeetje"];
    $eComment = $_POST["eComment"];
    $eTitel = $_POST["eTitel"];
    $sql = "UPDATE weetjesdb SET 
                     geb_datum='$eGeb_datum', 
                     plaatje='$ePlaatje',
                     status='$eStatus',
                     weetjes='$eWeetje',
                     comment='$eComment',
                     titel='$eTitel'
                     WHERE id=$eID";
    if (mysqli_query($conn, $sql)) {
        echo '<script>errorr(false, "weetje succesvol gewijzigd")</script>';
    } else {
        echo '<script>errorr(true, "er ging iets fout met het wijzigen van het weetje '.mysqli_error($conn).'")</script>';
    }

}

function zoekUser() {
    if (isset($_POST["zoekUser"])) {
        $user = $_POST["zoekUser"];
    } else {
        $user = $_SESSION["pGebruikersnaam"];
    }
    delZSession();
    selectQueryMaker("RESET",'');
    selectQueryMaker("FROM",'weetjesdb');
    selectQueryMaker("WHERE",'gebruiker='.$user);
    $_SESSION["pGebruikersnaam"] = $user;
    $_SESSION["zoekUser"] = true;
    selectQueryMaker("LIMIT",'0,15');
    $queryString = selectQueryMaker("DONE",'');
    return $queryString;
}

function zoeken($currentPage)
{
    global $huidigPage;
    if (isset($_POST['zoek'])) {

        delZSession();
        selectQueryMaker("RESET",'');
        $pSorteer = $_POST['sorteer'];
        $_SESSION["pSorteer"] = $pSorteer;
        $pAscDesc = $_POST['ascDesc'];
        $_SESSION["pAscDesc"] = $pAscDesc;
        if (isset($_POST['filter'])) {
            $pFilter = $_POST['filter'];
            $_SESSION["pFilter"] = $pFilter;
        } else if ($currentPage === "weetjesCat") {
            $pFilter = "goedgekeurd";
            $_SESSION["pFilter"] = "goedgekeurd";
        }

        $pGebDatum = $_POST['gebDatum'];
        $_SESSION["pGebDatum"] = $pGebDatum;

        selectQueryMaker("FROM",'weetjesdb');
        if ($pFilter != "uit") {
            selectQueryMaker("WHERE",'status='.$pFilter);
        } if ($currentPage === "admin") {
            if (isset($_POST['gebruiker']) && $_POST['gebruiker'] != null) {
                selectQueryMaker("WHERE",'gebruiker='.$_POST['gebruiker']);
                $_SESSION["pGebruikersnaam"] = $_POST['gebruiker'];
            }
        } else if ($currentPage === "profiel") {
            selectQueryMaker("WHERE",'gebruiker='.$_SESSION["gebruikersnaam"]);
        }
        if ($pGebDatum != null) {
            selectQueryMaker("WHERE",'geb_datum='.$pGebDatum);
        }
        selectQueryMaker("ORDER BY",$pSorteer);
        selectQueryMaker("ASCDESC",$pAscDesc);
        selectQueryMaker("LIMIT",'0,15');

    } else if (isset($_SESSION["pSorteer"])) {

        if ($currentPage === "profiel") {
            selectQueryMaker("WHERE",'gebruiker='.$_SESSION["gebruikersnaam"]);
        } elseif (isset($_SESSION["pGebruikersnaam"]) && $_SESSION["pGebruikersnaam"] != null && $currentPage === "admin") {
            selectQueryMaker("WHERE",'gebruiker='.$_SESSION["pGebruikersnaam"]);
        }
        if ($currentPage === "weetjesCat") {
            selectQueryMaker("WHERE",'status=goedgekeurd');
        } elseif ($_SESSION["pFilter"] != "uit") {
            selectQueryMaker("WHERE",'status='.$_SESSION["pFilter"]);

        }
        if ($_SESSION["pGebDatum"] != null) {
            selectQueryMaker("WHERE",'geb_datum='.$_SESSION["pGebDatum"]);
        }
        selectQueryMaker("ORDER BY",$_SESSION["pSorteer"]);
        selectQueryMaker("ASCDESC",$_SESSION["pAscDesc"]);
        $offset = $huidigPage*15;
        selectQueryMaker("LIMIT",$offset.', 15');
    } else {

        if ($currentPage === "profiel") {
            selectQueryMaker("WHERE",'gebruiker='.$_SESSION["gebruikersnaam"]);
        } elseif ($currentPage === "weetjesCat") {
            selectQueryMaker("WHERE",'status=goedgekeurd');
        }
        $offset = $huidigPage*15;
        selectQueryMaker("LIMIT",$offset.', 15');

    }
    $queryString = selectQueryMaker("DONE",'');
    //echo "<h1>".$queryString."</h1>";
    return $queryString;

}

/*if (isset($_GET["pagina"])) {
    $huidigPage = $_GET["pagina"];
    $offset = $huidigPage*15;
    if ($huidigPage < 0) {
        $huidigPage = 1;
    }

    array_push($queryArr,"OFFSET $offset");
}
$RSQS = "SELECT COUNT(id) FROM weetjesdb";
if ($queryArr[1] == "WHERE"){
    $RSQS = $RSQS." WHERE ".$queryArr[2];
    if ($queryArr[3] == "AND"){
        $RSQS = $RSQS." AND ".$queryArr[4];
    }
}*/

/* ------------- QUERY MAKER UITLEG --------------
  Hij werkt met de funtie: selectQueryMaker();
  Je voegt een voor een dingen in.
  Vul het eerste vak welk ding het moet zijn (ik weet de naam ervan niet).
  Daarna komt een komma, dan doe je de value die erin moet.
    voorbeeld: electQueryMaker("SELECT",'ID');

  Als er een + bij staat kunnen er meerdere values worden toegevoegd.
  Een - betekent dat er maar 1 bij kan doen.
  Een * betekent dat het verplicht is


  syntax:
    + welke rows je wilt hebben (run niet als je alle rows wilt):
    selectQueryMaker("SELECT",'');

    * - Van welke tabel je de informatie moet pakken:
    selectQueryMaker("FROM",'');

    + Welke items je wilt filteren (er moet eerst een row staan en dan een = teken met de value die je zoekt):
    selectQueryMaker("WHERE",'');
    voorbeeld:
        selectQueryMaker("WHERE",'gebruikersnaam=Henk');

    - Op welke column je het wilt sorteren:
    selectQueryMaker("ORDER BY",'');

    - sorteren op ASC of DESC:
    selectQueryMaker("ASCDESC",'');

    - het limiet van het aantal rows dat word aangevraagd, en de offset:
    selectQueryMaker("LIMIT",'$offset, $limit');
    voorbeeld:
        selectQueryMaker("LIMIT",'0,10');   Deze laat de eerste 10 rows zien.
        selectQueryMaker("LIMIT",'5,10');   Deze laat 10 rows zien vanaf de 5de row.

    Ben je klaar met alles erin zitten?
    Dit is de functie om je query string te krijgen:
        $queryString = selectQueryMaker("done","");
    Nu heeft de variabele $queryString de query string die je net gemaakt hebt.
    Een array word ook in een session gemaakt.
        $_SESSION['$queryArr']

    Als je refreshed is de array met dingen dus nog vol. als je de array en session wilt resetten doe je dit:
        selectQueryMaker("RESET",'');

    Als je nog vragen hebt, kan je ze altijd vragen.
    Als je echte errors of problemen krijgt, laat het mij weten.
  */

if (isset($_SESSION['$queryArr'])) {
    $queryArr = $_SESSION['$queryArr'];
} else {
    $queryArr = ['SELECT'=>[],'FROM'=>['weetjesdb'],'WHERE'=>[],'ORDER BY'=>[],'LIMIT'=>[],'ASCDESC'=>[]];
}
function selectQueryMaker($dingus,$value) {

    global $queryArr;
    $dingus = strtoupper($dingus);
    if ($dingus == 'RESET') {unset($_SESSION['$queryArr']); $queryArr = ['SELECT'=>[],'FROM'=>[],'WHERE'=>[],'ORDER BY'=>[],'LIMIT'=>[],'ASCDESC'=>[]]; return;}
    if ($dingus == 'DONE') {
        $queryString = "SELECT ";
        if (count($queryArr['SELECT']) == 0) {
            $queryString = $queryString."*";
        } else {
            $c=1;
            foreach ($queryArr['SELECT'] as $v) {

                $queryString = $queryString."`$v`";
                if (count($queryArr['SELECT']) > $c) {
                    $queryString = $queryString.", ";
                }
                $c++;
            }
        }
        if (count($queryArr['FROM']) == 1) {
            $queryString = $queryString.' FROM `'.$queryArr['FROM'][0].'` ';
        } else {echo "<p class='error'>Geen of teveel FROM's ingevuld</p>";return;}

        if (count($queryArr['WHERE']) > 0) {
            $c=1;
            $queryString = $queryString."WHERE ";
            foreach ($queryArr['WHERE'] as $v) {
                $whereArr = explode("=",$v);

                $queryString = $queryString.$whereArr[0]."='".$whereArr[1]."'";
                if (count($queryArr['WHERE']) > $c) {
                    $queryString = $queryString." AND ";
                }
                $c++;
            }
        }

        $queryString = $queryString.' ORDER BY ';
        if (count($queryArr['ASCDESC']) == 1) {
            $ascDesc = $queryArr['ASCDESC'][0];
        } else if (count($queryArr['ORDER BY'])==0){$ascDesc="ASC";}else{echo "<p class='error'>teveel ascDesc's ingevuld</p>";return;}

        if (count($queryArr['ORDER BY']) == 1) {
            $queryString = $queryString.'`'.$queryArr['ORDER BY'][0].'` '.$ascDesc;
        } else if(count($queryArr['ORDER BY'])==0){
            $queryString = $queryString.'1 '.$ascDesc;
        }else{echo "<p class='error'>teveel ORDER BY's ingevuld</p>";return;}

        if (count($queryArr['LIMIT']) == 1) {
            $queryString = $queryString.' LIMIT '.$queryArr['LIMIT'][0];
        } elseif (count($queryArr['LIMIT']) > 1){echo "<p class='error'>teveel LIMIT's ingevuld</p>";return;}

        return $queryString;
    }
    if ( !in_array($dingus, ['SELECT','FROM','WHERE','ORDER BY','LIMIT','ASCDESC'], true ) ) {
        echo "<p class='error'>".$dingus." is geen goeie dingus</p>";
        return;
    }
    $queryArr[$dingus][]=$value;

}

function numRowsQuery() {
    $numQueryString = "SELECT COUNT(id) FROM weetjesdb ";
    global $queryArr;
    if (count($queryArr['WHERE']) > 0) {
        $c=1;
        $numQueryString = $numQueryString."WHERE ";
        foreach ($queryArr['WHERE'] as $v) {
            $whereArr = explode("=",$v);

            $numQueryString = $numQueryString.$whereArr[0]."='".$whereArr[1]."'";
            if (count($queryArr['WHERE']) > $c) {
                $numQueryString = $numQueryString." AND ";
            }
            $c++;
        }
    }
    //echo "<h1>$numQueryString</h1>";
    return $numQueryString;
}



function verifyEmail($gebruiker, $email) {
    if (isset($_SESSION["emailVerify-$email"])) {
        $evs = $_SESSION["emailVerify-$email"];
    } else {
        $_SESSION["emailVerify-$email"] = 0;
        $evs = 0;
    }
    global $errors;
    if ($evs != 2) {
        global $conn;
        global $sdn;
        $hash = md5(rand(0,1000));
        $q = "UPDATE gebruikers SET hash='$hash' WHERE gebruiker='$gebruiker'";

        mysqli_query($conn, $q);
        if (
        sendMail($email, 'Verifieer je email bij de KnowitAll', '
            <h1>Verifieer je email.</h1>
            <h2>'.$gebruiker.'</h2>
            <h2>'.$email.'</h2>
            <hr>
            <div>
                <h2>Klik op de onderste link om je email te registreren.</h2>
                <p>http://'.$sdn.'.student4a0.ao-ica.nl/content/verify.php?email='.$email.'&hash='.$hash.'</p>
            
            </div>

    
    ', 'KnowItAll<558674@edu.rocmn.nl>' ))
        {
            $_SESSION["emailVerify-$email"]++;
            $_SESSION["success"] = "We hebben een verificatie email naar je gestuurd. Als je deze niet kan vinden zit hij waarschijnlijk in je spam folder.";
        } else {
            array_push($errors, "Er ging iets fout bij het sturen van de verificatie mail");
        }
    } else {
        array_push($errors, "Er is al een verificatie email naar je gestuurd.");
    }


}

if (isset($_POST["editUser"]) && $_SESSION["rank"] === "admin") {
    $user = $_POST["gebruikersnaam"];

    /*$numRowsQAf = "SELECT COUNT(*) FROM `weetjesdb` WHERE gebruiker='$user' AND status='afgekeurd'";
    $numRowsQGo = "SELECT COUNT(*) FROM `weetjesdb` WHERE gebruiker='$user' AND status='goedgekeurd'";
    $numRowsQNi = "SELECT COUNT(*) FROM `weetjesdb` WHERE gebruiker='$user' AND status='niet_reviewed'";
    $numRowsQ = (int)$numRowsQNi+(int)$numRowsQGo+(int)$numRowsQAf;*/
    $numRowsQ = "SELECT status FROM `weetjesdb` WHERE gebruiker='$user'";
    $result = $conn->query($numRowsQ);

    if ($result->num_rows > 0) {
        $numRowsT = 0;
        $numRowsAf = 0;
        $numRowsGo = 0;
        $numRowsNi = 0;
        while($row = $result->fetch_assoc()) {
            $numRowsT++;
            if ($row["status"] == "goedgekeurd") {
                $numRowsGo++;
            }
            if ($row["status"] == "afgekeurd") {
                $numRowsAf++;
            }
            if ($row["status"] == "niet_reviewed") {
                $numRowsNi++;
            }
        }
    }

    $sqs = "SELECT * FROM `gebruikers` WHERE gebruiker='$user'";
    $results = mysqli_query($conn, $sqs);

    if (mysqli_num_rows($results) == 1) {
        $result = mysqli_fetch_assoc($results);
        $uID = $result["id"];
        $uEmail = $result["email"];
        $uRank = $result["rank"];
        $uVerified = $result["verified"];
        if ($uVerified == 1) {
            $uVerified = "ja";
        } else {
            $uVerified = "nee";
        }
        echo '
<div id="editFormBackground">
    <form id="editForm" method="post" action="">
        <div class="editFormTexts">
            <h1>Informatie over '.$user.'</h1>
            <input type="hidden" name="user" value="'.$user.'">
            <hr>
            <p>ID: '.$uID.'</p>
            <p>Email: '.$uEmail.'</p>
            <label for="uRank">Rank:</label>
            <select style="width: 200px" name="uRank" id="uRank">
                <option '. (($uRank=='admin')?'selected="selected"':"") .' value="admin">admin</option>
                <option '. (($uRank=='gebruiker')?'selected="selected"':"") .' value="gebruiker">gebruiker</option>
                <option style="color: red" '. (($uRank=='verbannen')?'selected="selected"':"") .' value="verbannen">verbannen</option>
            </select>
        </div>
        <p>Geverifieerd: '.$uVerified.'</p>
        <hr>
        <p>Aantal weetjes: '.$numRowsT.'</p>
        <p>goedgekeurde weetjes: '.$numRowsGo.'</p>
        <p>niet reviewde weetjes: '.$numRowsNi.'</p>
        <p>afgekeurde weetjes: '.$numRowsAf.'</p>
        <a id="DelAllWB" style="width: max-content; font-size: 0.7rem; height: 1rem; display: block;" class="submitKnop" onclick="kill(`DelAll`,`'.$user.'`)">Verwijder afgekeurd</a>
        <input id="DelAllW" style="width: max-content; display: none;" class="submitKnop" type="submit" name="userDelAll" value="Confirm verwijder afgekeurde weetjes van '.$user.'">
        <hr>
        <input class="submitKnop" type="reset" value="reset" >
        <input class="submitKnop" type="submit" value="cancel" name="editCancel">
        <input class="submitKnop" type="submit" value="klaar" name="userEditKlaar">
    </form>

</div>
                ';
    } else {
        array_push($errors, "Er is al iets fout gegaan bij het vinden van de gebruiker.");
    }
}

if (isset($_POST["userEditKlaar"]) && $_SESSION["rank"] === "admin") {
    $qer = "UPDATE gebruikers SET rank='".$_POST["uRank"]."' WHERE gebruiker='".$_POST["user"]."'";

    if (mysqli_query($conn, $qer)) {
        $_SESSION["success"] = "rank van ".$_POST["user"]." is succesvol aangepast.";
    } else {
        array_push($errors, "Er is al iets fout gegaan bij het aanpassen van de rank.");
    }
}

if (isset($_POST["userDelAll"]) && $_SESSION["rank"] === "admin") {
    $qer = "DELETE FROM weetjesdb WHERE gebruiker='".$_POST["user"]."' AND status='afgekeurd'";

    if (mysqli_query($conn, $qer)) {
        $_SESSION["success"] = "Alle weetjes van ".$_POST["user"]." zijn succesvol verwijderd.";
    } else {
        array_push($errors, "Er is al iets fout gegaan bij het verwijderen van de weetjes van ".$_POST["user"].".");
    }
}