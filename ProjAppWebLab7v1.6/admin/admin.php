<?php
session_start(); // Rozpoczęcie sesji

include('../cfg.php');

// Sprawdzenie czy formularz logowania został wysłany
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdzenie poprawności danych logowania
    if(isset($_POST['login_email']) && isset($_POST['login_pass'])) {
        $user_login = $_POST['login_email'];
        $user_pass = $_POST['login_pass'];

        // Sprawdzenie czy dane logowania są poprawne
        if($user_login == $login && $user_pass == $pass) {
            // Ustawienie zmiennej sesyjnej oznaczającej zalogowanie
            $_SESSION['logged_in'] = true;
        } else {
            $error_message = "Błędne dane logowania. Spróbuj ponownie.";
        }
    }
}

// Obsługa wylogowywania
if(isset($_GET['action']) && $_GET['action'] == 'logout') {
    // Zniszczenie sesji
    session_destroy();
    // Przekierowanie na stronę logowania
    header("Location: admin.php");
    exit();
}

// Sprawdzenie czy użytkownik jest zalogowany
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    // Użytkownik jest zalogowany, można wykonywać operacje administracyjne

    // Metoda ListaPodstron()
    function ListaPodstron() {
        global $link;
        $query = "SELECT * FROM page_list ORDER BY id LIMIT 100";
        $result = mysqli_query($link, $query);

        if($result) {
            echo '<table>';
            echo '<tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>';

            while($row = mysqli_fetch_array($result)) {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['page_title'].'</td>';
                echo '<td><button><a href="edytuj_podstrone.php?id='.$row['id'].'" style="text-decoration:none;">Edytuj</a></button>  <button><a href="usun_podstrone.php?id='.$row['id'].'" style="text-decoration:none;">Usuń</a></td></button>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "Błąd zapytania: ".mysqli_error($link);
        }
    }

    // Przycisk wylogowywania
    echo '<button style="float:right"><a href="?action=logout" style="text-decoration: none;">Wyloguj</a></button>';

    // Wywołanie metody ListaPodstron()
    ListaPodstron();
} else {
    // Użytkownik nie jest zalogowany, wyświetl formularz logowania
    if(isset($error_message)) {
        echo '<p style="color:red;">'.$error_message.'</p>';
    }
    echo FormularzLogowania();
}


function EdytujPodstrone() {
    global $link;

    // Sprawdź, czy przekazano poprawne id podstrony do edycji
    if(isset($_POST['edytuj']) && isset($_POST['id_podstrony'])) {
        $id_podstrony = mysqli_real_escape_string($link, $_POST['id_podstrony']);

        // Pobierz dane podstrony z bazy danych
        $query = "SELECT * FROM page_list WHERE id = '$id_podstrony'";
        $result = mysqli_query($link, $query);

        if($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Formularz edycji podstrony
            echo '<h2>Edycja Podstrony</h2>';
            echo '<form method="post" action="zapisz_edycje.php">';
            echo '<input type="hidden" name="id_podstrony" value="'.$id_podstrony.'">';

            // Pole dla tytułu
            echo '<label for="tytul">Tytuł:</label>';
            echo '<input type="text" name="tytul" value="'.htmlspecialchars($row['page_title']).'" required>';

            // Pole dla treści strony
            echo '<label for="tresc">Treść:</label>';
            echo '<textarea name="tresc" rows="8" required>'.htmlspecialchars($row['page_content']).'</textarea>';

            // Pole checkbox dla aktywności strony
            echo '<label>Aktywna: <input type="checkbox" name="aktywna" '.($row['active'] ? 'checked' : '').'></label>';

            // Przycisk zapisu
            echo '<input type="submit" name="zapisz" value="Zapisz">';
            echo '</form>';
        } else {
            echo "Błąd zapytania: ".mysqli_error($link);
        }
    }
}


// Metoda FormularzLogowania()
function FormularzLogowania() {
    $form = '
        <div class="logowanie">
            <h1 class="heading" style="display:flex; align-items: center; justify-content:center;">Logowanie:</h1>
            <div class="logowanie">
                <form style="display:flex; align-items: center; justify-content:center;" method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                    <table class="logowanie">
                        <tr><td class="log4_t" style="color: #44aaaa;">Login:</td><td><input style="border: 1px solid #44aaaa;" type="text" name="login_email" class="logowanie" /></td></tr>
                        <tr><td class="log4_t" style="color: #44aaaa;">Hasło:</td><td><input style="border: 1px solid #44aaaa;" type="password" name="login_pass" class="logowanie" /></td></tr>
                        <tr><td>&nbsp;</td><td><input type="submit" style="background-color: #ffffff; color: #44aaaa; border: 1px solid #44aaaa; border-radius:5%" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
                    </table>
                </form>
            </div>
        </div>
    ';

    return $form;
}
?>