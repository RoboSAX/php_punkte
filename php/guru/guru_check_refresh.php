<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<head>
    <meta http-equiv="refresh" content="<?php echo $settings['Options']['RefreshRateInternal']; ?>">
</head>
<body style='overflow:hidden'>
    <?php require "guru_check.php"; ?>
</body>
