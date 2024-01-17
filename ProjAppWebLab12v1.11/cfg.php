<?php
// Ustawienia dla logowania
$login = 'admin';
$pass = 'admin';
$adminEmail = "admin@admin.pl";
$odbiorca = "admin123@admin.pl";

$config = array(
    'login' => 'admin',
    'pass' => 'admin',
    'adminEmail' => 'admin@admin.pl',
    'odbiorca' => 'admin123@admin.pl',
);

// Ustawienia połączenia z bazą danych
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';


// Połączenie z bazą danych
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);


// Sprawdzenie czy udało się połączyć z bazą danych
if (!$link) {
    echo '<b>Przerwane połączenie</b>';
}


// Wybór bazy danych
if (!mysqli_select_db($link, $baza)) {
    echo 'Nie wybrano bazy';
}
?>