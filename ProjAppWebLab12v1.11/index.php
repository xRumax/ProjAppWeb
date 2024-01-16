<?php
include("cfg.php");



error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
/* po tym komentarzu będzie kod do dynamicznego ładowania stron */
if (isset($_GET['idp'])) {
  if ($_GET['idp'] == 'Główna')
    $strona = 'html/glowna.html';
  if ($_GET['idp'] == 'TOP10')
    $strona = 'html/TOP10.html';
  if ($_GET['idp'] == 'Ranking')
    $strona = 'html/Ranking.html';
  if ($_GET['idp'] == 'Wyczekiwane')
    $strona = 'html/Wyczekiwane.html';
  if ($_GET['idp'] == 'Kontakt')
    $strona = 'html/Kontakt.html';
  if ($_GET['idp'] == 'Źródła')
    $strona = 'html/Źródła.html';
  if ($_GET['idp'] == 'JS')
    $strona = 'html/JS.html';
  if ($_GET['idp'] == 'Filmy')
    $strona = 'html/Filmy.html';
  if ($_GET['idp'] == 'Admin')
    $strona = 'admin';

  // $filename = $strona;

  // if(file_exists($filename)) {
  //   echo "The file $filename exists";
  // } else {
  //   echo "The file $filename does not exist";
  // }

}
?>
<!doctype html>
<html>

<head>
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Content-Language" content="pl" />
  <meta name="Author" content="Dawid Rumiński" />
  <link rel="stylesheet" href="css/style.css">
  <title>Najlepsze gry</title>
  <link rel="icon" type="image/x-icon" href="img/icon.png">
  <script src="https://fonts.google.com/specimen/Anonymous+Pro?query=anonymous" crossorigin="anonymous"></script>
  <script src="js/timedate.js" type="text/javascript"></script>
  <script src="../js/kolorujtlo.js" type="text/javascript"></script>
</head>

<body onload="odliczanieCzasu(); odliczanieDaty()">
  <nav>
    <ul>
      <li id="date"></li>
      <li id="zegar"></li>
      <li <?php echo ($_GET['idp'] == 'Główna') ? 'class="active"' : ''; ?>><a href="index.php?idp=Główna">Główna</a></li>
      <li <?php echo ($_GET['idp'] == 'TOP10') ? 'class="active"' : ''; ?>><a href="index.php?idp=TOP10">TOP 10</a></li>
      <li <?php echo ($_GET['idp'] == 'Ranking') ? 'class="active"' : ''; ?>><a href="index.php?idp=Ranking">Ranking</a>
      </li>
      <li <?php echo ($_GET['idp'] == 'Wyczekiwane') ? 'class="active"' : ''; ?>><a
          href="index.php?idp=Wyczekiwane">Wyczekiwane</a></li>
      <li <?php echo ($_GET['idp'] == 'Kontakt') ? 'class="active"' : ''; ?>><a href="index.php?idp=Kontakt">Kontakt</a>
      </li>
      <li <?php echo ($_GET['idp'] == 'Źródła') ? 'class="active"' : ''; ?>><a href="index.php?idp=Źródła">Źródła</a></li>
      <li <?php echo ($_GET['idp'] == 'JS') ? 'class="active"' : ''; ?>><a href="index.php?idp=JS">JS</a></li>
      <li <?php echo ($_GET['idp'] == 'Filmy') ? 'class="active"' : ''; ?>><a href="index.php?idp=Filmy">Filmy</a></li>
      <li <?php echo ($_GET['idp'] == 'Admin') ? 'class="active"' : ''; ?>><a href="admin/">Admin</a></li>
    </ul>
  </nav>


  <?php include($strona); ?>


</body>

</html>