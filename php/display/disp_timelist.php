<?php
$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/robosax/php/settings.ini",true);

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
			$sql = "SELECT name FROM teams WHERE teamid='".$games[$tmp+$j]['team']."'";
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
				if(($j + 1) == $settings['Options']['TeamsPerMatch']) echo $name;
				else echo $name." vs. ";
				
			}
			$i++;
		}
	}
	echo "</td></tr>";
}

?>
</table>