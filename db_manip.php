<?php
function write_log($statement)
{
	$log = fopen(date("Y-m-d")."-log.txt","a+");
	fwrite($log, date("H:i:s")." : ".$statement."; \n");
	fclose($log);
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
			
			var_dump($highlight);
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
				if($e >= 3 && $highlight[2][$e])
				{
					$sql = "UPDATE games SET highlight='0' WHERE gameid='".$highlight[0][$e]."'";
					$conn->query($sql);
					write_log("Changed highlight of Game: ".$highlight[0][$e]." to 0 in Update()");
				}
			}
			
			$sql = "UPDATE teams SET points='".$points."' WHERE teamid='".$i."'";
			$conn->query($sql);
			write_log("Set new points of team ".$i." to ".$points." in Updated()");
		}
		else
		{
			write_log("0 results for the query: ".$sql." : in using Update() (db_manip.php)");
		}
		
		
		
	}
	CloseCon($conn);
}
?>