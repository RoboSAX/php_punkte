<?php
    # include main function for settings and database connection
    include_once '../lib/db_main.php';
?>

<body>
<table style='width:100%; height:100%' class='main'>
    <tr>
        <td style='width:20%; border-right:1px solid black' valign='top'>
            <iframe style='width:100%; height:100%; border: 0' src="<?php echo $settings['Server']['base_url']."display/disp_timelist_refresh.php"; ?>" ></iframe> </td>
        <td style='width:60%' valign='top'>
            <table style='width:100%; height:100%'>
                <tr><td style='width:100%; border-bottom:1px solid black'> <?php include "../guru/guru_nav.php" ?> </td></tr>
                <tr><td style='width:100%; height:100%'>
                    <iframe style='width:100%; height:100%; border: 0' src="<?php
                            if($_POST['changeview'] == "teams") {
                                echo $settings['Server']['base_url']."guru/edit_teams.php";

                            } elseif($_POST['changeview'] == "start") {
                                echo $settings['Server']['base_url']."guru/edit_start.php";

                            } elseif($_POST['changeview'] == "games") {
                                echo $settings['Server']['base_url']."guru/edit_games.php";

                            } elseif($_POST['changeview'] == "blocks") {
                                echo $settings['Server']['base_url']."guru/edit_blocks.php";

                            } elseif($_POST['changeview'] == "ref") {
                                echo $settings['Server']['base_url']."referee/schiri.php";

                            } elseif($_POST['changeview'] == "disp") {
                                echo $settings['Server']['base_url']."display/disp_points_refresh.php";

                            } else {
                                echo $settings['Server']['base_url']."guru/edit_games.php";
                            }
                        ?>" ></iframe>
                </td></tr>
            </table>
        </td>
        <td style='width:20%; border-left:1px solid black' valign='top'>
            <iframe style='width:100%; height:100%; border: 0' src="<?php echo $settings['Server']['base_url']."guru/guru_check_refresh.php"; ?>" ></iframe> </td>
    </tr>
</table>
</body>
