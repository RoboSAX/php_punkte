<?php

function write_log($statement)
{
    global $settings;
    if ($settings['Options']['Logging'])
    {
        $log = fopen($settings['Files']['logging_path'].date('Y_m_d').'_log.txt','a+');
        if ($log)
        {
            fwrite($log, date("H:i:s").' : '.$statement.';'.PHP_EOL);
            fclose($log);
        }
    }
}

function int_to_time($int)
{
    if($int >= 1000)
    {
        if($int%100 >= 10)
        {
            return floor($int / 100) . ":" . $int%100;
        }
        else
        {
            return floor($int / 100) . ":0" . $int%100;
        }
    }
    else
    {
        if($int%100 >= 10)
        {
            return "0" . floor($int / 100) . ":" . $int%100;
        }
        else
        {
            return "0" . floor($int / 100) . ":0" . $int%100;
        }
    }
}

function addTimes($time1, $time2)
{
    $h = (int)($time1/100) + (int)($time2/100);
    $m = (int)($time1%100) + (int)($time2%100);

    if($m > 59)
    {
        $h++;
        $m -= 60;
    }

    return (100 * $h) + $m;
}

function Select($table, $col)
{
    $conn = OpenCon();

    $sql = "SELECT $col FROM $table";
    $result = $conn->query($sql);

    if($col == '*')
    {
        $list = array(array());
        if ($result->num_rows > 0)
        {
            $i = 0;
            while($row = $result->fetch_assoc())
            {
                $list[$i] = $row;
                $i++;
            }
        }
        else
        {
            write_log("0 results in using Select() (db_manip.php) with the Parameters: ".$table."; ".$col);
        }
    }
    else
    {
        $list = array();
        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                array_push($list, $row[$col]);
            }
        }
        else
        {
            write_log("0 results in using Select() (db_manip.php) with the Parameters: ".$table."; ".$col);
        }
    }

    CloseCon($conn);

    return $list;
}

function ColCount($table)
{

    $conn = OpenCon();

    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);

    if($result->num_rows > 0)
    {
        $colnum = $result->field_count;
    }
    else
    {

    }
    CloseCon($conn);

    return $colnum;
}

function UpdateDB()
{
    global $settings;

    $conn = OpenCon();
    $anz = $settings['Options']['AnzTeams'];

    for($i = 1; $i < $anz+1; $i++)
    {
        $points = 0;
        $sql = "SELECT * FROM games WHERE team='".$i."' AND finished='1' ORDER BY points DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {
            $g = 0;
            while($row = $result->fetch_assoc())
            {
                $highlight[0][$g] = $row['gameid'];
                $highlight[1][$g] = $row['points'];
                $highlight[2][$g] = $row['highlight'];
                $g++;
            }

            for($e = 0; $e < 5; $e++)
            {
                if($e < 3 && $highlight[1][$e])
                {
                    $points += $highlight[1][$e];
                    if(!$highlight[2][$e])
                    {
                        $sql = "UPDATE games SET highlight='1' WHERE gameid='".$highlight[0][$e]."'";
                        $conn->query($sql);
                        write_log("Changed highlight of Game: ".$highlight[0][$e]." to 1 in Update()");
                    }
                }
                if(($e >= 3 && $highlight[2][$e]) || !$highlight[1][$e])
                {
                    $sql = "UPDATE games SET highlight='0' WHERE gameid='".$highlight[0][$e]."'";
                    $conn->query($sql);
                    write_log("Changed highlight of Game: ".$highlight[0][$e]." to 0 in Update()");
                }
            }

            $sql = "UPDATE teams SET points='".$points."' WHERE teamid='".$i."'";
            $conn->query($sql);
            write_log("Set new points of team ".$i." to ".$points." in UpdatedDB()");
        }
        else
        {
            write_log("0 results for the query: ".$sql." : in using UpdateDB() (db_manip.php)");
        }
    }
    CloseCon($conn);
}

function UpdateGame($id)
{
    $conn = OpenCon();

    $points = array();
    $sql = "SELECT * FROM pointmanagement WHERE game ='".$id."'";
    $result = $conn->query($sql);

    if($result->num_rows > 0)
    {
        $points = $result->fetch_assoc();
    }
    else
    {
        write_log("0 results for the query: ".$sql." : in using UpdateGame() (db_manip.php)");
    }

    $pospoints = $points['+1'] * 1 + $points['+3'] * 3 + $points['+5'] * 5;
    $negpoints = $points['-1'] * -1 + $points['-3'] * -3 + $points['-5'] * -5;

    if($pospoints + $negpoints <= 0)
    {
        $sql = "UPDATE games SET points='0' WHERE gameid='".$id."'";
        $conn->query($sql);
    }
    else
    {
        $sql = "UPDATE games SET points='".($pospoints + $negpoints)."' WHERE gameid='".$id."'";
        $conn->query($sql);
    }

    $sql = "UPDATE games SET objectives='".($points['+1']+$points['+3']+$points['+5'])."' WHERE gameid='".$id."'";
    $conn->query($sql);

    $sql = "UPDATE games SET penalties='".($points['-1']+$points['-3']+$points['-5'])."' WHERE gameid='".$id."'";
    $conn->query($sql);
}

function UpdateTime()
{
    global $settings;

    $conn = OpenCon();

    $sql = "SELECT teamid FROM teams WHERE active='1' ORDER BY points DESC, teamid ASC";
    $result = $conn->query($sql);

    $teams = array();

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            array_push($teams, $row['teamid']);
        }
    }

    $timenow = $settings['Blocktimes']['Block1'];
    $blocktime = 0;

    $blocktime = (5 * sizeof($teams)) / $settings['Options']['TeamsPerMatch'];
    if($blocktime > int($blocktime)) $blocktime = int($blocktime) + 1;

    $times = array();

    for($i = 0; $i < $settings['Options']['AnzTeams']; $i++)
    {
        $sql = "SELECT * FROM games WHERE team='".$teams[$i]."', finished='0', active='0' ORDER BY block ASC";
        $result = $conn->query($sql);
        $games = array();

        if($result->num_rows > 0)
        {
            $games = $result->fetch_assoc();
        }

        if($games[0]['block'] > 1)
        {
            array_push($times,addTimes($games['time'],$blocktime));
            $sql = "UPDATE games SET time='".$times[$i]."' WHERE team='".$teams[$i]." AND block> '";
            $conn->query($sql);
        }
    }
    CloseCon($conn);
}

function SetGamesInactive()
{
    $conn = OpenCon();

    for($i = 0; $i < 2; $i++)
    {
        $sql = "SELECT teamid FROM teams WHERE active='".$i."'";
        $result = $conn->query($sql);

        $size = 0;
        $teams = array();

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $teams[] = $row;
                $size++;
            }
        }

        for($j = 0; $j < $size; $j++)
        {
            $sql = "UPDATE games SET teamactive='".$i."' WHERE team='".$teams[$j]['teamid']."'";
            $conn->query($sql);
        }
    }
    CloseCon($conn);
}
?>
