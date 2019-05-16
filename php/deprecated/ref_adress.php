<?php
session_start();

include_once 'db_connection.php';
include_once 'db_manip.php';

$conn = OpenCon();
$users = Select('login','name');
$passwords = Select('login','password');
$ids = Select('login','loginid');

if(sizeof($users) != sizeof($passwords))
{
	echo "Mismatch of Users and Passwords!";
}
else
{	
	for($i = 0; $i < sizeof($users); $i++)
	{
		
		if ($_POST["name"]==$users[$i]&&$_POST["password"]==$passwords[$i])
		{	
			$_SESSION['status'] = $ids[$i];
			header("Location:/robosax/workspace.php");
			exit();
		}
	}
	echo "No such User found<br>";
	echo "<a href='index.php'>Back</a>";
	session_destroy();
}
?>