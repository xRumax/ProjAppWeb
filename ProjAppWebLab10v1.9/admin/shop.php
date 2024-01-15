<?php
include('../cfg.php');

function FormularzDodawania()
{
    $add = '
    <div class="dodaj">
        <h1 class="heading"><b>Dodaj podstronę<b/></h1>
        <div class="dodaj">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="dodaj">
                    <tr><td class="add_4t"><b>Nazwa kategorii: <b/></td><td><input type="text" name="category_name_add" class="dodaj" /></td></tr>
                    <tr><td class="add_4t"><b>Id matki: <b/></td><td><input type="text" name="id_mother_add" class="dodaj" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="Dodawanie" class="dodaj" value="Dodaj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $add;
}

function DodajNowaKategorie()
{
    global $link;
    if (isset($_POST['Dodawanie'])) {
        $nazwa = $_POST['category_name_add'];
        $matka = $_POST['id_mother_add'];

        // Sprawdź, jakie jest ostatnie id w tabeli kategorii
        $checkQuery = "SELECT MAX(id) as maxId FROM shop";
        $checkResult = mysqli_query($link, $checkQuery);
        $row = mysqli_fetch_assoc($checkResult);

        // Ustaw id na wartość ostatniego id + 1
        $newId = $row['maxId'] + 1;

        $query = "INSERT INTO shop (id, nazwa, matka) VALUES ('$newId', '$nazwa', '$matka')";

        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Pomyślnie dodano podstronę!";
            // Przekierowanie do shop.php
            echo "<script>window.location.href='shop.php';</script>";
            exit();
        }
    }
}



function FormularzEdycji()
{
    $edit = '
    <div class="edycja" >
        <h1 class="heading"><b>Edytuj Kategorię<b/></h1>
        <div class="edycja">
            <form method="post" name="EditForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="edycja">
                    <tr><td class="edit_4t"><b>Id kategorii: <b/></td><td><input type="text" name="category_id" class="edycja" /></td></tr>
                    <tr><td class="edit_4t"><b>Nazwa kategorii: <b/></td><td><input type="text" name="category_name" class="edycja" /></td></tr>
                    <tr><td class="edit_4t"><b>Matka: <b/></td><td><input type="text" name="category_mother" class="edycja" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="edytor" class="edycja" value="Zmień" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $edit;
}



function EdytujKategorie()
{
    global $link;
    if (isset($_POST['edytor'])) {
        $id = $_POST['category_id'];
        $nazwa = $_POST['category_name'];
        $matka = $_POST['category_mother'];

        $query = "SELECT * FROM shop WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        if (is_null($row)) {
            echo '<center>Nie istnieje kategoria o id ' . $id . '!</center>';
            exit();
        }

        $query = "UPDATE shop SET nazwa = '$nazwa', matka = '$matka' WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($link, $query);
        if ($result) {
            echo "<script>window.location.href='shop.php';</script>";
            exit();
        } else {
            echo "<center>Błąd podczas edycji: " . mysqli_error($link) . "</center>";
        }
    }

}

function FormularzUsuwania()
{
    $deleting = '
    <div class="delete">
        <h1><b>Usuń Kategorię</b></h1>
        <form method="post">

            <label for="id1">Podaj id</label>
            <input type="number" name="id1" required />

            <input type="submit" name="usun" value="Usuń" />

            
        </form>
    </div>';

    echo $deleting;
}

function UsunKategorie($id)
{
    global $link;

    $query = "SELECT id FROM shop WHERE matka = '$id'";
    $result = mysqli_query($link, $query);
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            UsunKategorie($row['id']);
        }
    }

    $query1 = "DELETE FROM shop WHERE id = '$id' LIMIT 1";
    $result1 = mysqli_query($link, $query1);
    if (!$result1) {
        echo '<center>Błąd<br><center>';
    }
}

function PokazKategorie($mother = 0, $ile = 0)
{
    global $link;
    $query = "SELECT * FROM shop WHERE matka = '$mother'";
    $result = mysqli_query($link, $query);
    if ($result) {
        $brak = 0;
        while ($row = mysqli_fetch_array($result)) {
            $brak = 1;
            for ($i = 0; $i < $ile; $i++) {
                echo '&nbsp;&nbsp;&nbsp;<span style="color: #0000FF;">>>>>></span>';
            }
            echo ' <b><span style="color:#008000;">' . $row['id'] . '</span> ' . $row['nazwa'] . '</b><br><br>';
            PokazKategorie($row['id'], $ile + 1);
        }
        if ($brak == 0 && $ile == 0) {
            echo "</center><b>Brak kategorii<b/></center>";
        }
    }
}





echo '<h1><b>Lista Kategorii:</b></h1>';
PokazKategorie();
echo FormularzDodawania();
DodajNowaKategorie();
echo FormularzEdycji();
EdytujKategorie();
echo FormularzUsuwania();
if (isset($_POST['usun'])) {
    $id = $_POST['id1'];
    UsunKategorie($id);
    echo "<script>window.location.href='shop.php';</script>";
    exit();
}

?>