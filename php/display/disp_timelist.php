<?php
include_once '../lib/db_connection.php';
include_once '../lib/db_manip.php';

$settings = parse_ini_file("../config/settings.ini",true);

$conn = OpenCon();

$sql = "SELECT time FROM games WHERE time>'0' AND finished='0' AND active='0' AND teamactive='1' ORDER BY time ASC";
$result = $conn->query($sql);

$times = array();
$size = 1;

if($result->num_rows > 0)
{
	$row = $result->fetch_assoc();
	$times[0] = $row['time'];
	while($row = $result->fetch_assoc())
	{
		if(isset($times[$size-1]))
		{
			if($times[$size-1] != $times[$size])
			{
				array_push($times,$row['time']);
				$size++;
			}
		}
	}
}
else
{
	write_log("0 results for the query: ".$sql." in disp_timelist.php");
}
?>
<h2>Kommende Spiele</h2>
<table>
<?php
$i = 0;
while($i < $size)
{
	$tmp = $i;
	$anzeige = "";
	$name = "";
	
	$sql = "SELECT * FROM games WHERE time='".$times[$i]."' AND teamactive=1";
	$result = $conn->query($sql);
	
	$games = array();
	$gr = 0;
	
	if($result->num_rows > 0)
	{
		while($games[] = $result->fetch_assoc()) $gr++;
	}
	
	for($j = 0; $j < $settings['Options']['TeamsPerMatch'] || $j < $gr; $j++)
	{
		if(isset($games[$j]))
		{
			$sql = "SELECT name FROM teams WHERE teamid='".$games[$j]['team']."' AND active='1'";
			$result = $conn->query($sql);
		
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
					$name = $row['name'];
				}
			}
			else
			{
				write_log("0 results for the query: ".$sql." in disp_timelist.php");
			}
		
			if($name)
			{
				if($j != 0 && ($gr-1)) $anzeige .= " vs. ";
				if($anzeige != $name." vs. ") $anzeige .= $name;
			}
			$i++;
		}
	}
	if($name) echo "<tr><td>".int_to_time($times[$tmp])." : ".$anzeige;
	echo "</td></tr>";
}

?>
</table>