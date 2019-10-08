<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
    $styles = array("disp_points.css");
    require_once '../lib/head.php';
?>

<?php
    $conn = OpenCon();

    UpdateDB();

    $f = Select('teams','teamid');
    $anz = sizeof($f);

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
        write_log("0 Results for the query: ".$sql." in display.php");
    }

    // 0 -> Punkte; 1 -> Name; 2 -> Teamleiter; 3 -> Roboter; 4 -> TeamID
?>
<?php
    $guru = $_SERVER['HTTP_REFERER'] == $settings['Server']['base_url']."guru/guru_main.php";

    if($guru) echo "<form action='".$settings['Server']['base_url']."guru/edit_teams.php' method='post'>\n";

    function mklink($link, $href, $param, $arg)
    {
        if($GLOBALS['guru'])
            $link = "<button type='submit' name='$param' value='$arg'><u>$link</u></button>";
        return $link;
    }
?>
<table style='width:100%' class='display'>
    <tr>
        <th style='width:300px'>Teams</th>
        <th>Spielblock I</th>
        <th>Spielblock II</th>
        <th>Spielblock III</th>
        <th>Spielblock IV</th>
        <th>Spielblock V</th>
    </tr>
<?php
    $current_place = 1;
    $last_place = 1;

    for($i = 0;$i < $anz; $i++) //Liste mit Teams
    {
        echo "\t<tr>\n";
        echo "\t\t<td style='width:300px'>\n";
        echo "\t\t\t<table style='width:300px' class='list'>\n";
        echo "\t\t\t\t<tr>\n\t\t\t\t\t<td rowspan='2' style='width:25px'>";

        if(!$teams[$i]['active'])
        {
            echo "-";
        }
        else
        {
            if($teams[$i]['games'] != 0)
            {
                if($i == 0)
                {
                    echo $current_place;
                }
                elseif($teams[$i]['points'] == $teams[$i-1]['points'])
                {
                    echo $last_place;
                }
                else
                {
                    echo $current_place;
                    $last_place = $current_place;
                }
            }
            $current_place++;
        }
        echo "</td>\n";

        echo "\t\t\t\t\t<td colspan='2'>".mklink(
            "<b>".$teams[$i]['name']."</b>", "guru/guru_main.php",
            "team", $teams[$i]['teamid']
        )."</td>\n";
        echo "\t\t\t\t\t<td rowspan='2' style='width:25px'>".$teams[$i]['points']."</td>\n";
        echo "\t\t\t\t</tr>\n";
        echo "\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t<td style='width:125px'>".$teams[$i]['teamleiter']."</td>\n";
        echo "\t\t\t\t\t<td style='width:125px'>".$teams[$i]['roboter']."</td>\n";
        echo "\t\t\t\t</tr>\n";
        echo "\t\t\t</table>\n";
        echo "\t\t</td>\n";

        $sql = "SELECT * FROM games WHERE team='".$teams[$i]['teamid']."' ORDER BY block ASC";
        $result = $conn->query($sql);

        $teamrow = array();

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $teamrow[] = $row;
            }
        }
        else
        {
            write_log("0 results for the query: ".$sql." : (display.php)");
        }


        for($s = 0; $s < 5 && $teams[$i]['active']; $s++)
        {
            $time = int_to_time($teamrow[$s]['time']);
            $sql = "SELECT * FROM changed WHERE game='".$teamrow[$s]['gameid']."'";
            $result = $conn->query($sql);

            $changed = array();

            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    $changed = $row;
                }
            }
            else
            {
                write_log("No Changes found for game: ".$teamrow[$s]['gameid']." in display.php");
            }

            $sql = "SELECT * FROM pointmanagement WHERE game='".$teamrow[$s]['gameid']."'";
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
                write_log("No Points found for game: ".$teams[$s]['gameid']." in display");
            }


            if($teamrow[$s]['finished'])
            {
                $pospoints = $points['+1'] * 1 + $points['+3'] * 3 + $points['+5'] * 5;
                $negpoints = $points['-1'] * -1 + $points['-3'] * -3 + $points['-5'] * -5;

                //echo "\t\t<!-- finished -->\n";
                // Punkte-Zelle
                echo "\t\t<td>\n"; //!
                echo "\t\t\t<table class='games' align='center'>\n";
                echo "\t\t\t\t<tr>\n";

                // Zeitänderung
                echo "\t\t\t\t\t";
                if($changed['time']) echo "<td class='changed'>";
                else echo "<td class='normal'>";
                echo $time."</td>\n";

                // Anzahl der Objectives
                echo "\t\t\t\t\t";
                if($changed['objectives']) echo "<td class='changed'>Li: ";
                else echo "<td class='normal'>Li: ";
                echo $teamrow[$s]['objectives']."</td>\n";

                // Anzahl der Strafen
                echo "\t\t\t\t\t";
                if($changed['penalties']) echo "<td class='changed'>St: ";
                else echo "<td class='normal'>St: ";
                echo $teamrow[$s]['penalties']."</td>\n";

                echo "\t\t\t\t</tr>\n";
                echo "\t\t\t\t<tr>\n";

                // Anzahl der Punkte
                echo "\t\t\t\t\t";
                if($teamrow[$s]['highlight']) echo "<td class='h'>";
                else echo "<td class='normal'>";
                if($pospoints + $negpoints <= 0) echo "0";
                else echo $pospoints + $negpoints;
                echo "</td>\n";

                // Pluspunkte
                echo "\t\t\t\t\t<td class='normal'>".$pospoints."</td>\n";

                // Minuspunkte
                echo "\t\t\t\t\t<td class='normal'>".$negpoints."</td>\n";

                echo "\t\t\t\t</tr>\n";
                echo "\t\t\t</table>\n\t\t</td>\n";
            }
            else
            {
                if($s == 0 && isset($teamrow[$s]['time']) xor $teamrow[$s-1]['finished'])
                {
                    //echo "\t\t<!-- remaining -->\n";
                    if($changed['time']) echo "\t\t<td class='changed'>\n\t\t\t<i>";
                    else echo "\t\t<td>\t\t\n\t\t\t<i>";
                    echo int_to_time($teamrow[$s]['time']);
                    echo "</i>\n\t\t</td>\n";
                }
            }
        }

        echo "\t</tr>\n";
    }
?>
</table>

<?php if($guru) echo "</form>\n"; ?>
<?php CloseCon($conn); ?>
