<?php
    // debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_WARNING); // error_reporting(E_ALL);

    // parse settings
    $settings_filename='settings.ini';
    $settings_file='../../config/'.$settings_filename;
    if (!file_exists($settings_file)) {
        die("Can't find ".$settings_filename."!");
    }
    $settings = parse_ini_file($settings_file,true);

    // check all parameters
    $check = array(
        "DB" => array("host", "username", /* "password", */ "database"),
        "Server" => array("base_url"),
        "Options" => array(
            "AnzTeams", "GameTime", "TeamsPerMatch", /* bool "logging", */
            "RefreshRateInternal", "RefreshRateDisp"
        ),
        /* bool "+5_enable", */  /* bool "+3_enable", */  /* bool "+1_enable", */
        /* bool "-1_enable", */  /* bool "-3_enable", */  /* bool "-5_enable" */
        "Blocktimes" => array("Block1", "Block2", "Block3", "Block4", "Block5", "Block6")
    );

    foreach($check as $switch => $names)
    {
        foreach($names as $name)
        {
            if($settings[$switch][$name] == NULL) die($settings_filename." without [".$switch."][".$name."]!" );
        }
    }

    // include main functions
    require_once '../lib/db_connection.php';
    require_once '../lib/db_manip.php';
?>

<!doctype html>
<head>
<?php
	if(isset($head)) echo $head;

	if(!isset($styles)) $styles = array();
	array_push($styles, "main.css");

	foreach($styles as $name)
		echo "\t<link rel='stylesheet' href='".$settings['Server']['base_url']."../css/$name'/>\n";
?>
</head>
