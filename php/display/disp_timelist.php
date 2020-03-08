<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
	
	$conn = OpenCon();
	$sql = 'SELECT * FROM games OUTER JOIN teams WHERE game.teamid = teams.teamid AND teams.active = 1 AND games.finished = 0 AND games.active = 0 ORDER BY teams.points DESC, teams.position ASC';
	$result = query($sql);
?>

<h2> Kommende Spiele: </h2>
<hr>
<table>
<?php
	$flag_line = true;
	for($i = 1; $res = $result->fetch_assoc(); $i++)
	{
		if($flag_line)
		{
			echo '<tr><td>';
			$flag_line = false;
		}
		
		echo $res['teams.name'];
		
		if($settings['Options']['TeamsPerMatch'] > 1 and $i%$settings['Options']['TeamsPerMatch'] != 0)
		{
			
			if(strpos($settings['Blockoptions']['Block' . $res['block']], 'vs') === true)
			{
				echo ' vs ';
			}
			else
			{			
				
				else echo ', ';			
			}
		}
		
		if($i%$settings['Options']['TeamsPerMatch'] == 0)
		{
			echo '</td><td>' . $res['games.time_start'] / 100 . ':' . $res['games.time_start']%100 . ' Uhr</td></tr>';
			$flag_line = true;
		}
	}
?>


