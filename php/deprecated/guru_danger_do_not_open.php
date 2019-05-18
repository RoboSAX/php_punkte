<?php
include_once 'db_connection.php';
include_once 'db_manip.php';

$conn = OpenCon();
$q = 1;
for($i = 1; $i < 7; $i++)
{
	for($j = 1; $j < 17; $j++)
	{
		$sql = "INSERT INTO `games` (`gameid`, `block`, `time`, `points`, `objectives`, `penalties`, `team`, `active`, `finished`, `highlight`) VALUES (".$q.", ".$i.", 0, 0, 0, 0, ".$j.", 0, 0, 0)";
		$conn->query($sql);
		
		$sql = "INSERT INTO pointmanagement (pointid, game, `+1`, `+3`, `+5`, `-1`, `-3`, `-5`) VALUES (".$q.", ".$q.", 0, 0, 0, 0, 0, 0)";
		$conn->query($sql);
		
		$sql = "INSERT INTO changed (changedid, game, time, points, objectives, penalties) VALUES (".$q.", ".$q.", 0, 0, 0, 0)";
		$conn->query($sql);
		
		$q++;
	}
}

?>