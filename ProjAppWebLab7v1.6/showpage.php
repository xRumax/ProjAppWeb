<?php

include('html/TOP10.html');
include("cfg.php");
$id_clear = htmlspecialchars($id);
function PokazPodstrone($id) {
    global $link; // Dodane, aby uzyskać dostęp do połączenia z bazą danych

    $id_clear = htmlspecialchars($id);

    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query); // Zmiana funkcji mysql_query na mysqli_query
    $row = mysqli_fetch_array($result); // Zmiana funkcji mysql_fetch_array na mysqli_fetch_array

    if(empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }
    return $web;
}
$id_strony = 2; // Przykładowe ID strony
$zawartosc_strony = PokazPodstrone($id_strony);
echo $zawartosc_strony;

?>