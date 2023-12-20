<?php
$login = 'admin';
$pass = 'admin';
$adminEmail = "admin@admin.pl";
$odbiorca = "admin123@admin.pl";

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

$link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

if (!$link) {
    echo '<b>Przerwane połączenie</b>';
}

if (!mysqli_select_db($link, $baza)) {
    echo 'Nie wybrano bazy';
}
?>