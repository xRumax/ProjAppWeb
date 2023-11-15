<?
 error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
 /* po tym komentarzu będzie kod do dynamicznego ładowania stron */

 if($_GET['idp'] == '') $strona = 'html/glowna.html';
 if($_GET['idp'] == 'TOP10') $strona = '/html/TOP10.html';
 if($_GET['idp'] == 'Ranking') $strona = '/html/Ranking.html';
 if($_GET['idp'] == 'Wyczekiwane') $strona = '/html/Wyczekiwane.html';
 if($_GET['idp'] == 'Kontakt') $strona = '/html/Kontakt.html';
 if($_GET['idp'] == 'Źródła') $strona = '/html/Źródła.html';
 if($_GET['idp'] == 'JS') $strona = '/html/JS.html';

 include($strona)
?>

<!doctype html>
<html>
  <head>
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="pl" />
	<meta name = "Author" content="Dawid Rumiński" />
	<link rel = "stylesheet" href="css/style.css">
	<title>Najlepsze gry</title>
	<link rel="icon" type="image/x-icon" href="img/icon.png">
    <script src="https://fonts.google.com/specimen/Anonymous+Pro?query=anonymous" crossorigin="anonymous"></script>
    <script src="timedate.js" type="text/javascript"></script>
  </head>

<body onload="odliczanieCzasu(); odliczanieDaty()">
  <nav>
    <ul> 
      <li id="date"></li>
      <li id="zegar"></li>
      <li><a class = "active" href = "index.php?idp=glowna.html">Główna</li></a>
      <li><a href = "index.php?idp=TOP10">TOP 10</li></a>
      <li><a href = "index.php?idp=Ranking.html">Ranking</li></a>
      <li><a href = "index.php?idp=Wyczekiwane.html">Wyczekiwane</li></a>
      <li><a href = "index.php?idp=Kontakt.html">Kontakt</li></a>
      <li><a href = "index.php?idp=Źródła.html">Źródła</li></a>
      <li><a href = "index.php?idp=JS.html">JS</li></a>
    </ul>
    </nav>
    
      <?php
       $nr_indeksu = '167364';
       $nrGrupy='4';

      echo 'Autor: Dawid Rumiński '.$nr_indeksu.'<br>Grupa:'.$nrGrupy.'<br/><br/>'
      ?>
    
</body>
</html>