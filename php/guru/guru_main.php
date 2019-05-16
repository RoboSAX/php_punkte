<?php
    include_once '../lib/db_connection.php';
    include_once '../lib/db_manip.php';
?>

<body>
<table style='width:100%; height:100%' class='main'>
    <tr>
        <td style='width:20%; border-right:1px solid black' valign='top'><?php include "../display/disp_timelist.php"; ?></td>
        <td style='width:80%' valign='top'>
            <table style='width:100%; height:100%'>
                <tr><td style='width:100%; border-bottom:1px solid black'> <?php include "../guru/guru_nav.php" ?> </td></tr>
                <tr><td style='width:100%; height:100%'>
                    <iframe style='width:100%; height:100%; border: 0' src="<?php
                            if($_POST['changeview'] == "teams") {
                                echo "../guru/edit_teams.php";

                            } elseif($_POST['changeview'] == "start") {
                                echo "../guru/edit_start.php";

                            } elseif($_POST['changeview'] == "games") {
                                echo "../guru/edit_games.php";

                            } elseif($_POST['changeview'] == "blocks") {
                                echo "../guru/edit_blocks.php";

                            } else {
                                echo "../guru/edit_games.php";
                            }
                        ?>" />
                </td></tr>
            </table>
        </td>
    </tr>
</table>
</body>
