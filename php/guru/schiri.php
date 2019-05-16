<?php
include $_SERVER['DOCUMENT_ROOT'].'/robosax/php/lib/db_connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/robosax/php/lib/db_manip.php';

$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/robosax/php/settings.ini",true);

$conn = OpenCon();

$sql = "SELECT * FROM games WHERE finished='0' AND active='1' ORDER BY time ASC";
$result = $conn->query($sql);

$active = array();

if($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$active[] = $row;
	}
}
else
{
	write_log("0 results for the query: ".$sql." in disp_timelist.php");
}

if(sizeof($active))
{
	//Beim Absenden checken ob die SPiele bereits finished sind
}
else
{
	$sql = "SELECT * FROM games WHERE finished='0' AND active='0' AND time>0 ORDER BY time ASC";
	$result = $conn->query($sql);
	
	$games = array();
	
	if($result->num_rows > 0)
	{
		while($games[] = $result->fetch_assoc());
	}
	else
	{
		write_log("Hier ist irgendwas schiefgelaufen");
	}
	
	$i = 0;
	
	$times = array();
	
	while(isset($games[$i]))
	{
		if($games[$i-1]['time'] != $games[$i]['time']) array_push($times,$games[$i]['time']);
		$i++;
	}
	
	echo "<form action='schiri.php' method ='post'><table>";
	
	for($j = 0; $j < sizeof($times); $j++)
	{
		$teamnames = "|";
		
		$sql = "SELECT team FROM games WHERE time='".$times[$j]."' AND active='1'";
		$result = $conn->query($sql);
		
		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				
				$teamnames .= $row['name']."|";
			}				
		}
		//Als Combobox darstellen!!
		//Erstes Element soll das nächste Spiel direkt sein!!
		echo "<tr><td><button type='submit' name='time' value='".$times[$j]."'>".int_to_time($times[$j])."</button></td><td>".$teamnames."</td></tr>";
	}
	
	
}
if(isset($_POST['time']))
{
	
	 // checken ob die Spiel bereits aktiv sind
	$sql = "UPDATE games SET active='1' WHERE time='".$_POST['time']."'";
	$conn->query($sql);
	
	header("Location: schiri.php");
}

?>


