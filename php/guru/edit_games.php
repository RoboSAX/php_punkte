<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
    $styles = array("select.css");
    require_once '../lib/head.php';
?>

<?php
    $teams = Select('teams','*');
    if(isset($_POST['game'])) $_POST['team'] = $_POST['game'];
?>

<table>
    <tr><td>Wähle ein Team aus:</td></tr>
    <tr>
        <td style="padding-right: 20px;">
            <form action='edit_games.php' method='post'>
                <div class="select" style="max-height:116px;">
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
        $conn = OpenCon();

        $sql = "SELECT * FROM games WHERE team='".$_POST['team']."' ORDER BY block ASC";
        $result = $conn->query($sql);

        $games = array();

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $games[] = $row;
            }
        }
        else
        {
            write_log("0 Results for the query: ".$sql." in edit_games.php");
        }

        for($i = 0; isset($games[$i]) && $i < 7; $i++)
        {
            $sql = "SELECT * FROM pointmanagement WHERE game='".$games[$i]['gameid']."'";
            $result = $conn->query($sql);

            $points = array();

            if($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    $points = $row;
                }
            }
            else
            {
                write_log("No Points found for game: ".$games[$i]['gameid']." in edit_games.php");
            }
            echo "\t\t<td>Block : ".$games[$i]['block']."\n";
            echo "\t\t\t<table>\n";
            echo "\t\t\t\t<tr>\n";
            echo "\t\t\t\t\t<td style='padding:5px'>".$games[$i]['time']."</td>\n";
            echo "\t\t\t\t\t<td style='padding:5px'>Objectives: ".$games[$i]['objectives']."</td>\n";
            echo "\t\t\t\t\t<td style='padding:5px'>Strafen: ".$games[$i]['penalties']."</td>\n";
            echo "\t\t\t\t</tr>\n";
            echo "\t\t\t\t<tr>\n";
            echo "\t\t\t\t\t<td>".$games[$i]['points']."</td>\n";
            echo "\t\t\t\t\t<td>+Punkte: ";
            echo $points['+1'] * 1 + $points['+3'] * 3 + $points['+5'] * 5;
            echo "</td>\n";
            echo "\t\t\t\t\t<td>-Punkte: ";
            echo $points['-1'] * -1 + $points['-3'] * -3 + $points['-5'] * -5;
            echo "</td>\n";
            echo "\t\t\t\t</tr>\n";
            echo "\t\t\t\t<tr>\n";
            echo "\t\t\t\t\t<td colspan='3'>\n";
            echo "\t\t\t\t\t\t<form action='edit_games.php' method='post'>\n";
            echo "\t\t\t\t\t\t\t<button type='submit' name='game' value='".$games[$i]['gameid']."'>Bearbeiten</button>\n";
            echo "\t\t\t\t\t\t</form>\n";
            echo "\t\t\t\t\t</td>\n";
            echo "\t\t\t\t</tr>\n";
            echo "\t\t\t</table>\n";
            echo "\t\t</td>\n";
        }
        CloseCon($conn);
    }

    echo "\t</tr>\n</table>\n";

    if(isset($_POST['game']))
    {
        $conn = OpenCon();

        $sql = "SELECT * FROM games WHERE gameid='".$_POST['game']."'";
        $result = $conn->query($sql);

        $game = array();

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $game = $row;
            }
        }
        else
        {
            write_log("0 Results for the query: ".$sql." in edit_games.php");
        }

        $sql = "SELECT * FROM games WHERE block='".$game['block']."' AND time <> '".$game['time']."' ORDER BY time ASC";
        $result = $conn->query($sql);

        $refgames = array();
        $size = 0;

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $refgames[] = $row;
                $size++;
            }
        }
        else
        {
            write_log("No games found for a possible change");
        }


        $sql = "SELECT * FROM pointmanagement WHERE game='".$_POST['game']."'";
        $result = $conn->query($sql);

        $point = array();

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $point = $row;
            }
        }
        else
        {
            write_log("No Points found for game: ".$_POST['game']." in edit_games.php");
        }

        $teamname = array();

        $sql = "SELECT name FROM teams WHERE teamid='".$game['team']."' ";
        $result = $conn->query($sql);
        $teamname = $result->fetch_assoc();

        echo "<br>\n<form action='edit_games.php' method='post'>\n";
        echo "\t<table>\n";
        echo "\t\t<tr>\n";
        echo "\t\t\t<td>Team:</td>\n";
        echo "\t\t\t<td>".$teamname['name']."</td>\n";
        echo "\t\t</tr>\n";
        echo "\t\t<tr>\n";
        echo "\t\t\t<td>Block:</td>\n";
        echo "\t\t\t<td>".$game['block']."</td>\n";
        echo "\t\t</tr>\n";
        echo "\t\t<tr>\n";
        echo "\t\t\t<td>Zeit:</td>\n";
        echo "\t\t\t<td><input type='text' name='time' value='".$game['time']."'/></td>\n";
        echo "\t\t</tr>\n";

        foreach(array("+1", "+3", "+5", "-1", "-3", "-5") as $value)
        {
            if($settings['Options'][$value."_enable"])
            {
                echo "\t\t<tr>\n";
                echo "\t\t\t<td>$value:</td>\n";
                echo "\t\t\t<td><input type='text' name='$value' value='".$point['$value']."'/></td>\n";
                echo "\t\t</tr>\n";
            }
        }
        if(!$game['finished'])
        {
            echo "\t\t<tr>\n";
            echo "\t\t\t<td>Spiel beendet?</td>\n";
            echo "\t\t\t<td><input type='checkbox' name='finished' /></td>\n";
            echo "\t\t</tr>\n";
        }

        echo "\t\t<tr>\n";
        echo "\t\t\t<td>Spiel tauschen</td>\n";
        echo "\t\t\t<td>\n";
        echo "\t\t\t\t<select id='timeswitch' name='timeswitch'>\n";
        echo "\t\t\t\t\t<option value='0'>--Nur bei Spielwechsel auswaehlen--</option>\n";

        for($i = 0; $i < $size; $i++)
        {
            $sql = "SELECT name FROM teams WHERE teamid='".$refgames[$i]['team']."'";
            $result = $conn->query($sql);

            $name = $result->fetch_assoc();

            if($name['name'])
            {
                echo "\t\t\t\t\t<option value='".$refgames[$i]['gameid']."'>".int_to_time($refgames[$i]['time'])." ".$name['name']."</option>\n";
            }
        }
        echo "\t\t\t\t</select>\n";
        echo "\t\t\t</td>\n";
        echo "\t\t</tr>\n";
        echo "\t\t<tr>\n";
        echo "\t\t\t<td colspan='2'><button type='submit' name='change' value='".$_POST['game']."'>Bestaetigen</button></td>\n";
        echo "\t\t</tr>\n";
        echo "\t</table>\n";
        echo "</form>";

        CloseCon($conn);
    }

    if(isset($_POST['change']))
    {
        $conn = OpenCon();

        $sql = "SELECT * FROM games WHERE gameid='".$_POST['change']."'";
        $result = $conn->query($sql);

        $game = array();

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $game = $row;
            }
        }
        else
        {
            write_log("0 Results for the query: ".$sql." in edit_games.php");
        }

        if(isset($_POST['finished']))
        {
            $sql = "UPDATE games SET finished=1 WHERE gameid='".$game['gameid']."'";
            $conn->query($sql);
        }


        $refgame = array();

        if($_POST['timeswitch'])
        {
            $sql = "SELECT * FROM games WHERE gameid='".$_POST['timeswitch']."'";
            $result = $conn->query($sql);

            if($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    $refgame = $row;
                }

                $sql = "UPDATE games SET time='".$refgame['time']."' WHERE gameid='".$game['gameid']."'";
                $conn->query($sql);

                $sql = "UPDATE games SET time='".$game['time']."' WHERE gameid='".$refgame['gameid']."'";
                $conn->query($sql);

                $sql = "UPDATE changed SET time='1' WHERE game='".$refgame['gameid']."'";
                $conn->query($sql);

                $sql = "UPDATE changed SET time='1' WHERE game='".$game['gameid']."'";
                $conn->query($sql);

            }
            else
            {
                write_log("0 Results for the query: ".$sql." in edit_games.php");
            }
        }

        $sql = "SELECT * FROM pointmanagement WHERE game='".$_POST['change']."'";
        $result = $conn->query($sql);

        $point = array();

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $point = $row;
            }
        }
        else
        {
            write_log("No Points found for game: ".$_POST['game']." in edit_games.php");
        }

        if(($_POST['time'] != $game['time']) && !$_POST['timeswitch'])
        {
            $sql = "UPDATE games SET time='".$_POST['time']."' WHERE gameid='".$_POST['change']."'";
            $conn->query($sql);

            if($game['finished'])
            {
                $sql = "UPDATE changed SET time='1' WHERE game='".$_POST['change']."'";
                $conn->query($sql);
            }
        }

        foreach(array("+1", "+3", "+5", "-1", "-3", "-5") as $value)
        {
            if(isset($_POST[$value]))
            {
                if($_POST[$value] != $point[$value])
                {
                    $sql = "UPDATE pointmanagement SET `$value`='".$_POST[$value]."' WHERE game='".$_POST['change']."'";
                    $conn->query($sql);

                    if($game['finished'])
                    {
                        $sql = "UPDATE changed SET objectives='1' WHERE game='".$_POST['change']."'";
                        $conn->query($sql);
                    }
                }
            }
        }

        UpdateGame($_POST['change']);
        //UpdateTime();
        UpdateDB();
    }

?>

</html>
