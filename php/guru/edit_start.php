<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
    $styles = array("select.css");
    require_once '../lib/head.php';
?>

<?php
    $conn = OpenCon();

    // load all teams
    $teams = new Teams;
    if (!$teams->load_teams_from_db()) {
        die("0 results for team query: in edit_start.php");
    }

    if(isset($_POST["change_team_id"]) && isset($_POST["new_team_id"]))
    {
        $team = $teams->get_team_by_id((int)$_POST["change_team_id"]);
        // TODO: Update team start number
        //$team->set_id($_POST["new_team_id"]);
        $team->save_team_to_db();
    }
?>

<form action='edit_start.php' method='post'>
    <table>
        <tr>
            <td style="min-width: 100px;">Team</td>
            <td>Team-ID</td>
        </tr>
        <?php
            foreach ($teams->tms as $team)
            {
                echo "\t\t<tr>";
                echo "\t\t\t<td align='center'>\n";

                echo "\t\t\t\t<button class='selectBtn' type='submit' name='team_id'";
                if($team->get_id() == $_POST['team_id'])
                    echo " style='color:blue'";
                echo " value='".$team->get_id()."'>".$team->get_name()."</button><br>\n";

                echo "\t\t\t</td>";
                echo "\t\t\t<td align='center'>";

                if($team->get_id() == $_POST['team_id'])
                {
                    echo "\t\t\t\t<select name='new_team_id' text='".$team->get_id()."'>\n";
                    echo "\t\t\t\t\t<option value='".$team->get_id()."'>".$team->get_id()."</option>\n";
                    echo "\t\t\t\t\t<option value='0'>0</option>\n";
                    foreach($teams->get_free_ids() as $id)
                        echo "\t\t\t\t\t<option value='".$id."'>".$id."</option>\n";
                    echo "\t\t\t\t</select>\n";
                    echo "\t\t\t</td>";
                    echo "\t\t\t<td>";
                    echo "\t\t\t\t<button type='submit' name='change_team_id' value='".$_POST["team_id"]."'>Speichern</button>\n";
                }
                else echo $team->get_id();

                echo "\t\t\t</td>";
                echo "\t\t</tr>";
            }
        ?>
    </table>
</form>
