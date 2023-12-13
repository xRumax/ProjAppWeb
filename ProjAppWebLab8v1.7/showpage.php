<?php

#include('html/TOP10.html');
include("cfg.php");

function PokazPodstrone($id) {
    global $link;

    $id_clear = htmlspecialchars($id);

    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);

    if(empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }
    return $web;
}
// $id_strony = 2; // Przykładowe ID strony
// $zawartosc_strony = PokazPodstrone($id_strony);
// echo $zawartosc_strony;

?>