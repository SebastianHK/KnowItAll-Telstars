<?php
session_start();



require_once "classes/mail.php";
// initializing variables
$gebruikersnaam = "";
$email = "";
$errors = array();
require 'connectie.php';
require 'functies.php';
// connect to the database
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//$db = mysqli_connect('localhost', 'root', '', 'knowitall');

// REGISTER USER
if (isset($_POST['reg_gebruiker'])) {
    // receive all input values from the form
    $gebruikersnaam = mysqli_real_escape_string($db, $_POST['gebruikersnaam']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $wachtwoord_1 = mysqli_real_escape_string($db, $_POST['wachtwoord_1']);
    $wachtwoord_2 = mysqli_real_escape_string($db, $_POST['wachtwoord_2']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($gebruikersnaam)) { array_push($errors, "Gebruikersnaam is verplicht"); }
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (empty($email)) { array_push($errors, "Email is verplicht"); }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email niet geldig");
    }
    if (empty($wachtwoord_1)) { array_push($errors, "Wachtwoord is verplicht"); }
    if ($wachtwoord_1 != $wachtwoord_2) {
        array_push($errors, "De twee wachtwoorden komen niet overeen");
    }
    $characters = array("A","B","C","D","E","F","G","H","I","J",
        "K","L","M","N","O","P","Q","R","S","T",
        "U","V","W","X","Y","Z","a","b","c","d",
        "e","f","g","h","i","j","k","l","m","n",
        "o","p","q","r","s","t","u","v","w","x",
        "y","z","1","2","3","4","5","6","7","8",
        "9","0","?","!","_","-");
    foreach (str_split($gebruikersnaam) as $char) {
        if (!in_array($char, $characters)) {
            array_push($errors, "Een van de characters in je gebruikersnaam mag je niet gebruiken.");
            break;
        }
    }
    foreach (str_split($wachtwoord_1) as $char) {
        if (!in_array($char, $characters)) {
            array_push($errors, "Een van de characters in je wachtwoord mag je niet gebruiken.");
            break;
        }
    }

    // first check the database to make sure
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT * FROM gebruikers WHERE gebruiker='$gebruikersnaam' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $gebruiker = mysqli_fetch_assoc($result);

    if ($gebruiker) { // if user exists
        if ($gebruiker['gebruiker'] === $gebruikersnaam) {
            array_push($errors, "Deze gebruikersnaam word al gebruikt");
        }

        if ($gebruiker['email'] === $email) {
            array_push($errors, "Deze email word al gebruikt");
        }
    }

    // Finally, register user if there are no errors in the form
      if (count($errors) == 0) {
        $wachtwoord = password_hash($wachtwoord_1,PASSWORD_DEFAULT);//encrypt the password before saving in the database
        $query = "INSERT INTO gebruikers (gebruiker, email, wachtwoord) 
                VALUES('$gebruikersnaam', '$email', '$wachtwoord')";
        if (mysqli_query($db, $query)) {
            verifyEmail($gebruikersnaam, $email);
        } else {
            array_push($errors, "Er ging iets fout bij het maken van het account");
        }
    }
}

if (isset($_POST["verifyEmail"])) {
    verifyEmail($_POST["gebruikersnaam"], $_POST['email']);
}

// LOGIN USER
if (isset($_POST['login_gebruiker'])) {
    $gebruikersnaam = mysqli_real_escape_string($db, $_POST['gebruikersnaam']);
    $wachtwoord = mysqli_real_escape_string($db, $_POST['wachtwoord']);

    if (empty($gebruikersnaam)) {
        array_push($errors, "Gebruikersnaam is verplicht");
    }
    if (empty($wachtwoord)) {
        array_push($errors, "wachtwoord is verplicht");
    }

    if (count($errors) == 0) {
        $query = "SELECT * FROM gebruikers WHERE gebruiker='$gebruikersnaam'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
            $result = mysqli_fetch_assoc($results);
            if ($result['rank'] != "verbannen") {
                if(password_verify($wachtwoord, $result['wachtwoord'])) {
                    if ($result['verified'] == 1) {
                        $_SESSION['gebruikersnaam'] = $gebruikersnaam;
                        $_SESSION['success'] = "Je bent succesvol ingelogd!";
                        $_SESSION['rank'] = $result['rank'];
                        header('location: index.php');
                    } else {
                        array_push($errors, "Verifieer je email aub. <form method='post' action=''><input type='hidden' name='gebruikersnaam' value='$gebruikersnaam'><input type='hidden' name='email' value='".$result["email"]."'><input type='submit' name='verifyEmail' value='Stuur email opnieuw'></form>");
                    }
                } else {
                    array_push($errors, "Verkeerde gebruikersnaam/wachtwoord combinatie");
                }
            } else {
                array_push($errors, "Je account is verbannen.");
            }
        } else {
            array_push($errors, "Verkeerde gebruikersnaam/wachtwoord combinatie");
        }
    }
}

?>