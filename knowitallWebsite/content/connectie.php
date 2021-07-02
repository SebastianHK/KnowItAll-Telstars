<?php
/*
$localhost = "localhost"; #localhost
$dbusername = "student4a0_554619"; #username of phpmyadmin
$dbpassword = "qz4QLF";  #password of phpmyadmin
$dbname = "student4a0_554619";  #database name
*/
$localhost = "localhost"; #localhost
$dbusername = "root"; #username of phpmyadmin
$dbpassword = "";  #password of phpmyadmin
$dbname = "knowitall";  #database name

// Vul hier je studenten nummer in:
$sdn = "554619";

// Vul hier de email in waar naar de mails gestuurd moeten worden
$aMail = "mlgdankboom@gmail.co";

$conn = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
$db = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
?>