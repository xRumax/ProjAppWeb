<?php
include('cfg.php');


function PokazKontakt()
{
    $form = '
        <form method="post" action="">
        <h1>Formularz Kontaktowy</h1>
            <label for="temat">Temat:</label>
            <input type="text" name="temat" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="tresc">Treść:</label>
            <textarea name="tresc" required></textarea>

            <input type="submit" name="submit_contact" value="Wyślij">
        </form>
    ';

    return $form;
}

function WyslijMailKontakt($odbiorca)
{
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '[nie_wypelniles_pola]';
        echo PokazKontakt();
    } else {
        $mail['subject'] = $_POST['temat'];
        $mail['body'] = $_POST['tresc'];
        $mail['sender'] = $_POST['email'];
        $mail['reciptient'] = $odbiorca;

        $header = "From: formularz kontaktowy <" . $mail['sender'] . ">\n";
        $header = "MIME-version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding:BASE64";
        $header = "X-Sender: <" . $mail['sender'] . ">\n";
        $header = "X-Mailer: PRapWWW mail 1.2\n";
        $header = "X-Priority:3\n";
        $header = "Return-Path <" . $mail['sender'] . ">\n";

        mail($mail['reciptient'], $mail['subject'], $mail['body'], $header);

        echo '[wiadomosc_wyslana]';
    }
}


echo PokazKontakt();

function Zapomniane_haslo()
{
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

function PrzypomnijHaslo($adminEmail)
{
    $subject = "Przypomnienie hasła";
    $body = "Twoje hasło to: 123456";

    $adminRecipient = $adminEmail;

    if (WyslijMailKontakt($adminRecipient)) {
        echo '[wiadomosc_wyslana]';
    } else {
        echo '[blad_wysylania]';
    }
}

?>