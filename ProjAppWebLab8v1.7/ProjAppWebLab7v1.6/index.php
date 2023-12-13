<?php
include("cfg.php");

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
/* po tym komentarzu będzie kod do dynamicznego ładowania stron */
if(isset($_GET['idp'])) {
  if($_GET['idp'] == 'Główna')
    $strona = 'html/glowna.html';
  if($_GET['idp'] == 'TOP10')
    $strona = 'html/TOP10.html';
  if($_GET['idp'] == 'Ranking')
    $strona = 'html/Ranking.html';
  if($_GET['idp'] == 'Wyczekiwane')
    $strona = 'html/Wyczekiwane.html';
  if($_GET['idp'] == 'Kontakt')
    $strona = 'html/Kontakt.html';
  if($_GET['idp'] == 'Źródła')
    $strona = 'html/Źródła.html';
  if($_GET['idp'] == 'JS')
    $strona = 'html/JS.html';
  if($_GET['idp'] == 'Filmy')
    $strona = 'html/Filmy.html';

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
      <li><a class="active" href="index.php?idp=Główna">Główna</li></a>
      <li><a href="index.php?idp=TOP10">TOP 10</li></a>
      <li><a href="index.php?idp=Ranking">Ranking</li></a>
      <li><a href="index.php?idp=Wyczekiwane">Wyczekiwane</li></a>
      <li><a href="index.php?idp=Kontakt">Kontakt</li></a>
      <li><a href="index.php?idp=Źródła">Źródła</li></a>
      <li><a href="index.php?idp=JS">JS</li></a>
      <li><a href="index.php?idp=Filmy">Filmy</li></a>
    </ul>
  </nav>


  <?php include($strona); ?>


</body>

</html>