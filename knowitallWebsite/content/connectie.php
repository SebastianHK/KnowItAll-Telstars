<?php

/*$localhost = "localhost"; #localhost
$dbusername = "student4a0_558674"; #username of phpmyadmin
$dbpassword = "kryX8I";  #password of phpmyadmin
$dbname = "student4a0_558674";  #database name
*/


$localhost = "localhost"; #localhost
$dbusername = "root"; #username of phpmyadmin
$dbpassword = "";  #password of phpmyadmin
$dbname = "knowitall";  #database name

// Vul hier je studenten nummer in:
$sdn = "558674";

// Vul hier de email in waar naar de mails gestuurd moeten worden
$aMail = "lexbrinkman2002@gmail.com";

$conn = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
$db = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
?>