<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<h2>Check</h2>
<table>
<?php

    $conn = OpenCon();
    $result = $conn->query("SELECT teamid FROM teams ORDER BY teamid ASC");

    $ids = array();
    $c = 0;

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            if($c > 0)
            {
                if($ids[$c-1] < $row['teamid'])
                {
                    array_push($ids,$row['teamid']);
                }
            }
            else
            {
                array_push($ids,$row['teamid']);
            }
            $c++;

        }
    }

    if(sizeof($ids) < $settings['Options']['AnzTeams'])
    {
        echo "<tr><td>TeamIDs aren't unique</td></tr>";
    }

?>
</table>
