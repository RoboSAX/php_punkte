<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
    $styles = array("select.css");
    require_once '../lib/head.php';
?>

<body>
<table style='width:100%; height:100%' class='main'>
    <tr>
        <td style='width:20%; border-right:1px solid black' valign='top'>
            <iframe src="<?php echo $settings['Server']['base_url']."display/disp_timelist_refresh.php"; ?>" ></iframe>
        </td>
        <td style='width:60%' valign='top'>
            <table style='width:100%; height:100%'>
                <tr>
                    <td style='width:100%; border-bottom:1px solid black'>
                        <form action='guru_main.php' method='post'>
						<table>
                            <tr>
                                <td style="text-align:center" colspan="3">Guru : Editor</td>
                                <td style="text-align:center">Schiri</td>
                                <td style="text-align:center">Display</td>
                            </tr>
                            <tr>
                                <td><button type='submit' name='changeview' value='teams' >Teams</button></td>
                                <td><button type='submit' name='changeview' value='start' >Startnummern</button></td>
                                <td><button type='submit' name='changeview' value='games' >Spiele</button></td>
                                <!-- <td><button type='submit' name='changeview' value='blocks'>edit Blocks</button></td> -->
                                <td>&nbsp;<button type='submit' name='changeview' value='ref' >Vorschau</button>&nbsp;</td>
                                <td><button type='submit' name='changeview' value='disp' >Vorschau</button></td>
                            </tr>
                        </table>
						</form>
                    </td>
                </tr>
                <tr>
                    <td style='width:100%; height:100%'>
                        <iframe src="<?php
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
