<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function sendMail($email, $subject, $content, $header) {
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";
        $retval = mail ($email,$subject,$content,$header);
        if( $retval == true ) {
            echo "Check je mail voor bevistiging. Mail niet gevonden? Check je spam folder.";
        }else {
            echo "Mail niet verzonden";
    }
    }
?>
