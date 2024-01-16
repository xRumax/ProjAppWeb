<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

    <?php
    // Rozpoczęcie sesji i załączenie pliku konfiguracyjnego
    session_start();
    include('../cfg.php');

    echo '<div class="back-btn">
        <a href="../admin">Powrót</a>
    </div>';

    // Sprawdzenie czy żądanie zostało wysłane metodą POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Sprawdzenie czy pola email i hasło są ustawione w formularzu   
        if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
            $user_login = $_POST['login_email'];
            $user_pass = $_POST['login_pass'];

            // Sprawdzenie poprawności danych logowania
            if ($user_login == $login && $user_pass == $pass) {

                $_SESSION['logged_in'] = true;
            } else {
                $error_message = "Błędne dane logowania. Spróbuj ponownie.";
            }
        }
    }

    // Jeśli użytkownik jest zalogowany, wyświetl panel administracyjny
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

        // Funkcja do wyświetlenia listy podstron
        function ListaPodstron()
        {
            global $link;

            // Zapytanie do bazy danych o listę podstron
            $query = "SELECT * FROM page_list ORDER BY id LIMIT 100";
            $result = mysqli_query($link, $query);

            // Wyświetlenie wyników w tabeli
            if ($result) {
                echo '<table class="page-list">';
                echo '<tr><th>ID</th><th>Tytuł</th></tr>';

                while ($row = mysqli_fetch_array($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['page_title'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo "Błąd zapytania: " . mysqli_error($link);
            }
        }

        // Funkcja do wylogowania użytkownika
        echo '<button style="float:right"><a href="?action=logout" style="text-decoration: none;">Wyloguj</a></button>';

        // Wywołanie funkcji do wyświetlenia listy podstron
        ListaPodstron();

        // Wyświetlenie formularzy do edycji, dodawania i usuwania podstron
        echo FormularzDodawania();
        DodajNowaPodstrone();
        echo FormularzEdycji();
        EdytujPodstrone();
        echo FormularzUsuwania();
        UsunPodstrone();
    } else {
        // Wyświetlenie formularza logowania
        if (isset($error_message)) {
            echo '<p style="color:red;">' . $error_message . '</p>';
        }
        echo FormularzLogowania();
    }

    // Wylogowanie użytkownika
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        session_destroy();
        header("Location: admin.php");
        exit();
    }

    // Funkcja generująca formularz logowania
    function FormularzLogowania()
    {
        $form = '
        <div class="logowanie">
            <h1>Logowanie:</h1>
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table>
                    <tr><td><label for="login_email">Login:</label></td><td><input type="text" name="login_email" /></td></tr>
                    <tr><td><label for="login_pass">Hasło:</label></td><td><input type="password" name="login_pass" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" value="Zaloguj" /></td></tr>
                </table>
            </form>
        </div>
    ';

        return $form;
    }

    // Funkcja generująca formularz edycji podstrony
    function FormularzEdycji()
    {
        $edit = '
    <div class="edycja" >
        <h1 class="heading"><b>Edytuj podstronę<b/></h1>
        <div class="edycja">
            <form method="post" name="EditForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="edycja">
                    <tr><td class="edit_4t"><b>Id podstrony: <b/></td><td><input type="text" name="id_strony" class="edycja" /></td></tr>
                    <tr><td class="edit_4t"><b>Tytuł podstrony: <b/></td><td><input type="text" name="page_title" class="edycja" /></td></tr>
                    <tr><td class="edit_4t"><b>Treść podstrony: <b/></td><td><input type="text" name="page_content" class="edycja" /></td></tr>
                    <tr><td class="edit_4t"><b>Status podstrony: <b/></td><td><input type="checkbox" name="status" class="edycja" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="edytor" class="edycja" value="Zmień" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

        return $edit;
    }

    // Funkcja obsługująca edycję podstrony
    function EdytujPodstrone()
    {
        global $link;

        if (isset($_POST['edytor'])) {
            $id = $_POST['id_strony'];
            $tytul = $_POST['page_title'];
            $tresc = $_POST['page_content'];
            $status = isset($_POST['status']) ? 1 : 0;

            if (!empty($id)) {
                $query = "UPDATE page_list SET page_title = '$tytul', page_content = '$tresc', status = $status WHERE id = $id LIMIT 1";

                $result = mysqli_query($link, $query);

                if ($result) {
                    echo "Edycja zakończona pomyślnie!";
                    header("Location: admin.php");
                    exit();
                } else {
                    echo "Błąd podczas edycji: " . mysqli_error($link);
                }
            }
        }
    }

    // Funkcja generująca formularz dodawania nowej podstrony
    function FormularzDodawania()
    {
        $add = '
    <div class="dodaj">
        <h1 class="heading"><b>Dodaj podstronę<b/></h1>
        <div class="dodaj">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="dodaj">
                    <tr><td class="add_4t"><b>Tytuł podstrony: <b/></td><td><input type="text" name="page_title_add" class="dodaj" /></td></tr>
                    <tr><td class="add_4t"><b>Treść podstrony: <b/></td><td><input type="text" name="page_content_add" class="dodaj" /></td></tr>
                    <tr><td class="add_4t"><b>Status podstrony: <b/></td><td><input type="checkbox" name="status_add" class="dodaj" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="Dodawanie" class="dodaj" value="Dodaj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

        return $add;
    }

    // Funkcja obsługująca dodawanie nowej podstrony
    function DodajNowaPodstrone()
    {
        global $link;
        if (isset($_POST['Dodawanie'])) {
            $tytul = $_POST['page_title_add'];
            $tresc = $_POST['page_content_add'];
            $status = isset($_POST['status_add']) ? 1 : 0;

            $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$tresc', $status)";
            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Pomyślnie dodano podstronę!";
                header('Location: admin.php');
                exit();
            } else {
                echo "Błąd podczas dodawania podstrony: " . mysqli_error($link);
            }
        }
    }

    // Funkcja generująca formularz usuwania podstrony
    function FormularzUsuwania()
    {
        $remove = '
    <div class="usun">
        <h1 class="heading"><b>Usuń podstronę<b/></h1>
        <div class="usun">
            <form method="post" name="DeleteForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="usun">
                    <tr><td class="rem_4t"><b>Id podstrony: <b/></td><td><input type="text" name="id_remove" class="usun" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="Usuwanie" class="usun" value="Usuń" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

        return $remove;
    }

    // Funkcja obsługująca usuwanie podstrony
    function UsunPodstrone()
    {
        global $link;
        if (isset($_POST['Usuwanie'])) {
            $id = $_POST['id_remove'];

            $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";
            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Pomyślnie usunięto podstronę!";
                header("Location: admin.php");
                exit();
            } else {
                echo "Błąd podczas usuwania podstrony: " . mysqli_error($link);
            }
        }
    }
    ?>

</body>

</html>