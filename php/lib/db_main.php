<?php
    // debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_WARNING); 
    //error_reporting(E_ALL);

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
        "Files" => array("logging_path"),
        "Options" => array(
            "AnzTeams", "GameTime", "TeamsPerMatch", "Logging",
            "RefreshRateInternal", "RefreshRateDisp",
            "+5_enable", "+3_enable", "+1_enable",
            "-1_enable", "-3_enable", "-5_enable"),
        "Blocktimes" => array("Block1", "Block2", "Block3", "Block4", "Block5", "Block6")
    );

    foreach($check as $switch => $names)
    {
        foreach($names as $name)
        {
            if(!isset($settings[$switch][$name])) die($settings_filename." without [".$switch."][".$name."]!" );
        }
    }

    // include main functions
    require_once '../lib/db_connection.php';
    require_once '../lib/db_manip.php';
    require_once '../lib/objects.php';
?>
