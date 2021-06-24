<?php
//function om bericht te sturen
//unset($_SESSION["geplaatst"]);
//unset($_SESSION["oudTijd"]);
//unset($_SESSION["nieuwTijd"]);
function stuur()
{
    global $gebruiker, $conn;
    if (isset($_SESSION["geplaatst"])) {
        //echo "Sessie Geplaatst: ".$_SESSION["geplaatst"];
        $nieuwMin = $_SESSION["nieuwTijd"][0];
        $oudMin = $_SESSION["oudTijd"][0];
        $nieuwH = $_SESSION["nieuwTijd"][1];
        $oudH = $_SESSION["oudTijd"][1];
        $nieuwD = $_SESSION["nieuwTijd"][2];
        $oudD = $_SESSION["oudTijd"][2];

        //echo '<p>' . $_SESSION["geplaatst"] . '</p>';
        if ($_SESSION["geplaatst"] > 10 && abs($nieuwMin - $oudMin) < 20 || $nieuwH !== $oudH || $nieuwD !==$oudD) {
            echo "<div class='error'><p>Je hebt te veel berichten geplaatst in een korte tijd!</p></div>";
            if (abs($nieuwMin - $oudMin) > 20 || $nieuwH !== $oudH || $nieuwD !==$oudD) {
                $_SESSION["geplaatst"] = 0;
            }
            return;
        }
    }

    $weetje = htmlspecialchars($_POST["weetje"]);
    $datum = htmlspecialchars($_POST["datum"]);
    $plaatje = htmlspecialchars($_POST["plaatje"]);
    $titel = htmlspecialchars($_POST["titel"]);

    $sql = "INSERT INTO weetjesdb (weetjes, gebruiker, geb_datum, plaatje, titel) VALUES ('$weetje','$gebruiker','$datum','$plaatje','$titel')";

    if (mysqli_query($conn, $sql)) {

        if (isset($_SESSION["geplaatst"])) {
            $_SESSION["geplaatst"] = $_SESSION["geplaatst"] + 1;
            $_SESSION["nieuwTijd"] = explode("-",date("i-h-d"));
        } else {
            $_SESSION["nieuwTijd"] = explode("-",date("i-h-d"));
            $_SESSION["oudTijd"] = explode("-",date("i-h-d"));
            $_SESSION["geplaatst"] = 0;

        }
    }
}

function delZSession() {
    unset($_SESSION["pGebruikersnaam"]);
    unset($_SESSION["pSorteer"]);
    unset($_SESSION["pAscDesc"]);
    unset($_SESSION["pFilter"]);
}

function edit($weetjesArr, $conn) {
    $ID = $_POST["ID"];
    $index = $_POST["index"];
    $editGebruikersnaam = $_POST["gebruikersnaam"];
    if(strtolower($_SESSION["gebruikersnaam"]) == strtolower($editGebruikersnaam) && in_array('weetje.'.$ID, $weetjesArr) || $_SESSION["rank"] == 'admin') {
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
                echo '
<div id="editFormBackground">
    <form id="editForm" method="post">
        <div class="editFormTexts">
            <label for="eID">ID:</label>
            <input name="eID" id="eID" readonly type="text" value="'.$wID.'">
            <label for="eGebruikersnaam">Gebruiker:</label>
            <input name="eGebruikersnaam" id="eGebruikersnaam" readonly type="text" value="'.$wGebruikersnaam.'">
            <label for="ePlaats_datum">Geplaatst op:</label>
            <input name="ePlaats_datum" id="ePlaats_datum" readonly type="date" value="'.$plaats_datum.'">
            <label for="eGeb_datum">Ingevoerde datum:</label>
            <input name="eGeb_datum" id="eGeb_datum" type="date" value="'.$wGeb_datum.'">
            <label for="ePlaatje">Plaatje:</label>
            <input name="ePlaatje" id="ePlaatje" type="text" value="'.$wPlaatje.'">
            <label for="eStatus">Status:</label>
            <select name="eStatus" id="eStatus">
                <option '. (($wStatus=='goedgekeurd')?'selected="selected"':"") .' value="goedgekeurd">Goedgekeurd</option>
                <option '. (($wStatus=='afgekeurd')?'selected="selected"':"") .' value="afgekeurd">Afgekeurd</option>
                <option '. (($wStatus=='niet_reviewed')?'selected="selected"':"") .' value="niet_reviewed">Niet reviewed</option>
            </select>
        </div>
        <textarea name="eWeetje">'.$weetje.'</textarea>
        <input class="submitKnop" type="reset" value="Reset" >
        <input class="submitKnop" type="submit" value="Cancel" name="editCancel">
        <input class="submitKnop" type="submit" value="Klaar" name="editKlaar">
    </form>
</div>
                ';
            }
        } else {
            echo '<script>errorr(true, "weetje niet gevonden")</script>';
        }

    }

    print_r($weetjesArr["weetje.$ID"]);
}

function editKlaar($weetjesArr, $conn)
{
    $eID = $_POST["eID"];
    $eGeb_datum = $_POST["eGeb_datum"];
    $ePlaatje = $_POST["ePlaatje"];
    $eStatus = $_POST["eStatus"];
    $eWeetje = $_POST["eWeetje"];
    $sql = "UPDATE weetjesdb SET 
                     geb_datum='$eGeb_datum', 
                     plaatje='$ePlaatje',
                     status='$eStatus',
                     weetjes='$eWeetje',
                     WHERE id=$eID";

    if (mysqli_query($conn, $sql)) {
        echo '<script>errorr(false, "weetje succesvol gewijzigd")</script>';
    } else {
        echo '<script>errorr(true, "er ging iets fout met het wijzigen van het weetje '.mysqli_error($conn).'")</script>';
    }

}


function zoeken()
{
    global $huidigPage;
    if (isset($_POST['zoek'])) {

        delZSession();
        selectQueryMaker("RESET",'');
        $pSorteer = $_POST['sorteer'];
        $pAscDesc = $_POST['ascDesc'];
        $pFilter = $_POST['filter'];
        $pGebDatum = $_POST['gebDatum'];
        $pGebruikersnaam = $_POST['gebruiker'];
        selectQueryMaker("FROM",'weetjesdb');
        if ($pFilter != "uit") {
            selectQueryMaker("WHERE",'status='.$pFilter);
        } if ($pGebruikersnaam != null) {
            selectQueryMaker("WHERE",'gebruiker='.$pGebruikersnaam);

        } if ($pGebDatum != null) {
            selectQueryMaker("WHERE",'geb_datum='.$pGebDatum);
        }
        selectQueryMaker("ORDER BY",$pSorteer);
        selectQueryMaker("ASCDESC",$pAscDesc);
        selectQueryMaker("LIMIT",'0,15');

    } else if (isset($_SESSION["pSorteer"])) {

        $offset = $huidigPage*15;
        selectQueryMaker("LIMIT",$offset.', 15');
    } else {
        selectQueryMaker("RESET",'');
        selectQueryMaker("FROM",'weetjesdb');

        $offset = $huidigPage*15;
        selectQueryMaker("LIMIT",$offset.', 15');
        selectQueryMaker("DONE",'');
    }
    return $queryString = selectQueryMaker("DONE",'');

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
    return $numQueryString;
}