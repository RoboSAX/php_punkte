<?php

include_once '../lib/db_connection.php';
include_once '../lib/db_manip.php';

$conn = OpenCon();

UpdateDB();

$f = Select('teams','teamid');
$anz = sizeof($f);

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
	write_log("0 Results for the query: ".$sql." in display.php");
}

//0 -> Punkte; 1 -> Name; 2 -> Teamleiter; 3 -> Roboter; 4 -> TeamID

?>
<body>
<table style='width:100%' class='display'>
	<tr>
		<td style='width:300px'>Teams</td><td>Spielblock I</td><td>Spielblock II</td><td>Spielblock III</td><td>Spielblock IV</td><td>Spielblock V</td>
	</tr>
		<?php
		
		$oc = 1;
		$octmp = 1;
		
		for($i = 0;$i < $anz; $i++) //Liste mit Teams
		{
			echo "<tr><td style='width:300px'><table style='width:300px' class='list'><tr><td rowspan='2' style='width:25px'>"; 
			if(!$teams[$i]['active']) echo "-";
			if($teams[$i]['active'] && $teams[$i]['games'] == 0) echo $i+1;			//Platz
			else 
			{
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
				
			$sql = "SELECT * FROM games WHERE team='".$teams[$i]['teamid']."' ORDER BY block ASC";
			$result = $conn->query($sql);
	
			$teamrow = array(); 
	
			if ($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
					$teamrow[] = $row;
				}
			}
			else
			{
				write_log("0 results for the query: ".$sql." : (display.php)");
			}
				
			
			for($s = 0; $s < 5 && $teams[$i]['active']; $s++)
			{				
				$time = int_to_time($teamrow[$s]['time']);
				$sql = "SELECT * FROM changed WHERE game='".$teamrow[$s]['gameid']."'";
				$result = $conn->query($sql);
			
				$changed = array();
			
				if ($result->num_rows > 0)
				{
					while($row = $result->fetch_assoc())
					{
						$changed = $row;
					}
				}
				else
				{
					write_log("No Changes found for game: ".$teamrow[$s]['gameid']." in display.php");
				}
				
				$sql = "SELECT * FROM pointmanagement WHERE game='".$teamrow[$s]['gameid']."'";
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
					write_log("No Points found for game: ".$teams[$s]['gameid']." in display");
				}
				
				
				if($teamrow[$s]['finished'])
				{
					$pospoints = $points['+1'] * 1 + $points['+3'] * 3 + $points['+5'] * 5;
					$negpoints = $points['-1'] * -1 + $points['-3'] * -3 + $points['-5'] * -5;
					
					echo "<td>";
					echo "<table class='games'>";
					if($changed['time']) echo "<tr><td class='changed'>";
					else echo "</td><td class='normal'>";
					echo $time;
					if($changed['objectives']) echo "</td><td class='changed'>Li: ";
					else echo "</td><td class='normal'>Li: ";
					echo $teamrow[$s]['objectives'];			//Anzahl der Objectives
					if($changed['penalties']) echo "</td><td class='changed'>St: ";
					else echo "</td><td class='normal'>St: ";
					echo $teamrow[$s]['penalties'];				//Anzahl der Strafen
					echo "</td></tr><tr>";
					if($teamrow[$s]['highlight']) echo "<td class='h'>";
					else echo "</td><td class='normal'>";
					if($pospoints + $negpoints < 0) echo "0";
					else echo $pospoints + $negpoints; 			//Anzahl der Punkte
					echo "</td><td class='normal'>";
					echo $pospoints;		//Pluspunkte
					echo "</td><td class='normal'>";
					echo $negpoints;		//Minuspunkte
					echo "</td></tr></table></td>";
					
				}
				else
				{
					if($s == 0 && isset($teamrow[$s]['time']))
					{
						if($changed['time']) echo "<td class='changed'><i>";
						else echo "<td><i>";
						echo int_to_time($teamrow[$s]['time']);
						echo "</i></td>";
					}
					else
					{
						if($teamrow[$s-1]['finished']) 
						{
							if($changed['time']) echo "<td class='changed'><i>";
							else echo "<td><i>";
							echo int_to_time($teamrow[$s]['time']);
							echo "</i></td>";
						}
					}
				}
			}				
			
		} 	
		
		?>
</table>
<?php 

CloseCon($conn);

?>
</body>