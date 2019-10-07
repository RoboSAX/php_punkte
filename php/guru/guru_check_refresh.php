<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
    $head = "\t<meta http-equiv='refresh' content='".$settings['Options']['RefreshRateInternal']."'>";
    require_once '../lib/head.php';
?>

<body style='overflow:hidden'>
    <?php require "guru_check.php"; ?>
</body>
