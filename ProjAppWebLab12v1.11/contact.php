<?php
include('cfg.php');

// Funkcja generująca formularz przypomnienia hasła
function Zapomniane_haslo()
{
    global $adminEmail;

    if (isset($_POST['x5_submit'])) {
        $email = $_POST['emailf'];

        if ($email == $adminEmail) {
            $password = PrzypomnijHaslo();
            if ($password) {
                echo "Twoje hasło to: $password";
            } else {
                echo "[blad_wysylania]";
            }
        } else {
            echo 'Nie ma takiego użytkownika';
        }
    }

    $wynik = '
    <div class="Zapomniane_haslo">
        <h1 class="heading">Zapomniałeś Hasła?</h1>
        <div class="formularzZapomniane">
            <form method="post" name="mail" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="formularz">
                    <tr><td class="for4_t">email:</td><td><input type="text" name="emailf" class="formularzZapomniane" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x5_submit" class="formularzZapomniane" value="wyslij" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $wynik;
}

echo Zapomniane_haslo();
// Funkcja przypominająca hasło
function PrzypomnijHaslo()
{
    global $pass;

    $newPassword = isset($pass) ? $pass : null;

    return $newPassword;
}


?>