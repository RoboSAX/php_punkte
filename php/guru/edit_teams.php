<?php
    # include main function for settings and database connection
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
<form action='edit_teams.php' method='post'>
    <table>
<?php
    for($i = 0; $i < $settings['Options']['AnzTeams']; $i++)
    {
        if(isset($teams[$i]))
        {
            echo "\t<tr>\n";
            echo "\t\t<td>\"".$teams[$i]['name']."\"</td>\n";
            echo "\t\t<td><button type='submit' value='".$teams[$i]['teamid']."' name='team'>Daten zum Team anpassen</button></td>\n";
            echo "\t</tr>\n";
        }
    }
?>
    </table>
</form>
<br>

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

        echo "<form action='edit_teams.php' method='post'>\n";
        echo "\t<table>\n";
        echo "\t\t<tr>\n";
        echo "\t\t\t<td>Name: </td>\n";
        echo "\t\t\t<td><input type='text' value='".$team['name']."' name='name'/></td>\n";
        echo "\t\t</tr>\n";
        echo "\t\t<tr>\n";
        echo "\t\t\t<td>Leiter: </td>\n";
        echo "\t\t\t<td><input type='text' value='".$team['teamleiter']."' name='teamleiter'/></td>\n";
        echo "\t\t</tr>\n";
        echo "\t\t<tr>\n";
        echo "\t\t\t<td>Roboter: </td>\n";
        echo "\t\t\t<td><input type='text' value='".$team['roboter']."' name='roboter'/></td>\n";
        echo "\t\t</tr>\n";
        echo "\t\t<tr>\n";
        echo "\t\t\t<td>Aktiv: </td>\n";
        echo "\t\t\t<td><input type='text' value='".$team['active']."' name='active'/></td>\n";
        echo "\t\t</tr>\n";
        echo "\t\t<tr>\n";
        echo "\t\t\t<td colspan='2'><button name='changedata' value='".$_POST['team']."' type='submit'>Bestaetigen</button></td>\n";
        echo "\t\t</tr>\n";
        echo "\t</table>\n";
        echo "</form>";
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
