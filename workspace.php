<?php
session_start();

include 'db_connection.php';
include 'db_manip.php';

if (!isset($_SESSION["status"]))
{
	header("Location:/robosax/index.php");
	session_destroy();
	exit();
}

?>
<head>
<style>
table, th, td 
{
	border: 1px solid black;
	text-align: center;
	border-spacing: 0px;
}
</style>
</head>
<b2>Workspace</b2>
<br>
<?php

$f = Select('teams','teamid');
$anz = sizeof($f);
$teams = array(Select('teams','points'), Select('teams','name'), Select('teams','teamleiter'), Select('teams','roboter'));

array_multisort($teams[0], SORT_DESC, $teams[1], $teams[2], $teams[3]);

for ($i = 0; $i < $anz; $i++)
{

	echo "<table style='width:300px'><tr><td rowspan='2' style='width:25px'>";
	echo $i+1;
	echo "</td><td colspan='2'>";
	if($_SESSION["status"] == 1)
	{
		$_SESSION['case'] = 0;
		$_SESSION['name'] = $teams[1][$i];
		echo "<a href='edit.php'>";
		echo $teams[1][$i];
		echo "</a>";
	}
	else
	{
		echo $teams[1][$i];
	}
	echo"</td><td rowspan='2' style='width:25px'>";
	echo $teams[0][$i];
	echo "</td></tr><tr><td style='width:125px'>";
	echo $teams[2][$i];
	echo "</td><td style='width:125px'>";
	echo $teams[3][$i];
	echo "</td></tr></table><br>";
}


?>



