<?php
$settings = parse_ini_file("../config/settings.ini",true);

$conn = OpenCon();

$sql = "SELECT * FROM games WHERE time>'0' AND finished='0' AND active='0' ORDER BY time ASC";
$result = $conn->query($sql);

$games = array();
$size = 0;

if($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$games[] = $row;
		$size++;
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
	for($j = 0; $j < $settings['Options']['TeamsPerMatch']; $j++)
	{
		if(isset($games[$i+$j]))
		{
			$sql = "SELECT name FROM teams WHERE teamid='".$games[$tmp+$j]['team']."' AND active='1'";
			$result = $conn->query($sql);
			$name = "";
		
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
			//	echo $name;
				if(!$j) echo "<tr><td>".int_to_time($games[$i]['time'])." : ";
				if($j != 0) echo " vs. ";
				echo $name;
				
			}
			$i++;
		}
	}
	echo "</td></tr>";
}

?>
</table>