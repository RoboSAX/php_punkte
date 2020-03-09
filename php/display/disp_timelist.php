
<h2> Kommende Spiele: </h2>
<hr>
<table>
<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
	
	$conn = OpenCon();
	$sql = 'SELECT * FROM `games` INNER JOIN `teams` ON `teams`.`teamid` = `games`.`teamid` WHERE `teams`.`active` = 1 AND `games`.`finished` = 0 AND `games`.`active` = 0 ORDER BY `teams`.`points` DESC, `teams`.`position` ASC';
	$result = $conn->query($sql);


	$flag_line = true;
	for($i = 1; $res = $result->fetch_assoc(); $i++)
	{
		if($flag_line)
		{
			echo '<tr><td>';
			$flag_line = false;
		}
		//var_dump($res);
		echo $res['name'];
		
		if($settings['Options']['TeamsPerMatch'] > 1 and $i%$settings['Options']['TeamsPerMatch'] != 0 and $res['block'] > 1)
		{
			
			if(strpos($settings['Blockoptions']['Block' . $res['block']], 'vs') === true)
			{
				echo ' vs ';
			}
			else
			{			
				
				echo ', ';			
			}
		}
		
		if($i%$settings['Options']['TeamsPerMatch'] == 0 or $res['block'] == 1)
		{
			echo '</td><td>' . $res['games.time_start'] / 100 . ':' . $res['games.time_start']%100 . ' Uhr</td></tr>';
			$flag_line = true;
		}
	}
	
	CloseCon($conn);
?>
</table>


