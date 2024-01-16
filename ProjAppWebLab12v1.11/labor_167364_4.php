<?php 
echo '<b>Test include</b><br/>';
include("testinclude.php");
echo "Imie: $name <br/> Numer indexu: $index <br/> Nr grupy: $group_no"; 

echo '<b><br/><br/>Test require_once</b><br/>';
require_once("testrequire.php");
echo "Marka: $car_brand <br/> Model: $car_model <br/> Rok produkcji: $car_year<br/>"; 

echo '<b><br/><br/>Test if, else, elseif</b><br/>';
echo '<b>a</b>=5<br/>';
echo '<b>b</b>=10<br/>';

if($a>$b)
{
    echo 'a jest większe od b';
}
else if($b==$a)
{
    echo 'wartości są równe';
}
else
{
    echo 'b jest większe od a';
}

echo '<b><br/><br/>Test switch</b><br/>';
$i = 3;
switch($i)
{
    case 1:
        echo'i jest równe 1';
        break;
    case 2:
        echo 'i jest równe 2';
        break;
    case 3:
        echo 'i jest równe 3';
        break;
}

echo '<b><br/><br/>Test pętli for</b><br/>';
for($i= 1; $i<= 3; $i++)
{
    echo "$i";
}

echo '<b><br/><br/>Test pętli while</b><br/>';
$j = 1;
while($j<=5)
{
    echo "$j";
    $j++;
}

echo '<b><br/><br/>Typy zmiennych $_GET</b><br/>';
echo '<a href="labor_167364_4.php?age=20">Click me</a>';
if(isset($_GET['age']))
{
    echo ' Wiek = '.$_GET["age"];
}

echo '<b><br/><br/>Typy zmiennych $_POST</b><br/>';
echo '<a href="labor_167364_4.php?name=Dawid"></a>';

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    echo 'Hello '.($_POST["formularz"]);
}

echo '<b><br/><br/>Typy zmiennych $_SESSION</b><br/>';

session_start();

$_SESSION['color'] = 'green';
$_SESSION['animal'] = 'dog';
echo "Zmienne sesji ustawione na:";
echo $_SESSION['color'];
session_destroy();


?>
<html>
<form action="" method="post">
    <input type="text" name="formularz" id="">
    <button type="submit">submit</button>
</form>
</html>