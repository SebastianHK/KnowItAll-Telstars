<?php
/*
$localhost = "localhost"; #localhost
$dbusername = "student4a0_558674"; #username of phpmyadmin
$dbpassword = "kryX8I";  #password of phpmyadmin
$dbname = "student4a0_558674";  #database name
*/
$localhost = "localhost"; #localhost
$dbusername = "root"; #username of phpmyadmin
$dbpassword = "";  #password of phpmyadmin
$dbname = "knowitall";  #database name
$conn = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
$db = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
?>