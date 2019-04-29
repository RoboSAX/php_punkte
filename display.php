<?php
include 'db_connection.php';
include 'db_manip.php';

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

CheckOrder($teams);
//0 -> Punkte; 1 -> Name; 2 -> Teamleiter; 3 -> Roboter; 4 -> TeamID

?>
<head>
<style>
table.list {
	border: 1px solid black; 
	text-align: center; 
	border-spacing: 0px;
	margin-top: 10px;
	}
table.list td {
	border: 1px solid black;
	color: black;	
	}
	
	
table.games {
	border: 1px solid black;
	text-align: left; 
	border-spacing: 0px;
	margin-top: 10px;
	margin-left: 20%;
	margin-right: 20%;
	width: 60%;
	}
	
table.games td.normal {
	border : 1px solid black;
	color: black;
	background-color: transparent;
	}
	
table.games td.changed {
	border: 1px solid black; 
	color: red;
	background-color: transparent;
	}	
	
table.games td.h {
	border: 1px solid black;
	background-color: yellow;
	}

table.games tr {
	border
	
}

table.display {
	border: 0px solid black; 
	text-align: center; 
	margin-top: 10px;
	}
table.display td {
	text-align: center;
	}
table.display tr {
	
}

h2 {
	text-align: center;
	}
</style>
</head>
<body>
<table style='width:100%' class='display'>
	<tr>
		<td style='width:300px'>Teams</td><td>Spielblock I</td><td>Spielblock II</td><td>Spielblock III</td><td>Spielblock IV</td><td>Spielblock V</td>
	</tr>
		<?php
		
		
		for($i = 0;$i < $anz; $i++) //Liste mit Teams
		{
			echo "<tr><td style='width:300px'><table style='width:300px' class='list'><tr><td rowspan='2' style='width:25px'>";
			echo $i+1;			//Platz
			echo "</td><td colspan='2'>";
			echo $teams[$i]['name']; //Name
			echo"</td><td rowspan='2' style='width:25px'>";
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
				$g = 0;
				while($row = $result->fetch_assoc())
				{
					$teamrow[$g] = $row;
					$g++;
				}
			}
			else
			{
				write_log("0 results for the query: ".$sql." : (display.php)");
			}
				
			
			for($s = 0; $s < 5; $s++)
			{				
				$time = explode(':',$teamrow[$s]['time']);
				/*$sql = "SELECT * FROM changed WHERE game='".$teamrow[$s+1]['gameid']."'";
				$result = $conn->query($sql);
			
				$changed = array();
			
				if ($result->num_rows > 0)
				{
					while($row = $result->fetch_assoc())
					{
						$changed[] = $row;
					}
				}
				*/
				if($teamrow[$s]['finished'])
				{
					echo "<td>";
					echo "<table class='games'><tr><td>";
					echo $time[1] . ":" . $time[2]; 
					echo "</td><td>";
					echo $teamrow[$s]['objectives'];			//Anzahl der Objectives
					echo "</td><td>";
					echo $teamrow[$s]['penalties'];				//Anzahl der Strafen
					echo "</td></tr><tr>";
					if($teamrow[$s]['highlight']) echo "<td class='h'>";
					else echo "<td class='normal'>";
					echo $teamrow[$s]['points'];				//Anzahl der Punkte
					echo "</td><td>";
					echo "Filler";								//Fill
					echo "</td><td>";
					echo "Filler";								//Fill
					echo "</td></tr></table></td>";
					
				}
				else
				{
					if($s == 4 || !$teamrow[$s+1][7])
					{
						echo "<td></td>";
					}
					else
					{
						echo "Fail";
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