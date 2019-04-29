<?php
include 'db_connection.php';
include 'db_manip.php';


if(isset($_SESSION['case']))
{
	switch($_SESSION['case'])
	{
		case 0:
			$team = SelectWhere("teams","name='".$_SESSION['name']."'");
			var_dump($team);
		
	}
}



?>