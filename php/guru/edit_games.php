<?php
    # include main function for settings and database connection
    include_once '../lib/db_main.php';
?>

<?php
$teams = Select('teams','*');

?>
<p>Ein Team auswählen:</p>
<form action='edit_games.php' method='post'>
<select id='team' name='team'>
	<option value='0'>---Auswahl---</option>
	<?php
	for($i = 0; isset($teams[$i]); $i++)
	{
		echo "<option value='".$teams[$i]['teamid']."'>".$teams[$i]['name']."</option>";
	}
	?>
</select><br>
<input type='submit' value='Akzeptieren'/>
</form>
<br>
<table style='width:100%'>
<?php
if(isset($_POST['team']))
{
	$conn = OpenCon();

	$sql = "SELECT * FROM games WHERE team='".$_POST['team']."' ORDER BY block ASC";
	$result = $conn->query($sql);

	$games = array();

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$games[] = $row;
		}
	}
	else
	{
		write_log("0 Results for the query: ".$sql." in edit_games.php");
	}

	for($i = 0; isset($games[$i]) && $i < 7; $i++)
	{
		$sql = "SELECT * FROM pointmanagement WHERE game='".$games[$i]['gameid']."'";
		$result = $conn->query($sql);

		$points = array();

		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				$points = $row;
			}
		}
		else
		{
			write_log("No Points found for game: ".$games[$i]['gameid']." in edit_games.php");
		}

		echo "Block : ".$games[$i]['block'];
		echo "<table><tr><td>";
		echo $games[$i]['time'];
		echo "</td><td>Objectives: ";
		echo $games[$i]['objectives'];
		echo "</td><td>Strafen: ";
		echo $games[$i]['penalties'];
		echo "</td></tr><tr><td>";
		echo $games[$i]['points'];
		echo "</td><td>+Punkte: ";
		echo $points['+1'] * 1 + $points['+3'] * 3 + $points['+5'] * 5;
		echo "</td><td>-Punkte: ";
		echo $points['-1'] * -1 + $points['-3'] * -3 + $points['-5'] * -5;
		echo "</td></tr><tr><td colspan='3'>";
		echo "<form action='edit_games.php' method='post'><button type='submit' name='game' value='".$games[$i]['gameid']."'>Bearbeiten</button></form>";
		echo "</td></tr></table>";
	}
	CloseCon($conn);
}
if(isset($_POST['game']))
{
	$conn = OpenCon();

	$sql = "SELECT * FROM games WHERE gameid='".$_POST['game']."'";
	$result = $conn->query($sql);

	$game = array();

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$game = $row;
		}
	}
	else
	{
		write_log("0 Results for the query: ".$sql." in edit_games.php");
	}

	$sql = "SELECT * FROM games WHERE block='".$game['block']."' AND time <> '".$game['time']."' ORDER BY time ASC";
	$result = $conn->query($sql);

	$refgames = array();
	$size = 0;

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$refgames[] = $row;
			$size++;
		}
	}
	else
	{
		write_log("No games found for a possible change");
	}


	$sql = "SELECT * FROM pointmanagement WHERE game='".$_POST['game']."'";
	$result = $conn->query($sql);

	$point = array();

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$point = $row;
		}
	}
	else
	{
		write_log("No Points found for game: ".$_POST['game']." in edit_games.php");
	}

	$teamname = array();

	$sql = "SELECT name FROM teams WHERE teamid='".$game['team']."' ";
	$result = $conn->query($sql);
	$teamname = $result->fetch_assoc();

	echo "<form action='edit_games.php' method='post'>";
	echo "<table><tr><td>Team:</td><td>".$teamname['name']."</td></tr>";
	echo "<tr><td>Block:</td><td>".$game['block']."</td></tr>";
	echo "<tr><td>Zeit:</td><td><input type='text' name='time' value='".$game['time']."'/></td></tr>";
	if($settings['Options']['+1_enable']) echo "<tr><td>+1:</td><td><input type='text' name='+1' value='".$point['+1']."'/></td></tr>";
	if($settings['Options']['+3_enable']) echo "<tr><td>+3:</td><td><input type='text' name='+3' value='".$point['+3']."'/></td></tr>";
	if($settings['Options']['+5_enable']) echo "<tr><td>+5:</td><td><input type='text' name='+5' value='".$point['+5']."'/></td></tr>";
	if($settings['Options']['-1_enable']) echo "<tr><td>-1:</td><td><input type='text' name='-1' value='".$point['-1']."'/></td></tr>";
	if($settings['Options']['-3_enable']) echo "<tr><td>-3:</td><td><input type='text' name='-3' value='".$point['-3']."'/></td></tr>";
	if($settings['Options']['-5_enable']) echo "<tr><td>-5:</td><td><input type='text' name='-5' value='".$point['-5']."'/></td></tr>";
	if(!$game['finished']) echo "<tr><td>Spiel beendet?</td><td><input type='checkbox' name='finished' /></td></tr>";
	echo "<tr><td>Spiel tauschen</td><td><select id='timeswitch' name='timeswitch'><option value='0'>--Nur bei Spielwechsel auswaehlen--</option>";
	for($i = 0; $i < $size; $i++)
	{
		$sql = "SELECT name FROM teams WHERE teamid='".$refgames[$i]['team']."'";
		$result = $conn->query($sql);

		$name = $result->fetch_assoc();

		if($name['name'])
		{
			echo "<option value='".$refgames[$i]['gameid']."'>".int_to_time($refgames[$i]['time'])." ".$name['name']."</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><td colspan='2'><button type='submit' name='change' value='".$_POST['game']."'>Bestaetigen</button></td></tr></table></form>";

	CloseCon($conn);
}
if(isset($_POST['change']))
{
	$conn = OpenCon();

	$sql = "SELECT * FROM games WHERE gameid='".$_POST['change']."'";
	$result = $conn->query($sql);

	$game = array();

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$game = $row;
		}
	}
	else
	{
		write_log("0 Results for the query: ".$sql." in edit_games.php");
	}

	if(isset($_POST['finished']))
	{
		$sql = "UPDATE games SET finished=1 WHERE gameid='".$game['gameid']."'";
		$conn->query($sql);
	}


	$refgame = array();

	if($_POST['timeswitch'])
	{
		$sql = "SELECT * FROM games WHERE gameid='".$_POST['timeswitch']."'";
		$result = $conn->query($sql);

		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				$refgame = $row;
			}

			$sql = "UPDATE games SET time='".$refgame['time']."' WHERE gameid='".$game['gameid']."'";
			$conn->query($sql);

			$sql = "UPDATE games SET time='".$game['time']."' WHERE gameid='".$refgame['gameid']."'";
			$conn->query($sql);

			$sql = "UPDATE changed SET time='1' WHERE game='".$refgame['gameid']."'";
			$conn->query($sql);

			$sql = "UPDATE changed SET time='1' WHERE game='".$game['gameid']."'";
			$conn->query($sql);

		}
		else
		{
			write_log("0 Results for the query: ".$sql." in edit_games.php");
		}
	}

	$sql = "SELECT * FROM pointmanagement WHERE game='".$_POST['change']."'";
	$result = $conn->query($sql);

	$point = array();

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$point = $row;
		}
	}
	else
	{
		write_log("No Points found for game: ".$_POST['game']." in edit_games.php");
	}

	if(($_POST['time'] != $game['time']) && !$_POST['timeswitch'])
	{
		$sql = "UPDATE games SET time='".$_POST['time']."' WHERE gameid='".$_POST['change']."'";
		$conn->query($sql);

		if($game['finished'])
		{
			$sql = "UPDATE changed SET time='1' WHERE game='".$_POST['change']."'";
			$conn->query($sql);
		}
	}

	if(isset($_POST['+1']))
	{
		if($_POST['+1'] != $point['+1'])
		{
			$sql = "UPDATE pointmanagement SET `+1`='".$_POST['+1']."' WHERE game='".$_POST['change']."'";
			$conn->query($sql);

			if($game['finished'])
			{
				$sql = "UPDATE changed SET objectives='1' WHERE game='".$_POST['change']."'";
				$conn->query($sql);
			}
		}
	}
	if(isset($_POST['+3']))
	{
		if($_POST['+3'] != $point['+3'])
		{
			$sql = "UPDATE pointmanagement SET `+3`='".$_POST['+3']."' WHERE game='".$_POST['change']."'";
			$conn->query($sql);

			if($game['finished'])
			{
				$sql = "UPDATE changed SET objectives='1' WHERE game='".$_POST['change']."'";
				$conn->query($sql);
			}
		}
	}
	if(isset($_POST['+5']))
	{
		if($_POST['+5'] != $point['+5'])
		{
			$sql = "UPDATE pointmanagement SET `+5`='".$_POST['+5']."' WHERE game='".$_POST['change']."'";
			$conn->query($sql);

			if($game['finished'])
			{
				$sql = "UPDATE changed SET objectives='1' WHERE game='".$_POST['change']."'";
				$conn->query($sql);
			}
		}
	}
	if(isset($_POST['-1']))
	{
		if($_POST['-1'] != $point['-1'])
		{
			$sql = "UPDATE pointmanagement SET `-1`='".$_POST['-1']."' WHERE game='".$_POST['change']."'";
			$conn->query($sql);

			if($game['finished'])
			{
				$sql = "UPDATE changed SET penalties='1' WHERE game='".$_POST['change']."'";
				$conn->query($sql);
			}
		}
	}
	if(isset($_POST['-3']))
	{
		if($_POST['-3'] != $point['-3'])
		{
			$sql = "UPDATE pointmanagement SET `-3`='".$_POST['-3']."' WHERE game='".$_POST['change']."'";
			$conn->query($sql);

			if($game['finished'])
			{
				$sql = "UPDATE changed SET penalties='1' WHERE game='".$_POST['change']."'";
				$conn->query($sql);
			}
		}
	}
	if(isset($_POST['-5']))
	{
		if($_POST['-5'] != $point['-5'])
		{
			$sql = "UPDATE pointmanagement SET `-5`='".$_POST['-5']."' WHERE game='".$_POST['change']."'";
			$conn->query($sql);

			if($game['finished'])
			{
				$sql = "UPDATE changed SET penalties='1' WHERE game='".$_POST['change']."'";
				$conn->query($sql);
			}
		}
	}
	UpdateGame($_POST['change']);
	//UpdateTime();
	UpdateDB();
}

?>
</table>
