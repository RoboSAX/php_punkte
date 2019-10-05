<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<!doctype html>
<html>

<body>
<table style='width:100%; height:95vh' class='main'>
    <tr>
        <td style='width:20%; border-right:1px solid black' valign='top'>
            <iframe style='width:100%; height:100%; border: 0' src="<?php echo $settings['Server']['base_url']."display/disp_timelist_refresh.php"; ?>" ></iframe>
        </td>
        <td style='width:60%' valign='top'>
            <table style='width:100%; height:100%'>
                <tr>
                    <td style='width:100%; border-bottom:1px solid black'>
                        <table>
                            <tr>
                                <td style="text-align:center" colspan="3">Guru : Editor</td>
                                <td style="text-align:center">Schiri</td>
                                <td style="text-align:center">Display</td>
                            </tr>
                            <tr>
                                <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='teams' >Teams</button></form></td>
                                <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='start' >Startnummern</button></form></td>
                                <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='games' >Spiele</button></form></td>
                                <!-- <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='blocks'>edit Blocks</button></form></td> -->
                                <td><form action='guru_main.php' method='post'>&nbsp;<button type='submit' name='changeview' value='ref' >Vorschau</button>&nbsp;</form></td>
                                <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='disp' >Vorschau</button></form></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style='width:100%; height:100%'>
                        <iframe style='width:100%; min-height:255px; height:100%; border: 0' src="<?php
                            echo $settings['Server']['base_url'];
                            switch($_POST['changeview']) {
                                case "teams":  echo "guru/edit_teams.php";  break;
                                case "start":  echo "guru/edit_start.php";  break;
                                case "games":  echo "guru/edit_games.php";  break;
                                case "blocks": echo "guru/edit_blocks.php"; break;
                                case "ref":    echo "todo/schiri.php";      break; //!"referee/schiri.php"
                                case "disp":   echo "display/disp_points_refresh.php"; break;
                                default:       echo "guru/edit_games.php";  break;
                            }
                        ?>" ></iframe>
                    </td>
                </tr>
            </table>
        </td>
        <td style='width:20%; border-left:1px solid black' valign='top'>
            <iframe style='width:100%; height:100%; border: 0' src="<?php echo $settings['Server']['base_url']."guru/guru_check_refresh.php"; ?>" ></iframe>
        </td>
    </tr>
</table>
</body>

</html>
