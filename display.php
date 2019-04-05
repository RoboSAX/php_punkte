<?php
include 'db_connection.php';
include 'db_manip.php';

$conn = OpenCon();

$f = Select('teams','teamid');
$anz = sizeof($f);
$teams = array(Select('teams','points'), Select('teams','name'), Select('teams','teamleiter'), Select('teams','roboter'), Select('teams','teamid'));

array_multisort($teams[0], SORT_DESC, $teams[1], $teams[2], $teams[3], $teams[4]);
//0 -> Punkte; 1 -> Name; 2 -> Teamleiter; 3 -> Roboter; 4 -> TeamID
?>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<table style="width:100%" class='display'>
	<tr>
		<td style='width:300px'>Teams</td><td>Spielblock I</td><td>Spielblock II</td><td>Spielblock III</td><td>Spielblock IV</td><td>Spielblock V</td>
	</tr>
	<?php
		
		for($i = 0;$i < $anz; $i++)
		{
			echo "<tr><td>";
			echo "<table style='width:300px' class='list'><tr><td rowspan='2' style='width:25px'>";
			echo $i+1;			//Platz
			echo "</td><td colspan='2'>";
			echo $teams[1][$i]; //Name
			echo"</td><td rowspan='2' style='width:25px'>";
			echo $teams[0][$i]; //Punktzahl
			echo "</td></tr><tr><td style='width:125px'>";
			echo $te ams[2][$i]; //Teamleiter
			echo "</td><td style='width:125px'>";
			echo $teams[3][$i]; //Roboter
			echo "</td></tr></table><br>";
			echo "</td>";
			
			$s = 0;
			$games = SelectWhere('games','team='.$teams[4][$i]);
			//0 -> GameID; 1 -> Block; 2 -> Zeit; 3 -> Punkte; 4 -> TeamID; 5 -> Strafenanzahl; 6 -> Objectives
			array_multisort($games[0], $games[1], SORT_ASC, $games[2], $games[3], $games[4], $games[5], $games[6]);
			
			while($s < 6 && $games[2][$i] <> '00:00:00')
			{
				
				if($games[][])
			}
			
		}
		
	?>

</table>
<?php 

CloseCon($conn);

?>
</body>