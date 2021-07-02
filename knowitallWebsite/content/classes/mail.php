<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function sendMail($email, $subject, $content, $header) {
        $header .= "MIME-Version: 1.0\r\n";
        $header .= 'From: KnowItAll <554619@edu.rocmn.nl>' . "\r\n";
        $header .= "Content-type: text/html\r\n";
        $retval = mail ($email,$subject,$content,$header);
        return $retval;
    }
?>
