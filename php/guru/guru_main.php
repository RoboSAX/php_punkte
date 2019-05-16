<?php
    include_once '../lib/db_connection.php';
    include_once '../lib/db_manip.php';
?>

<body>
<table style='width:100%; height:100%' class='main'>
    <tr>
        <td style='width:20%; border-right:1px solid black' valign='top'><?php include "../display/disp_timelist.php"; ?></td>
        <td style='width:80%' valign='top'>
            <table style='width:100%'>
                <tr><td style='width:100%; border-bottom:1px solid black'> <?php include "../guru/guru_nav.php" ?> </td></tr>
                <tr><td>
                    <?php
                        if($_POST['changeview'] == "teams") {
                            include "../guru/edit_teams.php";

                        } elseif($_POST['changeview'] == "start") {
                            include "../guru/edit_start.php";

                        } elseif($_POST['changeview'] == "games") {
                            include "../guru/edit_games.php";

                        } elseif($_POST['changeview'] == "blocks") {
                            include "../guru/edit_blocks.php";

                        } else {
                            include "../guru/edit_games.php";
                        }
                    ?>
                </td></tr>
            </table>
        </td>
    </tr>
</table>
</body>
