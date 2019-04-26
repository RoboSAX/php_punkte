<?php

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
				echo "0 results";
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
				echo "0 results";
			}
		}
		CloseCon($conn);

	return $list;
}

function SelectWhere($table, $comp='') //Geht irgendwie ne
{
	$conn = OpenCon();
	
	$list = array(array());

	$sql = "SELECT * FROM $table WHERE '$comp'";
	$result = $conn->query($sql);
	$i = 0;
	
	if ($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$list[$i][] = $row;
			$i++;
		}
	}
	else
	{
		echo "0 results";
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
		echo "0 Results";
	}
	
	return $colnum;
}

function UpdateDB()
{
	$conn = OpenCon();
	
	$settings = parse_ini_file("settings.ini", true);
	$anz = $settings['Options']['AnzTeams'];
	$points = 0;
	
	for($i = 1; $i < $anz+1; $i++)
	{		

		$points = 0;
		$sql = "SELECT points FROM games WHERE team='".$i."' AND finished='1'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			
			while($row = $result->fetch_assoc())
			{
				$points += $row['points'];
			}
			
			$sql = "UPDATE teams SET points='".$points."' WHERE teamid='".$i."'";
			$conn->query($sql);
		}
		else
		{
			echo "0 results";
		}
		
		
		
	}
}
?>