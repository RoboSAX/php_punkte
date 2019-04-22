<?php
include 'db_connection.php';
include 'db_manip.php';

$conn = OpenCon();

$f = Select('teams','teamid');
$anz = sizeof($f);
$teams = array(Select('teams','points'), Select('teams','name'), Select('teams','teamleiter'), Select('teams','roboter'), Select('teams','teamid'));
$games = array(Select('games','gameid'),Select('games','block'),Select('games','time'),Select('games','points'),Select('games','team'),Select('games','penalty'),Select('games','objectives'),Select('games','active'),Select('games','finished'));

array_multisort($teams[0], SORT_DESC, $teams[1], $teams[2], $teams[3], $teams[4]);
//0 -> Punkte; 1 -> Name; 2 -> Teamleiter; 3 -> Roboter; 4 -> TeamID

array_multisort($games[0], $games[4], SORT_ASC, $games[2], $games[3], $games[1], SORT_ASC, $games[5], $games[6], $games[7], $games[8]);
//0 -> GameID; 1 -> Block; 2 -> Zeit; 3 -> Punkte; 4 -> TeamID; 5 -> Strafenanzahl; 6 -> Objectives; 7 -> Status; 8 -> Beendet
?>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<table style='width:100%' class='display'>
	<tr>
		<td style='width:300px'>Teams</td><td>Spielblock I</td><td>Spielblock II</td><td>Spielblock III</td><td>Spielblock IV</td><td>Spielblock V</td>
	</tr>
	<tr>
		<td>
		<?php
		
		
		for($i = 0;$i < $anz; $i++) //Liste mit Teams
		{
			echo "<table style='width:300px' class='list'><tr><td rowspan='2' style='width:25px'>";
			echo $i+1;			//Platz
			echo "</td><td colspan='2'>";
			echo $teams[1][$i]; //Name
			echo"</td><td rowspan='2' style='width:25px'>";
			echo $teams[0][$i]; //Punktzahl
			echo "</td></tr><tr><td style='width:125px'>";
			echo $teams[2][$i]; //Teamleiter
			echo "</td><td style='width:125px'>";
			echo $teams[3][$i]; //Roboter
			echo "</td></tr></table><br>";
				
			//0 -> GameID; 1 -> Block; 2 -> Zeit; 3 -> Punkte; 4 -> TeamID; 5 -> Strafenanzahl; 6 -> Objectives; 7 -> Status; 8 -> Beendet
		} ?>
		</td>
		<td>
		<?php
			//zwei Schleifen inneinander, eine von oben nach unten, nach den Teams geordnet, die andere nach Block geordnet, von links nach rechts
		for($i = 0; $i <
			{
				if($games[8][$i])
				{
					echo "<td><table class='list'><tr>";
					echo "<td style='width:70px'>".$games[2][$i]."</td>";
					echo "<td style='width:70px'>".$games[6][$i]."</td>";
					echo "<td style='width:70px'>".$games[5][$i]."</td></tr>";
					echo "<tr><td style='width:70px'>".$games[3][$i]."</td>";
					echo "<td style='width:70px'>".$games[3][$i]."</td>";
					echo "<td style='width:70px'>".$games[1][$i]."</td></tr></table></td>";
					$s++;
				}
				else
				{
					//Spiele anzeigen
				}
			}
		
		?>
		</td>
	</tr>
</table>
<?php 

CloseCon($conn);

?>
</body>