<?php

#include('html/TOP10.html');
include("cfg.php");

// Funkcja do wyświetlania zawartości podstrony na podstawie jej ID
function PokazPodstrone($id)
{
    global $link; // Użycie globalnej zmiennej z połączeniem do bazy danych

    $id_clear = htmlspecialchars($id);

    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);

    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }
    return $web;
}

// Poniżej znajduje się przykładowe użycie funkcji:

// $id_strony = 2; // Przykładowe ID strony
// $zawartosc_strony = PokazPodstrone($id_strony);
// echo $zawartosc_strony;

?>