<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Page</title>
    <link rel="stylesheet" href="../css/shop.css">
</head>

<body>

    <?php
    session_start();
    include('../cfg.php');

    // Sprawdź czy użytkownik jest zalogowany
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        // Użytkownik nie jest zalogowany, przekieruj go na stronę logowania
        header("Location: ../admin.php");
        exit();
    }

    echo '<div class="back-btn">
            <a href="../admin">Powrót</a>
          </div>';

    //Funkcja wyświeletająca formularz dodawania kategorii
    function FormularzDodawania()
    {
        $add = '
    <div class="dodaj">
        <h1 class="heading"><b>Dodaj Kategorię<b/></h1>
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

    //Funkcja obsługująca dodawanie nowej kategorii
    function DodajNowaKategorie()
    {
        global $link;
        if (isset($_POST['Dodawanie'])) {
            $nazwa = $_POST['category_name_add'];
            $matka = $_POST['id_mother_add'];

            $checkQuery = "SELECT MAX(id) as maxId FROM shop";
            $checkResult = mysqli_query($link, $checkQuery);
            $row = mysqli_fetch_assoc($checkResult);

            $newId = $row['maxId'] + 1;

            $query = "INSERT INTO shop (id, nazwa, matka) VALUES ('$newId', '$nazwa', '$matka')";

            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Pomyślnie dodano podstronę!";
                echo "<script>window.location.href='shop.php';</script>";
                exit();
            }
        }
    }


    //Funkcja generująca formularz edycji kategorii
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


    //Funkcja obsługująca edycję kategorii
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

    //Funkcja generująca formularz usuwania kategorii
    function FormularzUsuwania()
    {
        $deleting = '
    <div class="delete">
        <h1><b>Usuń Kategorię</b></h1>
        <div class ="delete">
        <form method="post">
            <div class="delete">
            <tr><td class="edit_4t"><b>Podaj id: <b/></td><td><input type="number" name="id" class="delete" /></td></tr>
            

            <input type="submit" name="usun" value="Usuń" />

            
        </form>
        </div>
    </div>
    </div>';

        echo $deleting;
    }

    //Funkcja obsługująca usuwanie kategorii
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

    //Funkcja wyświetlająca kategorie
    function PokazKategorie($mother = 0, $ile = 0)
    {
        global $link;
        $query = "SELECT * FROM shop WHERE matka = '$mother'";
        $result = mysqli_query($link, $query);

        if ($result) {
            $brak = true;

            while ($row = mysqli_fetch_array($result)) {
                $brak = false;

                for ($i = 0; $i < $ile; $i++) {
                    echo '<span class="arrow">&rarr;</span>';
                }

                echo '<b><span class="category-id">' . $row['id'] . '</span> <span class="category-name">' . $row['nazwa'] . '</span></b><br><br>';

                PokazKategorie($row['id'], $ile + 1);
            }

            if ($brak && $ile === 0) {
                echo '<div class="no-category">Brak kategorii</div>';
            }
        }
    }

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
    echo '<h1>Lista Kategorii:</h1>';
    PokazKategorie();
    ?>
</body>

</html>