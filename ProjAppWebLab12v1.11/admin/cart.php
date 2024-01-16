<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <link rel="stylesheet" href="../css/cart.css">
</head>

<body>

    <?php
    session_start();
    include("../cfg.php");

    echo '<div class="back-btn">
            <a href="../admin">Powrót</a>
          </div>';


    // Funkcja wyświetlająca produkty w formie tabeli
    function pokazProdukty()
    {
        echo '<h1>Lista produktów:</h1>
        <center><table>
        <tr class="cols">
            <th class="cols">Id</th>
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
            <th class="cols">Akcje</th></tr>';

        global $link;

        $query = "SELECT * FROM products ORDER BY id DESC";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) == 0) {
            echo "Brak produktów";
        } else {
            while ($row = mysqli_fetch_array($result)) {
                $cenaNetto = $row['cena_netto'];
                $podatekVat = $row['podatek_vat'];
                $cenaBrutto = $cenaNetto + ($cenaNetto * $podatekVat / 100);

                echo '<tr>
                    <td class="tdid"><b>' . $row['id'] . '<b></td>
                    <td class="tdnazwa"><b>' . $row['tytul'] . '<b></td>
                    <td class="tdane">' . $row['opis'] . '</td>
                    <td class="tdane">' . $row['data_utworzenia'] . '</td>
                    <td class="tdane">' . $row['data_modyfikacji'] . '</td>
                    <td class="tdane">' . $row['data_wygasniecia'] . '</td>
                    <td class="tdane">' . $cenaNetto . '</td>
                    <td class="tdane">' . $podatekVat . '</td>
                    <td class="tdane">' . $row['ilosc_dostepnych_sztuk'] . '</td>
                    <td class="tdane">' . $row['status_dostepnosci'] . '</td>
                    <td class="tdane">' . $row['kategoria'] . '</td>
                    <td class="tdane">' . $row['gabaryt'] . '</td>
                    <td class="tdane"><img src="uploads/' . $row['zdjecie'] . '" alt="Product Image" style="max-width: 100px; max-height: 100px;"></td>
                    <td class="tddodaj">
                        <a href="cart.php?funkcja=dodajDoKoszyka&id=' . $row['id'] . '"><b>Dodaj do koszyka</b></a>
                    </td>
                </tr>';
            }
            echo '</table></center><br>';
        }
    }

    // Obsługa dodawania do koszyka
    if (isset($_GET['funkcja']) && $_GET['funkcja'] == 'dodajDoKoszyka' && isset($_GET['id'])) {
        $idProduktuDoDodaniaDoKoszyka = $_GET['id'];
        session_start();

        if (!isset($_SESSION['koszyk'])) {
            $_SESSION['koszyk'] = array();
        }
        $isProductInCart = false;

        foreach ($_SESSION['koszyk'] as &$item) {
            if ($item['id'] == $idProduktuDoDodaniaDoKoszyka) {
                $item['ilosc']++;
                $isProductInCart = true;
                break;
            }
        }

        if (!$isProductInCart) {
            array_push($_SESSION['koszyk'], array('id' => $idProduktuDoDodaniaDoKoszyka, 'ilosc' => 1));
        }
        echo "<script>window.location.href='cart.php';</script>";
        exit();
    }

    // Funkcja wyświetlająca zawartość koszyka
    function pokazKoszyk()
    {
        echo '<h1>Koszyk:</h1>';
        echo '<center><table>
    <tr class="cols">
        <th class="cols">Id</th>
        <th class="cols">Tytuł</th>
        <th class="cols">Cena brutto</th>
        <th class="cols">Ilość</th>
        <th class="cols" colspan = 3>Akcje</th>

    </tr>';

        // Warunek sprawdzający, czy koszyk jest ustawiony w sesji
        if (isset($_SESSION['koszyk']) && !empty($_SESSION['koszyk'])) {
            $koszyk = $_SESSION['koszyk'];
            global $link;

            $produktyWKoszyku = array_column($koszyk, 'id');

            // Warunek sprawdzający, czy są produkty w koszyku, aby uniknąć pustego zapytania SQL
            if (!empty($produktyWKoszyku)) {
                $produktyWKoszykuIds = array_map('intval', $produktyWKoszyku);
                $inClause = implode(',', $produktyWKoszykuIds);
                $query = "SELECT * FROM products WHERE id IN ($inClause)";
                $result = mysqli_query($link, $query);

                if ($result) {
                    while ($row = mysqli_fetch_array($result)) {
                        $idProduktu = $row['id'];

                        $iloscProduktuWKoszyku = array_filter($koszyk, function ($item) use ($idProduktu) {
                            return $item['id'] == $idProduktu;
                        });

                        if (!empty($iloscProduktuWKoszyku)) {
                            $iloscProduktuWKoszyku = current($iloscProduktuWKoszyku)['ilosc'];
                            $cenaNetto = $row['cena_netto'];
                            $podatekVat = $row['podatek_vat'];
                            $cenaBrutto = $cenaNetto + ($cenaNetto * $podatekVat / 100);

                            echo '<tr>
                                <td class="tdid"><b>' . $idProduktu . '<b></td>
                                <td class="tdnazwa"><b>' . $row['tytul'] . '<b></td>
                                <td class="tdane">' . $cenaBrutto . '</td>
                                <td class="tdane">' . $iloscProduktuWKoszyku . '</td>
                                <td class="tdminus"><a href="cart.php?funkcja=zmniejszIlosc&id=' . $idProduktu . '"><b>-</b></a></td>
                                <td class="tdplus"><a href="cart.php?funkcja=zwiekszIlosc&id=' . $idProduktu . '"><b>+</b></a>
                                <td class="tdakcje"><a href="cart.php?funkcja=usunZKoszyka&id=' . $idProduktu . '"><b>Usuń</b></a></td>
                                </tr>';
                        }
                    }
                } else {
                    echo "Error: " . mysqli_error($link);
                }
            }
        } else {
            echo '<tr><td colspan="5">Koszyk jest pusty</td></tr>';
        }

        echo '</table></center>';
    }

    // Funkcja usuwająca produkt z koszyka
    function usunZKoszyka()
    {
        if (isset($_GET['funkcja']) && $_GET['funkcja'] == 'usunZKoszyka' && isset($_GET['id'])) {
            $idProduktuDoUsuniecia = $_GET['id'];

            // Warunek sprawdzający, czy koszyk jest ustawiony w sesji
            if (isset($_SESSION['koszyk']) && !empty($_SESSION['koszyk'])) {
                $koszyk = $_SESSION['koszyk'];

                $koszyk = array_filter($koszyk, function ($item) use ($idProduktuDoUsuniecia) {
                    return $item['id'] != $idProduktuDoUsuniecia;
                });

                $_SESSION['koszyk'] = array_values($koszyk);
            }
            echo "<script>window.location.href='cart.php';</script>";
            exit();
        }
    }

    if (isset($_GET['funkcja']) && $_GET['funkcja'] == 'zmniejszIlosc' && isset($_GET['id'])) {
        $idProduktu = $_GET['id'];

        // Warunek sprawdzający, czy koszyk jest ustawiony w sesji
        if (isset($_SESSION['koszyk']) && !empty($_SESSION['koszyk'])) {
            $koszyk = $_SESSION['koszyk'];

            foreach ($koszyk as &$item) {
                if ($item['id'] == $idProduktu) {
                    $item['ilosc']--;

                    if ($item['ilosc'] <= 0) {
                        // Jeśli ilość osiągnęła zero, usuń produkt z koszyka
                        $koszyk = array_filter($koszyk, function ($item) use ($idProduktu) {
                            return $item['id'] != $idProduktu;
                        });
                    }

                    $_SESSION['koszyk'] = array_values($koszyk);
                    break;
                }
            }
        }
        echo "<script>window.location.href='cart.php';</script>";
        exit();
    }

    if (isset($_GET['funkcja']) && $_GET['funkcja'] == 'zwiekszIlosc' && isset($_GET['id'])) {
        $idProduktu = $_GET['id'];

        // Warunek sprawdzający, czy koszyk jest ustawiony w sesji
        if (isset($_SESSION['koszyk']) && !empty($_SESSION['koszyk'])) {
            $koszyk = $_SESSION['koszyk'];

            foreach ($koszyk as &$item) {
                if ($item['id'] == $idProduktu) {
                    $item['ilosc']++;
                    $_SESSION['koszyk'] = array_values($koszyk);
                    break;
                }
            }
        }

        echo "<script>window.location.href='cart.php';</script>";
        exit();
    }

    pokazProdukty();
    pokazKoszyk();
    usunZKoszyka();
    ?>
</body>

</html>