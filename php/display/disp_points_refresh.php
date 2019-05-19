<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<head>
    <meta http-equiv="refresh" content="<?php echo $settings['Options']['RefreshRateDisp']; ?>">
</head>
<body style='overflow:hidden'>
    <?php require "disp_points.php"; ?>
</body>
