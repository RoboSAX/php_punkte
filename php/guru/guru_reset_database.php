<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<form action='guru_reset_database.php' method='post'>
    <?php
        if(!isset($_POST['safety'])) {
            echo "    <button type='submit' name='safety'>Datenbank wirklich neu aufsetzen ?</button>\n";
        } else {
            echo "    <button type='submit' name='clear'>Datenbank komplett löschen ?</button><br>\n";
            echo "    <button type='submit' name='test' value='1'>Testwerte 1 setzen ?</button><br>\n";
            echo "    <button type='submit' name='test' value='2'>Testwerte 2 setzen ?</button><br>\n";
            echo "    <button type='submit' name='test' value='3'>Testwerte 3 setzen ?</button><br>\n";
        }
    ?>
</form>

<?php
    $conn = OpenCon();

    # Datenbank löschen
    if(isset($_POST['clear']) || isset($_POST['test'])) {
        $AnzTeams = $settings['Options']['AnzTeams'];
        $TimeStart = $settings['Blocktimes']['Block1'];

        echo "<br>\n";
        echo "Lösche alte Tabellen:<br>\n";
        flush(); ob_flush();

        $conn->query('SET foreign_key_checks = 0');
        if ($result = $conn->query("SHOW TABLES"))
        {
            while($row = $result->fetch_array(MYSQLI_NUM))
            {
                echo "    <div style='text-indent:20px;'>".$row[0]."</div>\n";
                flush(); ob_flush();
                $conn->query('DROP TABLE IF EXISTS '.$row[0]);
            }
        }



        echo "<br>\n";
        echo "Lege Tabellen neu an:<br>\n";
        flush(); ob_flush();
        $conn->query('SET foreign_key_checks = 1');



        echo "    <div style='text-indent:20px;'>changed</div>\n";
        flush(); ob_flush();
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
        for($i = 1; $i <= $AnzTeams; $i++)
        {
            if ($i != 1) $sql .= ", \n";
            $sql.= "(".($i).", ".($i).", 0, 0, 0, 0)";
        }
        $sql.= ";";
        $conn->query($sql);

        $sql = "ALTER TABLE `changed`
          ADD PRIMARY KEY (`changedid`);";
        $conn->query($sql);

        $sql = "ALTER TABLE `changed`
          MODIFY `changedid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;";
        $conn->query($sql);



        echo "    <div style='text-indent:20px;'>games</div>\n";
        flush(); ob_flush();
        $sql = "CREATE TABLE `games` (
                `gameid`     int(11)    NOT NULL,
                `block`      int(11)    NOT NULL,
                `time_start` int(11)    NOT NULL,
                `time_act`   int(11)    NOT NULL,
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

        $sql = "INSERT INTO `games` (`gameid`, `block`, `time_start`, `time_act`, `points`, ";
        $sql.= "`objectives`, `penalties`, `team`, `active`, `finished`, ";
        $sql.= "`highlight`, `teamactive`) VALUES \n";
        $curTime = 0;
        for($j = 1; $j <= $AnzTeams; $j++)
        {
            if($j != 1) $sql .= ", \n";
            $sql.= "(".$j.", 1, ";
            if((($j % $settings['Options']['TeamsPerMatch']) == ($settings['Options']['TeamsPerMatch'] - 1)) && $j > 1)
            {
                $curTime = addTimes($curTime,'5');
            }



            $sql.= addTimes($TimeStart,$curTime).", 0,";
            $sql.= "0, 0, 0, 0, 0, 0, 0, 1)";
        }

        $sql.= ";";
        $conn->query($sql);

        $sql = "ALTER TABLE `games`
          ADD PRIMARY KEY (`gameid`);";
        $conn->query($sql);

        $sql = "ALTER TABLE `games`
          MODIFY `gameid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;";
        $conn->query($sql);



        echo "    <div style='text-indent:20px;'>pointmanagement</div>\n";
        flush(); ob_flush();
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
        for($i = 1; $i <= $AnzTeams; $i++)
        {
            if ($i != 1) $sql .= ", \n";
            $sql.= "(".($i).", ".($i).", 0, 0, 0, 0, 0, 0)";
        }
        $sql.= ";";
        $conn->query($sql);

        $sql = "ALTER TABLE `pointmanagement`
          ADD PRIMARY KEY (`pointid`);";
        $conn->query($sql);

        $sql = "ALTER TABLE `pointmanagement`
          MODIFY `pointid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;";
        $conn->query($sql);



        echo "    <div style='text-indent:20px;'>teams</div>\n";
        flush(); ob_flush();
        $sql = "CREATE TABLE `teams` (
                `teamid`     int(11)     NOT NULL,
                `name`       varchar(40) NOT NULL,
                `robot`      varchar(20) NOT NULL,
                `points`     int(11)     NOT NULL,
                `teamleader` varchar(20) NOT NULL,
                `position`   int(11)     NOT NULL,
                `active`     tinyint(1)  NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        $conn->query($sql);

        $sql = "INSERT INTO teams (`teamid`, `name`, `robot`, `points`, ";
        $sql.= "`teamleader`, `position`, `active`) VALUES \n";
        for($i = 1; $i <= $AnzTeams; $i++)
        {
            if ($i != 1) $sql .= ", \n";
            $sql.= "(".$i.", 'team".($i)."', 'robot".($i);
            $sql.= "', 0, 'teamleader".($i)."', 0, 1)";
        }
        $sql.= ";";
        $conn->query($sql);

        $sql = "ALTER TABLE `teams`
          ADD PRIMARY KEY (`teamid`);";
        $conn->query($sql);



        echo "<br>\n";
        if(isset($_POST['clear'])) {
            echo "Done<br>\n";
            }
    }

    # Testdatensatz anlegen
    if(isset($_POST['test'])) {
        echo "<br>\n";
        echo "Lege Testdatensatz an:<br>\n";

        if ($_POST['test'] >= 1) {
            echo "    <div style='text-indent:20px;'>Stufe 1</div>\n";

            $sql = "UPDATE games SET `block`=1,
									 `time_start`=945,
									 `time_act`=950,
									 `points`=4,
									 `team`=1,
									 `active`=0,
									 `finished`=1,
									 `highlight`=0,
									 `teamactive`=1 WHERE `gameid`=1";
			$conn->query($sql);

            $sql = "UPDATE games SET `block`=1,
                                     `time_start`=945,
                                     `time_act`=952,
                                     `points`=8,
                                     `team`=2,
                                     `active`=0,
                                     `finished`=1,
                                     `highlight`=1,
                                     `teamactive`=1 WHERE `gameid`=2";
			$conn->query($sql);

            $sql = "UPDATE games SET `block`=1,
                                     `time_start`=950,
                                     `time_act`=100,
                                     `points`=1,
                                     `team`=4,
                                     `active`=1,
                                     `finished`=0,
                                     `highlight`=0,
                                     `teamactive`=1 WHERE `gameid`=3";
			$conn->query($sql);

            echo "        <div style='text-indent:40px;'>added 3 games</div>\n";
        }

        if ($_POST['test'] >= 2) {
            echo "    <div style='text-indent:20px;'>Stufe 2</div>\n";

            # ...
            echo "        <div style='text-indent:40px;'>...todo...</div>\n";
        }

        if ($_POST['test'] >= 3) {
            echo "    <div style='text-indent:20px;'>Stufe 3</div>\n";

            # ...
            echo "        <div style='text-indent:40px;'>...todo...</div>\n";
        }

        echo "<br>\n";
        echo "Done<br>\n";
    }

    CloseCon($conn);
?>
