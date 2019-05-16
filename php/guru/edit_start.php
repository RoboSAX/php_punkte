<?php 
include $_SERVER['DOCUMENT_ROOT'].'/robosax/php/lib/db_connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/robosax/php/lib/db_manip.php';

$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/robosax/php/settings.ini",true);

$conn = OpenCon();

if(!isset($_POST['counter']) && isset($_POST['change']))
{
	$sql = "UPDATE teams SET teamid='0' WHERE 1";
	$conn->query($sql);
}
if(isset($_POST['counter']) && isset($_POST['team']))
{
	if($_POST['counter'])
	{
		$sql = "UPDATE teams SET teamid='".($_POST['counter'])."', active='0' WHERE name='".$_POST['team']."'";
		$conn->query($sql);	
	}
}
$i = 0;
?>
<p>Immer das Team anklicken, welches die naechste Startnummer besitzt</p>
<br>
<?php
if(!isset($_POST['counter']) && !isset($_POST['change'])) echo "<form action='edit_start.php' method='post'><button name='change' value='1' type='submit'>Werte aendern</button></form>";

if(isset($_POST['change']) || isset($_POST['counter']))
{
	
	$teams = array();

	$sql = "SELECT * FROM teams WHERE teamid=0";
	$result = $conn->query($sql);

	if($result->num_rows > 0)
	{
		while($teams[] = $result->fetch_assoc());
	}

	
	echo "<table>";
	echo "<form action='edit_start.php' method='post'>";


	$count = 1;

	if(isset($_POST['counter']))
	{
		$count = $_POST['counter'] + 1;
		if(($count == $settings['Options']['AnzTeams']) || !sizeof($teams))
		{
			$sql = "UPDATE teams SET active='1' WHERE 1";
			$conn->query($sql);
			$count = 0;
		}
	}
	
	

	if(!$count) header("Location: edit_start.php");
	
	echo "Aktuelle ID: ".$count;

	while(isset($teams[$i]))
	{
		echo "<tr><td><input type='radio' name='team' value='".$teams[$i]['name']."'>".$teams[$i]['name']."</input></td></tr>";
		$i++;
	}
	echo "<tr><td><button type='submit' name='counter' value='".$count."'>Auswahl bestaetigen</button></td></tr></form>";
	echo "</form>";
	echo "</table>";

}
CloseCon($conn);
?>
