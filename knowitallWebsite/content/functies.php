<?php
//function om bericht te sturen
//unset($_SESSION["geplaatst"]);
//unset($_SESSION["oudTijd"]);
//unset($_SESSION["nieuwTijd"]);
function stuur()
{
    global $gebruiker, $conn;
    /*if (isset($_SESSION["geplaatst"])) {
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
    }*/

    $weetje = htmlspecialchars($_POST["weetje"]);
    $datum = htmlspecialchars($_POST["datum"]);
    $plaatje = htmlspecialchars($_POST["plaatje"]);
    $titel = htmlspecialchars($_POST["titelweetje"]);

    if ($weetje == NULL || $titel == NULL) {
        echo "<div class='error'><p>Je moet een weetje en een titel toevoegen</p></div>";
        return;
    }

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
                $comment =$row['comment'];
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
    $eComment = $_POST["eComment"];
    $sql = "UPDATE weetjesdb SET 
                     geb_datum='$eGeb_datum', 
                     plaatje='$ePlaatje',
                     status='$eStatus',
                     weetjes='$eWeetje',
                     comment='$eComment'
                     WHERE id=$eID";

    if (mysqli_query($conn, $sql)) {
        echo '<script>errorr(false, "weetje succesvol gewijzigd")</script>';
    } else {
        echo '<script>errorr(true, "er ging iets fout met het wijzigen van het weetje '.mysqli_error($conn).'")</script>';
    }

}

function zoeken()
{
    if (isset($_POST['zoek'])) {
        delZSession();
        $filterArr = [];
        $pSorteer = $_POST['sorteer'];
        $pAscDesc = $_POST['ascDesc'];
        $pFilter = $_POST['filter'];
        $pGebDatum = $_POST['gebDatum'];
        echo "<h1>DATUM GEBEURD: " . $pGebDatum . "</h1>";
        if ($_POST['gebruiker'] != null || $pFilter != "uit" || $pGebDatum != null) {
            array_push($queryArr, "WHERE");
            if ($_POST['gebruiker'] != null) {
                $pGebruikersnaam = $_POST['gebruiker'];
                $_SESSION['pGebruikersnaam'] = $pGebruikersnaam;
                array_push($filterArr, "gebruiker='$pGebruikersnaam'");
            }
            if ($pFilter != "uit") {
                array_push($filterArr, "status='$pFilter'");
            }
            if ($pGebDatum != null) {
                array_push($filterArr, "geb_datum='$pGebDatum'");
                $_SESSION['pGebDatum'] = $pGebDatum;
            }
            $i = 1;
            echo '<h1>' . count($filterArr) . '</h1>';
            foreach ($filterArr as $filter) {

                array_push($queryArr, "$filter");
                if ($i < count($filterArr)) {
                    array_push($queryArr, "AND");
                    echo "<h1> HEY" . $i . "</h1>";
                }
                $i++;

            }
        }

        $_SESSION['pSorteer'] = $pSorteer;
        $_SESSION['pAscDesc'] = $pAscDesc;
        $_SESSION['pFilter'] = $pFilter;

        array_push($queryArr, "ORDER BY `$pSorteer` $pAscDesc");
    } else if (isset($_SESSION["pSorteer"])) {

        $pSorteer = $_SESSION['pSorteer'];
        $pAscDesc = $_SESSION['pAscDesc'];
        $pFilter = $_SESSION['pFilter'];
        $filterArr = [];
        if (isset($pFilter) || isset($_SESSION['pgebDatum']) || isset($_POST['gebruiker'])) {
            array_push($queryArr, "WHERE");
            if (isset($_POST['gebruiker'])) {
                $_SESSION['pGebruikersnaam'] = $pGebruikersnaam;
                array_push($filterArr, "gebruiker='$pGebruikersnaam'");
            }
            if ($pFilter != "uit") {
                array_push($filterArr, "status='$pFilter'");
            }
            if (isset($_SESSION['pgebDatum'])) {
                $pGebDatum = $_SESSION['pGebDatum'];
                array_push($filterArr, "geb_datum='$pGebDatum'");

            }
            $i = 1;
            echo '<h1>' . count($filterArr) . '</h1>';
            foreach ($filterArr as $filter) {

                array_push($queryArr, "$filter");
                if ($i < count($filterArr)) {
                    array_push($queryArr, "AND");
                    echo "<h1> HEY " . $i . "</h1>";
                }
                $i++;

            }
        }
    }
}