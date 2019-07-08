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
        write_log("MULM");
    }
?>
<table>
    <tr>
        <td>Block 1</td>
        <td>Block 2</td>
        <td>Block 3</td>
        <td>Block 4</td>
        <td>Block 5</td>
        <td>Block 6</td>
    </tr>
    <tr>
        <td><form action='edit_blocks.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='1'>Zeit addieren</button></form></td>
        <td><form action='edit_blocks.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='2'>Zeit addieren</button></form></td>
        <td><form action='edit_blocks.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='3'>Zeit addieren</button></form></td>
        <td><form action='edit_blocks.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='4'>Zeit addieren</button></form></td>
        <td><form action='edit_blocks.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='5'>Zeit addieren</button></form></td>
        <td><form action='edit_blocks.php' method='post'><input type='text' name='time'/><br><button type='submit' name='changetime' value='6'>Zeit addieren</button></form></td>
    </tr>
</table>
<br>
<?php
    if(isset($_POST['changetime']))
    {
        $sql = "SELECT time FROM games WHERE block='".($_POST['changetime']+1)."', finished='0', active='0', time>'0' ORDER BY time ASC";

        $result = $conn->query($sql);
        $times = array();

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $times[] = $row;
            }


            if($_POST['changetime'] < 5)
            {
                $sql = "SELECT time FROM games WHERE block='".$_POST['changetime']."', finished='0', active='0' ORDER BY time DESC";

                $result = $conn->query($sql);

                if($result->num_rows > 0)
                {
                    $v = array();
                    while($row = $result->fetch_assoc())
                    {
                        $v[] = $row;
                    }

                    if($v[0]['time'] > $times[0]['times'])
                    {
                        for($i = 0; $i < sizeof($times); $i++)
                        {
                            $times[$i]['time'] = addTimes($times[$i]['time'], $_POST['time']);
                        }
                    }
                }
            }
        }
        else
        {
            write_log("No games left in block ".$_POST['changetime'].". Not possible to move this block in edit_blocks.php");
        }


    }

    CloseCon($conn);
?>
