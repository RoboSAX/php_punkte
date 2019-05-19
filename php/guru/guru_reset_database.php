<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<form action='guru_reset_database.php' method='post'>
    <button type='submit' name='safety'>Datenbank wirklich neu aufsetzen?</button>
</form>

<?php
    $conn = OpenCon();

    if(isset($_POST['safety']))
    {
        $AnzTeams = $settings['Options']['AnzTeams'];

        echo "";
        echo "Lösche alte Tabellen:";
        $conn->query('SET foreign_key_checks = 0');
        if ($result = $conn->query("SHOW TABLES"))
        {
            while($row = $result->fetch_array(MYSQLI_NUM))
            {
                echo "    ".$row[0];
                $conn->query('DROP TABLE IF EXISTS '.$row[0]);
            }
        }



        echo "";
        echo "Lege Tabellen neu an:";
        $conn->query('SET foreign_key_checks = 1');



        echo "    changed";
        $sql = "CREATE TABLE `changed` (
                `changedid`  int(11)    NOT NULL,
                `game`       int(11)    NOT NULL,
                `time`       tinyint(1) NOT NULL,
                `points`     tinyint(1) NOT NULL,
                `objectives` tinyint(1) NOT NULL,
                `penalties`  tinyint(1) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        $conn->query($sql);

        $sql = "INSERT INTO `changed` (`changedid`, `game`, `time`, ";
        $sql.= "`points`, `objectives`, `penalties`) VALUES \n";
        for($i = 1; $i <= $AnzTeams * 6; $i++)
        {
            if ($i != 1) $sql .= ", \n";
            $sql.= "(".($i).", ".($i).", 0, 0, 0, 0)";
        }
        sql.= ";";
        $conn->query($sql);

        $sql = "ALTER TABLE `changed`
          ADD PRIMARY KEY (`changedid`);";
        $conn->query($sql);

        $sql = "ALTER TABLE `changed`
          MODIFY `changedid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;";
        $conn->query($sql);



        echo "    games";
        $sql = "CREATE TABLE `games` (
                `gameid`     int(11)    NOT NULL,
                `block`      int(11)    NOT NULL,
                `time`       int(11)    NOT NULL,
                `points`     int(11)    NOT NULL,
                `objectives` int(11)    NOT NULL,
                `penalties`  int(11)    NOT NULL,
                `team`       int(11)    NOT NULL,
                `active`     tinyint(1) NOT NULL,
                `finished`   tinyint(1) NOT NULL,
                `highlight`  tinyint(1) NOT NULL,
                `teamactive` tinyint(1) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        $conn->query($sql);

        $sql = "INSERT INTO `games` (`gameid`, `block`, `time`, `points`, ";
        $sql.= "`objectives`, `penalties`, `team`, `active`, `finished`, ";
        $sql.= "`highlight`, `teamactive`) VALUES \n";
        for($i = 1; $i <= 6; $i++)
        {
            for($j = 1; $j <= $AnzTeams; $i++)
            {
                if (($i != 1) and ($j != 1)) $sql .= ", \n";
                $sql.= "(".(($i - 1) * $AnzTeams + $j).", ".($i).", ";
                $sql.= "0, 0, 0, 0, ".($j).", 0, 0, 0, 1)";
            }
        }
        sql.= ";";
        $conn->query($sql);

        $sql = "ALTER TABLE `games`
          ADD PRIMARY KEY (`gameid`);";
        $conn->query($sql);

        $sql = "ALTER TABLE `games`
          MODIFY `gameid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;";
        $conn->query($sql);



        echo "    pointmanagement";
        $sql = "CREATE TABLE `pointmanagement` (
                `pointid` int(11) NOT NULL,
                `game`    int(11) NOT NULL,
                `+1`      int(11) NOT NULL,
                `+3`      int(11) NOT NULL,
                `+5`      int(11) NOT NULL,
                `-1`      int(11) NOT NULL,
                `-3`      int(11) NOT NULL,
                `-5`      int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        $conn->query($sql);

        $sql = "INSERT INTO `pointmanagement` (`pointid`, `game`, `+1`, ";
        $sql.= "`+3`, `+5`, `-1`, `-3`, `-5`) VALUES ";
        for($i = 1; $i <= $AnzTeams * 6; $i++)
        {
            if ($i != 1) $sql .= ", \n";
            $sql.= "(".($i).", ".($i).", 0, 0, 0, 0, 0, 0)";
        }
        sql.= ";";
        $conn->query($sql);

        $sql = "ALTER TABLE `pointmanagement`
          ADD PRIMARY KEY (`pointid`);";
        $conn->query($sql);

        $sql = "ALTER TABLE `pointmanagement`
          MODIFY `pointid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;";
        $conn->query($sql);



        echo "    teams";
        $sql = "CREATE TABLE `teams` (
            `teamid`     int(11)     NOT NULL,
            `name`       varchar(40) NOT NULL,
            `roboter`    varchar(20) NOT NULL,
            `points`     int(11)     NOT NULL,
            `teamleiter` varchar(20) NOT NULL,
            `games`      int(11)     NOT NULL,
            `active`     tinyint(1)  NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        $conn->query($sql);

        $sql = "INSERT INTO teams (`teamid`, `name`, `roboter`, `points`, ";
        $sql.= "`teamleiter`, `games`, `active`) VALUES \n";
        for($i = 0; $i < $AnzTeams; $i++)
        {
            if ($i != 1) $sql .= ", \n";
            $sql.= "(".($i+1).", 'team".($i+1)."', 'roboter".($i+1);
            $sql.= "', 0, 'teamleiter'".($i+1).", 0, 1)";
        }
        sql.= ";";
        $conn->query($sql);

        $sql = "ALTER TABLE `teams`
          ADD PRIMARY KEY (`name`);";
        $conn->query($sql);



        echo "Done";
    }

    CloseCon($conn);
?>
