<?php
    // parse settings
    $settings_filename='settings.ini';
    $settings_file='../config/'.$settings_filename;
    if (!file_exists($settings_file)) {
        die("Can't find ".$settings_filename."!");
    }
    $settings = parse_ini_file($settings_file,true);

    // check all parameters
    if ($settings['DB']['host']     == NULL) { die($settings_filename." without [DB][host]!"    ); }
    if ($settings['DB']['username'] == NULL) { die($settings_filename." without [DB][username]!"); }
    // string password
    if ($settings['DB']['database'] == NULL) { die($settings_filename." without [DB][database]!"); }
    if ($settings['Server']['base_url']   == NULL) { die($settings_filename." without [Server][base_url]!"  ); }
    if ($settings['Options']['AnzTeams']  == NULL) { die($settings_filename." without [Options][AnzTeams]!" ); }
    if ($settings['Options']['GameTime']  == NULL) { die($settings_filename." without [Options][GameTime]!" ); }
    if ($settings['Options']['TeamsPerMatch']       == NULL) { die($settings_filename." without [Options][TeamsPerMatch]!"      ); }
    if ($settings['Options']['RefreshRateInternal'] == NULL) { die($settings_filename." without [Options][RefreshRateInternal]!"); }
    if ($settings['Options']['RefreshRateDisp']     == NULL) { die($settings_filename." without [Options][RefreshRateDisp]!"    ); }
    // bool +5_enable
    // bool +3_enable
    // bool +1_enable
    // bool -1_enable
    // bool -3_enable
    // bool -5_enable
    if ($settings['Blocktimes']['Block1'] == NULL) { die($settings_filename." without [Blocktimes][Block1]!"); }
    if ($settings['Blocktimes']['Block2'] == NULL) { die($settings_filename." without [Blocktimes][Block2]!"); }
    if ($settings['Blocktimes']['Block3'] == NULL) { die($settings_filename." without [Blocktimes][Block3]!"); }
    if ($settings['Blocktimes']['Block4'] == NULL) { die($settings_filename." without [Blocktimes][Block4]!"); }
    if ($settings['Blocktimes']['Block5'] == NULL) { die($settings_filename." without [Blocktimes][Block5]!"); }
    if ($settings['Blocktimes']['Block6'] == NULL) { die($settings_filename." without [Blocktimes][Block6]!"); }

    // include main functions
    include_once '../lib/db_connection.php';
    include_once '../lib/db_manip.php';
?>
