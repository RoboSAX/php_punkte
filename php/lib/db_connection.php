<?php
# include main function for settings and database connection
include_once $settings['Server']['base_url'].'lib/db_connection.php';

function OpenCon()
{
	$dbhost = $settings['DB']['host'];
	$dbuser = $settings['DB']['username'];
	$dbpass = $settings['DB']['password'];
	$db = $settings['DB']['database'];

	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);


	return $conn;
}

function CloseCon($conn)
{
	$conn -> close();
}

?>
