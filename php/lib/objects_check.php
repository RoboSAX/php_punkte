<?php

require_once '../lib/db_main.php';

$check_list = file("checks.txt");
	
echo "Checking workability of Classes... <br>";
	
for($i = 0; $i < sizeof($check_list); $i++)
{
	list($class, $method, $input, $output) = explode("###", $check_list[$i]);
	$test_obj = new $class;
	eval($input);
	
	ob_start();
	var_dump($test_obj);
	$result = ob_get_clean();
	
	echo "Class: " . $class . " | Method: " . $method . " | "; 
	
	if($result == $output) echo "<font color='green'>OK</font><br>";
	else echo "<font color='red'>ERROR</font><br>";
		
	echo $result . "<br>";
	
	if($output == 'object(Teams)#1 (2) { ["tms":"Teams":private]=> array(10) { [0]=> object(Team)#4 (7) { ["teamid":"Team":private]=> string(1) "1" ["teamleader":"Team":private]=> string(11) "teamleader1" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(6) "robot1" ["name":"Team":private]=> string(5) "team1" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } [1]=> object(Team)#6 (7) { ["teamid":"Team":private]=> string(1) "2" ["teamleader":"Team":private]=> string(11) "teamleader2" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(6) "robot2" ["name":"Team":private]=> string(5) "team2" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } [2]=> object(Team)#7 (7) { ["teamid":"Team":private]=> string(1) "3" ["teamleader":"Team":private]=> string(11) "teamleader3" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(6) "robot3" ["name":"Team":private]=> string(5) "team3" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } [3]=> object(Team)#8 (7) { ["teamid":"Team":private]=> string(1) "4" ["teamleader":"Team":private]=> string(11) "teamleader4" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(6) "robot4" ["name":"Team":private]=> string(5) "team4" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } [4]=> object(Team)#9 (7) { ["teamid":"Team":private]=> string(1) "5" ["teamleader":"Team":private]=> string(11) "teamleader5" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(6) "robot5" ["name":"Team":private]=> string(5) "team5" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } [5]=> object(Team)#10 (7) { ["teamid":"Team":private]=> string(1) "6" ["teamleader":"Team":private]=> string(11) "teamleader6" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(6) "robot6" ["name":"Team":private]=> string(5) "team6" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } [6]=> object(Team)#11 (7) { ["teamid":"Team":private]=> string(1) "7" ["teamleader":"Team":private]=> string(11) "teamleader7" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(6) "robot7" ["name":"Team":private]=> string(5) "team7" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } [7]=> object(Team)#12 (7) { ["teamid":"Team":private]=> string(1) "8" ["teamleader":"Team":private]=> string(11) "teamleader8" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(6) "robot8" ["name":"Team":private]=> string(5) "team8" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } [8]=> object(Team)#13 (7) { ["teamid":"Team":private]=> string(1) "9" ["teamleader":"Team":private]=> string(11) "teamleader9" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(6) "robot9" ["name":"Team":private]=> string(5) "team9" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } [9]=> object(Team)#14 (7) { ["teamid":"Team":private]=> string(2) "10" ["teamleader":"Team":private]=> string(12) "teamleader10" ["points":"Team":private]=> string(1) "0" ["robot":"Team":private]=> string(7) "robot10" ["name":"Team":private]=> string(6) "team10" ["position":"Team":private]=> string(1) "0" ["active":"Team":private]=> string(1) "1" } } ["AnzTeam":"Teams":private]=> int(10) }')
	echo "äöläpkl";
}

echo "<br>";

$test = new Teams();
$test->load_teams_from_db();

ob_start();
var_dump($test);
$result = ob_get_clean();

echo $result;

?>