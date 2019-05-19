<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<body style="overflow:hidden">
<table style='width:100%; height:100%' class='main'>
    <tr>
        <td style='width:20%; border-right:1px solid black' valign='top'>
            <?php include "../display/disp_timelist.php"; ?>
        </td>
        <td style='width:80%' valign='top'>
            <?php include "../display/disp_points.php"; ?>
        </td>
    </tr>
</table>
</body>
