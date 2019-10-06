<?php
    // include main function for settings and database connection
    $head = "\t<meta http-equiv='refresh' content='".$settings['Options']['RefreshRateDisp']."'>";
    require_once '../lib/db_main.php';
?>

<body style='overflow:hidden'>
    <?php require "disp_timelist.php"; ?>
</body>
