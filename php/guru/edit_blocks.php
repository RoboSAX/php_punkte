<?php
include 'db_connection.php';
include 'db_manip.php';

$settings = parse_ini_file("settings.ini",true);

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
	write_log("MULM");
}

?>
<table>
	<tr>
		<td>Block 1</td>
		<td>Block 2</td>
		<td>Block 3</td>
		<td>Block 4</td>
		<td>Block 5</td>
		<td>Block 6</td>
	</tr>
	<tr>
		<td><form action='editall.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='1'>Zeit addieren</button></form></td>
		<td><form action='editall.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='2'>Zeit addieren</button></form></td>
		<td><form action='editall.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='3'>Zeit addieren</button></form></td>
		<td><form action='editall.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='4'>Zeit addieren</button></form></td>
		<td><form action='editall.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='5'>Zeit addieren</button></form></td>
		<td><form action='editall.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='6'>Zeit addieren</button></form></td>
	</tr>
</table>
<br>
<table>
<?php
for($i = 0; $i < $settings['Options']['AnzTeams']; $i++)
{
	if(isset($teams[$i]))
	{
		echo "<table style='width:300px' class='list'><tr><form action='editall.php' method='post'><td>";
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
if(isset($_POST['changetime']))
{
	$sql = "SELECT time FROM games WHERE block='".($_POST['changetime']+1)."', finished='0', active='0', time>'0' ORDER BY time ASC";
	
	$result = $conn->query($sql);
	$times = array();
	
	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$times[] = $row;
		}
		
		
		if($_POST['changetime'] < 5)
		{
			$sql = "SELECT time FROM games WHERE block='".$_POST['changetime']."', finished='0', active='0' ORDER BY time DESC";
			
			$result = $conn->query($sql);
			
			if($result->num_rows > 0)
			{
				$v = array();
				while($row = $result->fetch_assoc())
				{
					$v[] = $row;
				}
				
				if($v[0]['time'] > $times[0]['times'])
				{
					for($i = 0; $i < sizeof($times); $i++)
					{
						$times[$i]['time'] = addTimes($times[$i]['time'], $_POST['time']);
					}
				}
			}
		}
	}
	else
	{
		write_log("No games left in block ".$_POST['changetime'].". Not possible to move this block in editall.php");
	}
	
	
}
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

	echo "<form action='editall.php' method='post'><table><tr><td>Name: </td><td>";
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
		
		write_log("Updated teamname for teamid: ".$team['teamid']." to: ".$_POST['name']." in editall.php");
	}
	if($_POST['teamleiter'] != $team['teamleiter'])
	{
		
	}
}


CloseCon($conn);
?>