<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Page</title>
    <link rel="stylesheet" href="../css/products.css">
</head>

<body>

    <?php
    session_start();
    include("../cfg.php");

    // Sprawdź czy użytkownik jest zalogowany
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        // Użytkownik nie jest zalogowany, przekieruj go na stronę logowania
        header("Location: ../admin.php");
        exit();
    }

    echo '<div class="back-btn">
            <a href="../admin">Powrót</a>
          </div>';

    // Funkcja dodająca produkt
    function dodajProdukt()
    {
        $adding = '
    <div class="add">
        <h1><b>Dodaj Produkt</b></h1>
        <form method="post" enctype="multipart/form-data">

            <label for="tytul">Podaj tytuł produktu</label>
            <input type="text" name="tytul" required />

            <label for="opis">Podaj opis produktu</label>
            <input type="text" name="opis" required />

            <label for="data_wygasniecia">Podaj datę wygaśnięcia</label>
            <input type="date" name="data_wygasniecia" required />

            <label for="cena_netto">Podaj cenę netto</label>
            <input type="number" name="cena_netto" required />

            <label for="podatek_vat">Podaj wysokość podatku VAT produktu</label>
            <input type="number" name="podatek_vat" required />

            <label for="ilosc_dostepnych_sztuk">Podaj ilość dostępnych sztuk w magazynie</label>
            <input type="number" name="ilosc_dostepnych_sztuk" required/>

            <label for="kategoria">Podaj kategorię</label>
            <input type="text" name="kategoria" required/>
            
            <label for="gabaryt">Podaj gabaryt produktu</label>
            <input type="text" name="gabaryt" required/>

            <label for="zdjecie">Podaj zdjęcie</label>
            <input type="file" name="zdjecie" accept="image/*" required />

            <input type="submit" name="Dodaj" class = "dodaj" value="Dodaj" />
        </form>
    </div>';

        echo $adding;

        global $link;
        if (isset($_POST['Dodaj'])) {
            $tytul = mysqli_real_escape_string($link, $_POST['tytul']);
            $opis = mysqli_real_escape_string($link, $_POST['opis']);
            $data_wygasniecia = $_POST['data_wygasniecia'];
            $cena_netto = $_POST['cena_netto'];
            $podatek_vat = $_POST['podatek_vat'];
            $ilosc_dostepnych_sztuk = $_POST['ilosc_dostepnych_sztuk'];
            $kategoria = mysqli_real_escape_string($link, $_POST['kategoria']);
            $gabaryt = mysqli_real_escape_string($link, $_POST['gabaryt']);

            $zdjecie = $_FILES['zdjecie']['name'];
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($zdjecie);
            move_uploaded_file($_FILES['zdjecie']['tmp_name'], $target_file);

            $result = mysqli_query($link, "SELECT MAX(id) AS max_id FROM products");
            $row = mysqli_fetch_assoc($result);
            $newId = $row['max_id'] + 1;

            $query = "INSERT INTO products (id, tytul, opis, data_utworzenia, 
        data_modyfikacji, data_wygasniecia, cena_netto, podatek_vat, ilosc_dostepnych_sztuk, 
        kategoria, gabaryt, zdjecie) VALUES ('$newId', '$tytul', '$opis', NOW(), NOW(),
        '$data_wygasniecia', '$cena_netto', '$podatek_vat', '$ilosc_dostepnych_sztuk', 
        '$kategoria', '$gabaryt', '$zdjecie')";
            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Pomyślnie dodano produkt!";
                echo "<script>window.location.href='products.php';</script>";
                exit();
            } else {
                echo "Błąd podczas dodawania produktu: " . mysqli_error($link);
            }
        }
    }

    // Funkcja usuwająca produkt
    function usunProdukt()
    {
        $deleting = '
    <div class="add">
        <h1><b>Usuń Produkt</b></h1>
        <form method="post" enctype="multipart/form-data">

            <label for="id">Podaj id produktu</label>
            <input type="text" name="id" required />

            <input type="submit" name="Usuń" class = "delete" value="Usuń" />
        </form>
    </div>';

        echo $deleting;

        global $link;
        if (isset($_POST['Usuń'])) {
            $id = mysqli_real_escape_string($link, $_POST['id']);
            $queryDelete = "DELETE FROM products WHERE id = '$id' LIMIT 1";
            $resultDelete = mysqli_query($link, $queryDelete);

            if ($resultDelete) {
                echo '<center>Produkt został pomyślnie usunięty.<center>';
                echo "<script>window.location.href='products.php';</script>";
            } else {
                echo '<center>Błąd podczas usuwania produktu: ' . mysqli_error($link) . '<center>';
            }
        }
        if (isset($_GET['funkcja']) && $_GET['funkcja'] == 'usun' && isset($_GET['id'])) {
            global $link;

            $id = mysqli_real_escape_string($link, $_GET['id']);

            $queryDelete = "DELETE FROM products WHERE id = '$id' LIMIT 1";
            $resultDelete = mysqli_query($link, $queryDelete);

            if ($resultDelete) {
                echo '<center>Produkt został pomyślnie usunięty.<center>';
                echo "<script>window.location.href='products.php';</script>";
                exit();
            } else {
                echo '<center>Błąd podczas usuwania produktu: ' . mysqli_error($link) . '<center>';
            }
        }
    }

    // Funkcja edytująca produkt
    function edytujProdukt()
    {
        $editing = '
    <div class="edit">
        <h1><b>Edytuj Produkt</b></h1>
        <form method="post" enctype="multipart/form-data">

            <label for="id">Podaj id produktu</label>
            <input type="text" name="id" required />

            <label for="tytul">Podaj tytuł produktu</label>
            <input type="text" name="tytul" required />

            <label for="opis">Podaj opis produktu</label>
            <input type="text" name="opis" required />

            <label for="data_wygasniecia">Podaj datę wygaśnięcia</label>
            <input type="date" name="data_wygasniecia" required />

            <label for="cena_netto">Podaj cenę netto</label>
            <input type="number" name="cena_netto" required />

            <label for="podatek_vat">Podaj wysokość podatku VAT produktu</label>
            <input type="number" name="podatek_vat" required />

            <label for="ilosc_dostepnych_sztuk">Podaj ilość dostępnych sztuk w magazynie</label>
            <input type="number" name="ilosc_dostepnych_sztuk" required/>

            <label for="kategoria">Podaj kategorię</label>
            <input type="text" name="kategoria" required/>
            
            <label for="gabaryt">Podaj gabaryt produktu</label>
            <input type="text" name="gabaryt" required/>

            <label for="zdjecie">Podaj zdjęcie</label>
            <input type="file" name="zdjecie" accept="image/*" required />

            <input type="submit" name="edytuj" class = "edycja" value="Edytuj" />
        </form>
    </div>';
        echo $editing;

        if (isset($_POST['edytuj'])) {
            global $link;
            $id = $_POST['id'];
            $tytul = $_POST['tytul'];
            $opis = $_POST['opis'];
            $data_wygasniecia = $_POST['data_wygasniecia'];
            $cena_netto = $_POST['cena_netto'];
            $podatek_vat = $_POST['podatek_vat'];
            $ilosc_dostepnych_sztuk = $_POST['ilosc_dostepnych_sztuk'];
            $kategoria = $_POST['kategoria'];
            $gabaryt = $_POST['gabaryt'];

            $zdjecie = $_FILES['zdjecie']['name'];
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($zdjecie);
            move_uploaded_file($_FILES['zdjecie']['tmp_name'], $target_file);

            $query = "SELECT * FROM products WHERE id = '$id' LIMIT 1";
            $result = mysqli_query($link, $query);

            if (mysqli_num_rows($result) > 0) {
                $data_mod = date('Y-m-d');
                $query = "UPDATE products SET tytul = '$tytul', opis = '$opis',
            data_wygasniecia='$data_wygasniecia', cena_netto='$cena_netto', podatek_vat='$podatek_vat', 
            ilosc_dostepnych_sztuk='$ilosc_dostepnych_sztuk', kategoria='$kategoria',
            gabaryt='$gabaryt', zdjecie='$zdjecie', data_modyfikacji='$data_mod' WHERE id = '$id' LIMIT 1";

                $result = mysqli_query($link, $query);
                if ($result) {
                    echo "<script>window.location.href='products.php';</script>";
                    exit();
                } else {
                    echo "<center>Błąd podczas edycji: " . mysqli_error($link) . "</center>";
                }
            } else {
                echo '<center>Nie istnieje produkt o takim id ' . $id . '!</center>';
                exit();
            }
        }
    }

    // Funkcja wyświetlająca produkty
    function pokazProdukty()
    {
        echo '<h1>Lista produktów:</h1>
    <center><table>
    <tr class="cols"><th class="cols">Id</th>
    <th class="cols">Tytuł</th>
    <th class="cols">Opis</th>
    <th class="cols">Data utworzenia</th>
    <th class="cols">Data modyfikacji</th>
    <th class="cols">Data wygaśnięcia</th>
    <th class="cols">Cena netto</th>
    <th class="cols">Podatek VAT</th>
    <th class="cols">Ilość sztuk w magazynie</th>
    <th class="cols">Status dostępności</th>
    <th class="cols">Kategoria</th>
    <th class="cols">Gabaryt</th>
    <th class="cols">Zdjęcie</th>
    <th class="cols">Usuń produkt</th></tr>';

        global $link;

        $query = "SELECT * FROM products ORDER BY id ASC";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) == 0) {
            echo "Brak produktów";
        } else {
            while ($row = mysqli_fetch_array($result)) {
                echo '<tr>
            <td class="tdid"><b>' . $row['id'] . '<b></td>
            <td class="tdnazwa"><b>' . $row['tytul'] . '<b></td>
            <td class="tdane">' . $row['opis'] . '</td>
            <td class="tdane">' . $row['data_utworzenia'] . '</td>
            <td class="tdane">' . $row['data_modyfikacji'] . '</td>
            <td class="tdane">' . $row['data_wygasniecia'] . '</td>
            <td class="tdane">' . $row['cena_netto'] . '</td>
            <td class="tdane">' . $row['podatek_vat'] . '</td>
            <td class="tdane">' . $row['ilosc_dostepnych_sztuk'] . '</td>
            <td class="tdane">' . $row['status_dostepnosci'] . '</td>
            <td class="tdane">' . $row['kategoria'] . '</td>
            <td class="tdane">' . $row['gabaryt'] . '</td>
            <td class="tdane"><img src="uploads/' . $row['zdjecie'] . '" alt="Product Image" style="max-width: 100px; max-height: 100px;"></td>
            <td class="tdusun"><a href="products.php?funkcja=usun&id=' . $row['id'] . '"><b>Usuń</b></a></td>

        </tr>';
            }
            echo '</table></center><br>';
        }
    }
    dodajProdukt();
    usunProdukt();
    edytujProdukt();
    pokazProdukty();

    ?>
</body>

</html>