<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
    $styles = array("select.css");
    require_once '../lib/head.php';
?>

<?php
    $teams = new Teams;
    if (!$teams->load_teams_from_db()) {
        write_log("0 results for query: in edit_teams.php");
    }

    if (isset($_POST['team'])) {
        $current_team_id = (int)$_POST['team'];
        $current_team = $teams->get_team_by_id($current_team_id);
    } else {
        $current_team_id = -1;
    }
?>
<table>
    <tr><td>Wähle ein Team aus:</td></tr>
    <tr>
        <td style="padding-right: 20px;">
            <form action='edit_teams.php' method='post'>
                <div class="select" style="max-height: 150px;">
                    <?php
                        foreach ($teams->tms as $team) {
                            echo "                    <button type='submit' name='team'";
                            if ($team->get_id() == $current_team_id) {
                                echo " style='color:blue'";
                            }
                            echo " value='".$team->get_id()."'>".$team->get_name()."</button><br>\n"; #"</button><hr>\n";
                        }
                    ?>
                </div>
            </form>
        </td>
        <?php
            if (isset($current_team)) {

                echo "        <td>\n";
                echo "            <form action='edit_teams.php' method='post'>\n";
                #echo "                <input type='hidden' name='team' value='".$_POST['team']."'/>";
                echo "                <table>\n";
                echo "                    <tr>\n";
                echo "                        <td>Name: </td>\n";
                echo "                        <td><input type='text' value='".$current_team->get_name()."' name='name'/></td>\n";
                echo "                    </tr>\n";
                echo "                    <tr>\n";
                echo "                        <td>Leiter: </td>\n";
                echo "                        <td><input type='text' value='".$current_team->get_teamleader()."' name='teamleader'/></td>\n";
                echo "                    </tr>\n";
                echo "                    <tr>\n";
                echo "                        <td>Roboter: </td>\n";
                echo "                        <td><input type='text' value='".$current_team->get_robot()."' name='robot'/></td>\n";
                echo "                    </tr>\n";
                echo "                    <tr>\n";
                echo "                        <td>Aktiv: </td>\n";
                echo "                        <td><input type='checkbox' value='1' name='active'";
                if ($current_team->get_active()) echo " checked";
                echo "/></td>\n";
                echo "                    </tr>\n";
                echo "                    <tr>\n";
                echo "                        <td colspan='2' align='center'><button name='changedata' value='".$current_team_id."' type='submit'>Speichern</button></td>\n";
                echo "                    </tr>\n";
                echo "                </table>\n";
                echo "            </form>";
                echo "        </td>\n";
            }
        ?>
    </tr>
</table>

<?php
    if (isset($_POST['changedata'])) {
        $current_team_id = (int)$_POST['changedata'];
        $current_team = $teams->get_team_by_id($current_team_id);


        if (isset($_POST['name']      )) $current_team->set_name      ($_POST['name']        );
        if (isset($_POST['teamleader'])) $current_team->set_teamleader($_POST['teamleader']  );
        if (isset($_POST['robot']     )) $current_team->set_robot     ($_POST['robot']       );
        if (isset($_POST['active']    )) {
            $current_team->set_active((int) $_POST['active']);
        } else {
            $current_team->set_active(false);
        }

        $current_team->save_team_to_db();
    }
?>

</html>
