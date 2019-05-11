<?php
function write_log($statement)
{
	$log = fopen(date("Y_m_d")."_log.txt","a+");
	fwrite($log, date("H:i:s")." : ".$statement."; \n");
	fclose($log);
}

function int_to_time($int)
{	
	if($int >= 1000)
	{
		if($int%100 >= 10)
		{
			return intdiv($int,100) . ":" . $int%100;
		}
		else
		{
			return intdiv($int,100) . ":0" . $int%100;
		}
	}
	else
	{
		if($int%100 >= 10)
		{
			return "0" . intdiv($int,100) . ":" . $int%100;
		}
		else
		{
			return "0" . intdiv($int,100) . ":0" . $int%100;
		}
	}
}

function addTimes($time1, $time2)
{
	$h = int($time1/100) + int($time2/100);
	$m = int($time1%100) + int($time2%100);
	
	if($m > 59)
	{
		$h++;
		$m -= 60; 
	}
	
	return (100 * $h) + $m;
}

function Select($table, $col)
{
	
	$conn = OpenCon();
	
	
		$sql = "SELECT $col FROM $table";
		$result = $conn->query($sql);
		
		if($col == '*')
		{
			
			$list = array(array());
			if ($result->num_rows > 0)
			{
				$i = 0;
				while($row = $result->fetch_assoc())
				{
					$list[$i] = $row;
					$i++;
				}
			}
			else
			{
				write_log("0 results in using Select() (db_manip.php) with the Parameters: ".$table."; ".$col);
			}
		}
		else
		{
			$list = array();
			if ($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
					array_push($list, $row[$col]);
				}
			}
			else
			{
				write_log("0 results in using Select() (db_manip.php) with the Parameters: ".$table."; ".$col);
			}
		}
	CloseCon($conn);

	return $list;
}

function ColCount($table)
{
	
	$conn = OpenCon();
	
	$sql = "SELECT * FROM $table";
	$result = $conn->query($sql);
	
	if($result->num_rows > 0)
	{
		$colnum = $result->field_count;
	}
	else
	{
		
	}
	
	return $colnum;
}

function UpdateDB()
{
	$conn = OpenCon();
	$settings = parse_ini_file("settings.ini", true);
	$anz = $settings['Options']['AnzTeams'];
	
	for($i = 1; $i < $anz+1; $i++)
	{		
		$points = 0;
		$sql = "SELECT * FROM games WHERE team='".$i."' AND finished='1' ORDER BY points DESC";
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0)
		{
			$g = 0;
			while($row = $result->fetch_assoc())
			{
				$highlight[0][$g] = $row['gameid'];
				$highlight[1][$g] = $row['points'];
				$highlight[2][$g] = $row['highlight'];
				$g++;
			}
			
			for($e = 0; $e < 5; $e++)
			{
				if($e < 3 && $highlight[1][$e])
				{
					$points += $highlight[1][$e];
					if(!$highlight[2][$e])
					{
						$sql = "UPDATE games SET highlight='1' WHERE gameid='".$highlight[0][$e]."'";
						$conn->query($sql);
						write_log("Changed highlight of Game: ".$highlight[0][$e]." to 1 in Update()");
					}
				}
				if(($e >= 3 && $highlight[2][$e]) || !$highlight[1][$e])
				{
					$sql = "UPDATE games SET highlight='0' WHERE gameid='".$highlight[0][$e]."'";
					$conn->query($sql);
					write_log("Changed highlight of Game: ".$highlight[0][$e]." to 0 in Update()");
				}
			}
			
			$sql = "UPDATE teams SET points='".$points."' WHERE teamid='".$i."'";
			$conn->query($sql);
			write_log("Set new points of team ".$i." to ".$points." in UpdatedDB()");
		}
		else
		{
			write_log("0 results for the query: ".$sql." : in using UpdateDB() (db_manip.php)");
		}
		
		
		
	}
	CloseCon($conn);
}

function UpdateGame($id)
{
	$conn = OpenCon();
	
	$points = array();
	$sql = "SELECT * FROM pointmanagement WHERE game ='".$id."'";
	$result = $conn->query($sql);
	
	if($result->num_rows > 0)
	{
		$points = $result->fetch_assoc();		
	}
	else
	{
		write_log("0 results for the query: ".$sql." : in using UpdateGame() (db_manip.php)");
	}
	
	$pospoints = $points['+1'] * 1 + $points['+3'] * 3 + $points['+5'] * 5;
	$negpoints = $points['-1'] * -1 + $points['-3'] * -3 + $points['-5'] * -5;

	if($pospoints + $negpoints < 0)
	{
		$sql = "UPDATE games SET points='0' WHERE gameid='".$id."'";
		$conn->query($sql);
	}
	else
	{
		$sql = "UPDATE games SET points='".($pospoints + $negpoints)."' WHERE gameid='".$id."'";
		$conn->query($sql);
	}
}

function UpdateTime()
{
	$settings = parse_ini_file("settings.ini",true);
	$conn = OpenCon();
	
	$sql = "SELECT teamid FROM teams WHERE active='1' ORDER BY points DESC, teamid ASC";
	$result = $conn->query($sql);
	
	$teams = array();
	
	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			array_push($teams, $row['teamid']);
		}
	}
	
	$timenow = $settings['Options']['StartTime'];
	$blocktime = 0;

	$blocktime = (5 * sizeof($teams)) / $settings['Options']['TeamsPerMatch'];
	if($blocktime > int($blocktime)) $blocktime = int($blocktime) + 1;

	$times = array();

	for($i = 0; $i < $settings['Options']['AnzTeams']; $i++)
	{
		$sql = "SELECT * FROM games WHERE team='".$teams[$i]."', finished='0', active='0' ORDER BY block ASC";
		$result = $conn->query($sql);
		$games = array();
		
		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				$games[] = $row;
			}
		}
		
		if($games[0]['block'] > 1)
		{
			 array_push($times,addTimes($games[0]['time'],$blocktime));
		}
	}
	for($k = 0; $k < sizeof($teams); $k++)
	{
		$sql = "UPDATE games SET time='".$times[$k]."' WHERE team='".$teams[$k]."'";
		$conn->query($sql);
	}
}
?>