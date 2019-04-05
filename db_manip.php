<?php

function Select($table, $col)
{
	$list = array();
	$conn = OpenCon();
	

		$sql = "SELECT $col FROM $table";
		$result = $conn->query($sql);
	
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
		
		CloseCon($conn);

	return $list;
}

function SelectWhere($table, $comp='')
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

?>