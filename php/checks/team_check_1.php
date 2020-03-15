<?php

require_once '../lib/objects.php';


echo "Setting up testing entries in database...<br>";

$conn = OpenCon();

$sql = "INSERT INTO `teams` (`teamid`, `name`, `robot`, `points`, ";
$sql.= "`teamleader`, `position`, `active`) VALUES \n";
$sql.= " (20, `CheckerTeam`, `CheckerBot`, 9001, `Mr Checker`, 666, 0)";

$conn->query($sql);

echo "Done<br><br>";

echo "Trying to initiate a Team Object...<br>";

$test = new Team();
echo "Testing constructor..<br>";
echo "IP: ";
if($test->get_ip() == 0) echo "<font colour='green'>True</font><br>";
else echo "<font colour='red'>False</font><br>";

echo "Name: ";
if($test->get_name() == "") echo "<font colour='green'>True</font><br>";
else echo "<font colour='red'>False</font><br>";

echo "Robot: ";
if($test->get_robot() == "") echo "<font colour='green'>True</font><br>";
else echo "<font colour='red'>False</font><br>";

echo "Teamleader: ";
if($test->get_teamleader() == "") echo "<font colour='green'>True</font><br>";
else echo "<font colour='red'>False</font><br>";

echo "Position: ";
if($test->get_position() == 0) echo "<font colour='green'>True</font><br>";
else echo "<font colour='red'>False</font><br>";

echo "Points: ";
if($test->get_points() == 0) echo "<font colour='green'>True</font><br>";
else echo "<font colour='red'>False</font><br>";

echo "Active: ";
if($test->get_active() == true) echo "<font colour='green'>True</font><br>";
else echo "<font colour='red'>False</font><br>";

echo "<br>Testing loading from DB...<br>";
if($test->load_team_from_db(20))
{
	echo "Returned <font colour='green'>True</font><br>"
	echo "IP: "
	if($test->get_ip() == 20) echo "<font colour='green'>True</font><br>";
	else echo "<font colour='red'>False</font><br>";

	echo "Name: ";
	if($test->get_name() == "CheckerTeam") echo "<font colour='green'>True</font><br>";
	else echo "<font colour='red'>False</font><br>";

	echo "Robot: ";
	if($test->get_robot() == "CheckerBot") echo "<font colour='green'>True</font><br>";
	else echo "<font colour='red'>False</font><br>";

	echo "Teamleader: ";
	if($test->get_teamleader() == "Mr Checker") echo "<font colour='green'>True</font><br>";
	else echo "<font colour='red'>False</font><br>";

	echo "Position: ";
	if($test->get_position() == 666) echo "<font colour='green'>True</font><br>";
	else echo "<font colour='red'>False</font><br>";

	echo "Points: ";
	if($test->get_points() == 9001) echo "<font colour='green'>True</font><br>";
	else echo "<font colour='red'>False</font><br>";

	echo "Active: ";
	if($test->get_active() == false) echo "<font colour='green'>True</font><br>";
	else echo "<font colour='red'>False</font><br>";
	
}
else echo "Returned <font colour='red'>False</font><br><br>";

echo "Testing "	//TODO






?>