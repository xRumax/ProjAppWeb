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

        $query = "INSERT INTO shop (nazwa, matka) VALUES ('$nazwa', '$matka')";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Pomyślnie dodano podstronę!";
            header('Location: shop.php');
            exit();
        } else {
            echo "Błąd podczas dodawania podstrony: " . mysqli_error($link);
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
                    <tr><td class="edit_4t"><b>Id kategorii: <b/></td><td><input type="text" name="id_category" class="edycja" /></td></tr>
                    <tr><td class="edit_4t"><b>Matka kategorii: <b/></td><td><input type="text" name="category_mother" class="edycja" /></td></tr>
                    <tr><td class="edit_4t"><b>Nazwa kategorii: <b/></td><td><input type="text" name="category_name" class="edycja" /></td></tr>
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
        $id = $_POST['id_category'];
        $matka = $_POST['category_mother'];
        $name = $_POST['category_name'];

        if (!empty($id)) {
            $query = "UPDATE shop SET category_name = '$name', category_mother = '$matka', WHERE id = $id LIMIT 1";

            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Edycja zakończona pomyślnie!";
                header("Location: shop.php");
                exit();
            } else {
                echo "Błąd podczas edycji: " . mysqli_error($link);
            }
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

    $query = "SELECT id FROM sklep WHERE matka = '$id'";
    $result = mysqli_query($link, $query);
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            UsunKategorie($row['id']);
        }
    }

    $query1 = "DELETE FROM sklep WHERE id = '$id' LIMIT 1";
    $result1 = mysqli_query($link, $query1);
    if (!$result1) {
        echo '<center>Błąd<br><center>';
    }
}


// function ListaKategorii($mother = 0, $ile = 0)
// {
//     global $link;


//     $query = "SELECT * FROM sklep WHERE matka = '$mother'";
//     $result = mysqli_query($link, $query);
//     if ($result) {  
//         $brak = 0;
//         while ($row = mysqli_fetch_array($result)) {
//             $brak = 1;
//             for ($i = 0; $i < $ile; $i++) {
//                 echo '&nbsp;&nbsp;&nbsp;<span style="color: #0000FF;">>>>>></span>';
//             }
//             echo ' <b><span style="color:#008000;">' . $row['id'] . '</span> ' . $row['nazwa'] . '</b><br><br>';
//             ListaKategorii($row['id'], $ile + 1);
//         }
//         if ($brak == 0 && $ile == 0) {
//             echo "</center><b>Brak kategorii<b/></center>";
//         }
//     }
// }

function ListaKategorii()
{
    global $link;

    $query = "SELECT * FROM shop WHERE matka = 0";
    $result = mysqli_query($link, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<h2>' . $row['nazwa'] . '</h2>';
            WyswietlPodkategorie($row['id']);
        }
    } else {
        echo "Błąd zapytania: " . mysqli_error($link);
    }
}

// Funkcja wyświetlająca podkategorie (dzieci)
function WyswietlPodkategorie($motherId)
{
    global $link;

    $query = "SELECT * FROM shop WHERE mother_id = $motherId";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<ul>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>' . $row['nazwa'] . '</li>';
            // Rekurencyjne wyświetlenie podkategorii dla aktualnej kategorii
            WyswietlPodkategorie($row['id']);
        }
        echo '</ul>';
    }
}



ListaKategorii();


echo FormularzDodawania();
DodajNowaKategorie();
echo FormularzEdycji();
EdytujKategorie();
echo FormularzUsuwania();
?>