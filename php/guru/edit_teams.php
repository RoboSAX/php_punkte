<?php
include $_SERVER['DOCUMENT_ROOT'].'/robosax/php/lib/db_connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/robosax/php/lib/db_manip.php';

$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/robosax/php/config/settings.ini",true);

$conn = OpenCon();

$sql = "SELECT * FROM teams ORDER BY points DESC, teamid ASC";
$result = $conn->query($sql);

$teams = array();

if($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$teams[] = $row;
	}
}
else
{
	write_log("0 results for query: ".$sql." in edit_teams.php");
}

?>
<table>
<?php
for($i = 0; $i < $settings['Options']['AnzTeams']; $i++)
{
	if(isset($teams[$i]))
	{
		echo "<table style='width:300px' class='list'><tr><form action='edit_teams.php' method='post'><td>";
		echo $teams[$i]['name'];
		echo "</td><td>";
		echo "<button type='submit' value='".$teams[$i]['teamid']."' name='team'>Daten zum Team anpassen</button>";
		echo "</td></tr></table></form><br>";
	}
}
?>
</table>
<br>
<?php 
if(isset($_POST['team']))
{
	$sql = "SELECT * FROM teams WHERE teamid='".$_POST['team']."'";
	$result = $conn->query($sql);

	$team = array();

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$team = $row;
		}
	}
	else
	{
		write_log("HIER STIMMT WAS NICHT?!?!?!");
	}

	echo "<form action='edit_teams.php' method='post'><table><tr><td>Name: </td><td>";
	echo "<input type='text' value='".$team['name']."' name='name'/></td></tr>";
	echo "<tr><td>Leiter: </td><td>";
	echo "<input type='text' value='".$team['teamleiter']."' name='teamleiter'/></td></tr>";
	echo "<tr><td>Roboter: </td><td>";
	echo "<input type='text' value='".$team['roboter']."' name='roboter'/></td></tr>";
	echo "<tr><td>Aktiv: </td><td>";
	echo "<input type='text' value='".$team['active']."' name='active'/></td></tr>";
	echo "<tr><td colspan='2'><button name='changedata' value='".$_POST['team']." type='submit'>Bestaetigen</button></td></tr></table></form>";
}

if(isset($_POST['changedata']))
{
	$sql = "SELECT * FROM teams WHERE teamid='".$_POST['changedata']."'";
	$result = $conn->query($sql);

	$team = array();

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$team = $row;
		}
	}
	else
	{
		write_log("HIER STIMMT WAS NICHT?!?!?!");
	}
	
	if($_POST['name'] != $team['name'])
	{
		$sql = "UPDATE teams SET name='".$_POST['name']."' WHERE teamid='".$team['teamid']."'";
		$conn->query($sql);
		
		write_log("Updated teamname for teamid: ".$team['teamid']." to: ".$_POST['name']." in edit_teams.php");
	}
	if($_POST['teamleiter'] != $team['teamleiter'])
	{
		
	}
}


CloseCon($conn);
?>