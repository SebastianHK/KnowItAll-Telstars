<!DOCTYPE html>

<html>
<head>
    <script src="mainScript.js"></script>
    <title>verifieer email KnowitAll</title>
    <link rel="stylesheet" href="mainStyles.css">
    <meta http-equiv="Content-Type"content="text/html;charset=UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="icon" href="images/alleen_doos_logo.png" type="image/icon type">
</head>
<body>
<!-- start header div -->
<div id="header">
    <h3>KnowitAll email verificatie</h3>
</div>
<!-- end header div -->

<!-- start wrap div -->
<div id="wrap">
    <!-- start PHP code -->
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
require 'connectie.php';
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
        // Verify data
        $email = mysqli_escape_string($conn, $_GET['email']); // Set email variable
        $hash = mysqli_escape_string($conn, $_GET['hash']); // Set hash variable

        $search = mysqli_query($conn,"SELECT email, hash, verified FROM gebruikers WHERE email='".$email."' AND hash='".$hash."' AND verified='0'") or die(mysqli_error());
        $match  = mysqli_num_rows($search);

        if($match > 0){
            // We have a match, activate the account
            mysqli_query($conn, "UPDATE gebruikers SET verified='1' WHERE email='".$email."' AND hash='".$hash."' AND verified='0'") or die(mysqli_error());
            echo '<div class="statusmsg">Your account has been activated, you can now login</div>';
        }else{
            // No match -> invalid url or account has already been activated.
            echo '<div class="statusmsg">The url is either invalid or you already have activated your account.</div>';
        }

    }else{
        // Invalid approach
        echo '<div class="statusmsg">Invalid approach, please use the link that has been send to your email.</div>';
    }
    ?>
    <!-- stop PHP Code -->


</div>
<!-- end wrap div -->
</body>
</html>