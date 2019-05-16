<?php
include_once '../lib/db_connection.php';
include_once '../lib/db_manip.php';

$conn = OpenCon();

$sql = "SELECT * FROM teams ORDER BY points DESC, teamid ASC";
$result = $conn->query($sql);

$games = array();

if($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$teams[] = $row;
	}
}
?>

<table>
<?php
for($i = 0; $i < sizeof($teams); $i++)
{
	$sql = "SELECT * FROM games WHERE block='6' AND team='".$teams[$i]['teamid']."'";
	$result = $conn->query($sql);
	
	$game = array();
	
	if($result->num_rows > 0)
	{
		$game = $result->fetch_assoc();
	}
	else
	{
		//Dieser Fall kann eigentlich nur eintreten, wenn irgendjemand diese Seite aufmacht, BEVOR es einen 6. Block gibt
	}
	
	$sql = "SELECT * FROM pointmanagement WHERE game='".$game['gameid']."'";
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
		write_log("No Points found for game: ".$game['gameid']." in disp_lastblock.php");
	}
	
	$oc = 1;
	$octmp = 1;
	
	echo "<tr><td style='width:300px'><table style='width:300px' class='list'><tr><td rowspan='2' style='width:25px'>"; 
	
	if($teams[$i]['active'])
	{
		if($i == 0)
		{
			echo $oc;
			$oc++;
		}
		else
		{
			if($i > 0 && $teams[$i]['points'] == $teams[$i-1]['points'])
			{
				echo $octmp;
				$oc++;
			}
			else
			{
				echo $oc;
				$octmp = $oc;
				$oc++;
			}
		}
			
	}

	echo "</td><td colspan='2'><b>";
	echo $teams[$i]['name']; //Name
	echo"</b></td><td rowspan='2' style='width:25px'>";
	echo $teams[$i]['points']; //Punktzahl
	echo "</td></tr><tr><td style='width:125px'>";
	echo $teams[$i]['teamleiter']; //Teamleiter
	echo "</td><td style='width:125px'>";
	echo $teams[$i]['roboter']; //Roboter
	echo "</td></tr></table></td>";

	if($game['finished'])
	{
			
		$pospoints = $points['+1'] * 1 + $points['+3'] * 3 + $points['+5'] * 5;
		$negpoints = $points['-1'] * -1 + $points['-3'] * -3 + $points['-5'] * -5;
					
		echo "<td>";
		echo "<table>";
		echo "</td><td>";
		echo int_to_time($game['time']);
		echo "</td><td>";
		echo $teamrow[$s]['objectives'];			//Anzahl der Objectives
		echo "</td><td>";
		echo $teamrow[$s]['penalties'];				//Anzahl der Strafen
		echo "</td></tr><tr><td>";
		if($pospoints + $negpoints < 0) echo "0";
		else echo $pospoints + $negpoints; 			//Anzahl der Punkte
		echo "</td><td>";
		echo $pospoints;		//Pluspunkte
		echo "</td><td>";
		echo $negpoints;		//Minuspunkte
		echo "</td></tr></table></td>";
					
	}
	else
	{
		echo "<td><i>".int_to_time($game['time'])."</i></td>";
	}
		

	echo "</tr>";
}


?>