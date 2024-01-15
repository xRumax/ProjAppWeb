<?php
include("../cfg.php");

function dodajProdukt()
{
    $adding = '
    <div class="add">
        <h1><b>Dodaj Produkt</b></h1>
        <form method="post" enctype="multipart/form-data">

            <label for="tytuł">Podaj tytuł produktu</label>
            <input type="text" name="tytuł" required />

            <label for="opis">Podaj opis produktu</label>
            <input type="text" name="opis" required />

            <label for="data_utworzenia">Podaj datę utworzenia</label>
            <input type="date" name="data_utworzenia" required />
            
            <label for="data_modyfikacji">Podaj datę modyfikacji</label>
            <input type="date" name="data_modyfikacji" required />

            <label for="data_wygaśnięcia">Podaj datę wygaśnięcia</label>
            <input type="date" name="data_wygaśnięcia" required />

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

            <input type="submit" name="dodaj" value="Dodaj" />
        </form>
    </div>';

    echo $adding;

    global $link;
    if (isset($_POST['dodaj'])) {
        $tytul = mysqli_real_escape_string($link, $_POST['tytuł']);
        $opis = mysqli_real_escape_string($link, $_POST['opis']);
        $data_utworzenia = $_POST['data_utworzenia'];
        $data_modyfikacji = $_POST['data_modyfikacji'];
        $data_wygasniecia = $_POST['data_wygaśnięcia'];
        $cena_netto = $_POST['cena_netto'];
        $podatek_vat = $_POST['podatek_vat'];
        $ilosc_dostepnych_sztuk = $_POST['ilosc_dostepnych_sztuk'];
        $kategoria = mysqli_real_escape_string($link, $_POST['kategoria']);
        $gabaryt = mysqli_real_escape_string($link, $_POST['gabaryt']);

        // Handling file upload
        $zdjecie = $_FILES['zdjecie']['name'];
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($zdjecie);
        move_uploaded_file($_FILES['zdjecie']['tmp_name'], $target_file);

        // Pobierz najwyższe ID produktu z bazy danych
        $result = mysqli_query($link, "SELECT MAX(id) AS max_id FROM products");
        $row = mysqli_fetch_assoc($result);
        $newId = $row['max_id'] + 1;

        $query = "INSERT INTO products (id, tytuł, opis, data_utworzenia, 
        data_modyfikacji, data_wygaśnięcia, cena_netto, podatek_vat, ilosc_dostepnych_sztuk, 
        kategoria, gabaryt, zdjecie) VALUES ('$newId', '$tytul', '$opis', '$data_utworzenia', '$data_modyfikacji', 
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

function pokazProdukty()
{
    echo "<h1>Lista produktów:</h1>";
    global $link;

    $query = "SELECT * FROM products ORDER BY id DESC";
    $result = mysqli_query($link, $query);

    while ($row = mysqli_fetch_array($result)) {
        echo "Product ID: " . $row['id'] . "<br>";
        echo "Product Title: " . $row['tytuł'] . "<br>";
        echo "Product Description: " . $row['opis'] . "<br>";
        echo "Product Price: " . $row['cena_netto'] . "<br>";

        // Display product photo
        echo "<div style='text-align: left;'>";
        echo "<strong>Product Photo</strong><br>";
        echo "<img src='uploads/" . $row['zdjecie'] . "' alt='Product Photo' width='250' height='150'>";
        echo "</div>";

        echo "-------------------------<br>";
    }
}

pokazProdukty();
dodajProdukt();
?>