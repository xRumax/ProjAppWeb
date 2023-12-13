<?php
session_start();

include('../cfg.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
        $user_login = $_POST['login_email'];
        $user_pass = $_POST['login_pass'];


        if ($user_login == $login && $user_pass == $pass) {

            $_SESSION['logged_in'] = true;
        } else {
            $error_message = "Błędne dane logowania. Spróbuj ponownie.";
        }
    }
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

    function ListaPodstron()
    {
        global $link;
        $query = "SELECT * FROM page_list ORDER BY id LIMIT 100";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo '<table>';
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

    echo '<button style="float:right"><a href="?action=logout" style="text-decoration: none;">Wyloguj</a></button>';

    ListaPodstron();

    echo '<p style="font-size:20px; display:flex; font-weight:bold; margin-left:40px;">Akcje:</p>';
    echo FormularzEdycji();
    EdytujPodstrone();
    echo FormularzDodawania();
    DodajNowaPodstrone();
    echo FormularzUsuwania();
    UsunPodstrone();
} else {

    if (isset($error_message)) {
        echo '<p style="color:red;">' . $error_message . '</p>';
    }
    echo FormularzLogowania();
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: admin.php");
    exit();
}

function FormularzLogowania()
{
    $form = '
        <div class="logowanie">
            <h1 class="heading" style="display:flex; align-items: center; justify-content:center;">Logowanie:</h1>
            <div class="logowanie">
                <form style="display:flex; align-items: center; justify-content:center;" method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
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