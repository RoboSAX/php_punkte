<?php
include 'db_connection.php';
include 'db_manip.php';

$settings = parse_ini_file('settings.ini',true);

$teams = Select('teams','*');
var_dump($teams);
?>
<p>Ein Team auswählen:</p>