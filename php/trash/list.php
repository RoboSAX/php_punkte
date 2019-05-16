<?php
include_once 'db_connection.php';
include_once 'db_manip.php';

$conn = OpenCon();

$f = Select('teams','teamid');
$anz = sizeof($f);
$teams = array(Select('teams','points'), Select('teams','name'), Select('teams','teamleiter'), Select('teams','roboter'));

array_multisort($teams[0], SORT_DESC, $teams[1], $teams[2], $teams[3]);

for ($i = 0; $i < $anz; $i++)
{

	echo "<table style='width:300px' class='list'><tr><td rowspan='2' style='width:25px'>";
	echo $i+1;
	echo "</td><td colspan='2'>";
	echo $teams[1][$i];
	echo"</td><td rowspan='2' style='width:25px'>";
	echo $teams[0][$i];
	echo "</td></tr><tr><td style='width:125px'>";
	echo $teams[2][$i];
	echo "</td><td style='width:125px'>";
	echo $teams[3][$i];
	echo "</td></tr></table><br>";
}

CloseCon($conn);
?>