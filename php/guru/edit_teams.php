<?php
    # include main function for settings and database connection
    $styles = array("select.css");
    require_once '../lib/db_main.php';
?>

<?php
    $conn = OpenCon();

    $sql = "SELECT * FROM teams ORDER BY points DESC, teamid ASC";
    $result = $conn->query($sql);

    $teams = array();

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $teams[] = $row;
        }
    }
    else
    {
        write_log("0 results for query: ".$sql." in edit_teams.php");
    }
?>
<table>
    <tr><td>Wähle ein Team aus:</td></tr>
    <tr>
        <td style="padding-right: 20px;">
            <form action='edit_teams.php' method='post'>
                <div class="select" style="max-height: 150px;">
<?php
                    for($i = 0; isset($teams[$i]); $i++)
                    {
                        if(isset($teams[$i]))
                        {
                            echo "\t\t\t\t\t<button type='submit' name='team'";
                            if($_POST['team'] and $teams[$i]['teamid'] == $_POST['team'])
                                echo " style='color:blue'";
                            echo " value='".$teams[$i]['teamid']."'>".$teams[$i]['name']."</button><hr>\n";
                        }
                    }
?>
                </div>
            </form>
        </td>
<?php
    if(isset($_POST['team']))
    {
        $sql = "SELECT * FROM teams WHERE teamid='".$_POST['team']."'";
        $result = $conn->query($sql);

        $team = array();

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $team = $row;
            }
        }
        else
        {
            write_log("HIER STIMMT WAS NICHT?!?!?!");
        }

        echo "\t\t<td>\n";
        echo "\t\t\t<form action='edit_teams.php' method='post'>\n";
        echo "\t\t\t\t<table>\n";
        echo "\t\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t\t<td>Name: </td>\n";
        echo "\t\t\t\t\t\t<td><input type='text' value='".$team['name']."' name='name'/></td>\n";
        echo "\t\t\t\t\t</tr>\n";
        echo "\t\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t\t<td>Leiter: </td>\n";
        echo "\t\t\t\t\t\t<td><input type='text' value='".$team['teamleiter']."' name='teamleiter'/></td>\n";
        echo "\t\t\t\t\t</tr>\n";
        echo "\t\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t\t<td>Roboter: </td>\n";
        echo "\t\t\t\t\t\t<td><input type='text' value='".$team['roboter']."' name='roboter'/></td>\n";
        echo "\t\t\t\t\t</tr>\n";
        echo "\t\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t\t<td>Aktiv: </td>\n";
        echo "\t\t\t\t\t\t<td><input type='checkbox' name='active'";
            if($team['active']) echo " checked";
            echo "/></td>\n";
        echo "\t\t\t\t\t</tr>\n";
        echo "\t\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t\t<td colspan='2' align='center'><button name='changedata' value='".$_POST['team']."' type='submit'>Speichern</button></td>\n";
        echo "\t\t\t\t\t</tr>\n";
        echo "\t\t\t\t</table>\n";
        echo "\t\t\t</form>";
        echo "\t\t</td>\n";
    }

    if(isset($_POST['changedata']))
    {
        $sql = "SELECT * FROM teams WHERE teamid='".$_POST['changedata']."'";
        $result = $conn->query($sql);

        $team = array();

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $team = $row;
            }
        }
        else
        {
            write_log("HIER STIMMT WAS NICHT?!?!?!");
        }

        //note: loop possible
        if($_POST['name'] != $team['name'])
        {
            $sql = "UPDATE teams SET name='".$_POST['name']."' WHERE teamid='".$team['teamid']."'";
            $conn->query($sql);

            write_log("Updated teamname for teamid: ".$team['teamid']." to: ".$_POST['name']." in edit_teams.php");
        }
        if($_POST['teamleiter'] != $team['teamleiter'])
        {
            $sql = "UPDATE teams SET teamleiter='".$_POST['teamleiter']."' WHERE teamid='".$team['teamid']."'";
            $conn->query($sql);
        }
        if($_POST['roboter'] != $team['roboter'])
        {
            $sql = "UPDATE teams SET roboter='".$_POST['roboter']."' WHERE teamid='".$team['teamid']."'";
            $conn->query($sql);
        }
        if($_POST['active'] != $team['active'])
        {
            $sql = "UPDATE teams SET active='".$_POST['active']."' WHERE teamid='".$team['teamid']."'";
            $conn->query($sql);
        }
    }

    CloseCon($conn);
?>
    </tr>
</table>

</html>
