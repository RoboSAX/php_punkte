<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<body style="overflow:hidden">
<table style='width:100%; height:100%' class='main'>
    <tr>
        <td style='width:20%; border-right:1px solid black' valign='top'><iframe style='width:100%; height:100%; border: 0' src="<?php echo $settings['Server']['base_url']."display/disp_timelist_refresh.php"; ?>" ></iframe></td>
        <td style='width:80%' valign='top'><iframe style='width:100%; height:100%; border: 0' src="<?php echo $settings['Server']['base_url']."referee/schiri.php"; ?>"></iframe></td>
    </tr>
</table>
</body>
