<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<?php
SetGamesInactive();
?>

<?php
$conn = OpenCon();

$sql = "SELECT * FROM games WHERE finished='0' AND active='1' AND teamactive='1' ORDER BY time ASC";
$result = $conn->query($sql);

$active = array();
$anz = 0;

if($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$active[] = $row;
		$anz++;
	}

	echo "<form action='schiri.php' method='post'>";

	for($o = 0; $o < $settings['Options']['TeamsPerMatch'] && $o < $anz; $o++)
	{
		$sql = "SELECT name FROM teams WHERE teamid='".$active[$o]['team']."'";
		$erg = $conn->query($sql);

		if($erg->num_rows > 0) $team = $erg->fetch_assoc();
		echo "<table><tr><td colspan=2>".$team['name']."</td></tr>";
		echo "<tr><td>Gesamtpunktzahl: </td><td><input type='text' name='team".$o."' /></td></tr>";
		if($settings['Options']['-1_enable']) echo "<tr><td>Anzahl -1: </td><td><input type='text' name='-1".$o."' /></td></tr>";
		if($settings['Options']['-3_enable']) echo "<tr><td>Anzahl -3: </td><td><input type='text' name='-3".$o."' /></td></tr>";
		if($settings['Options']['-5_enable']) echo "<tr><td>Anzahl -5: </td><td><input type='text' name='-5".$o."' /></td></tr></table><br>";
		echo "<input type='hidden' name='teamid".$o."' value='".$active[$o]['gameid']."' />";
	}

	echo "<table><tr><td>Geteilte Punkte: </td><td><input type='text' name='gesamt' /></td></tr>";
	echo "<tr><td colspan=2><button type='submit' name='changes' value='".$active[0]['time']."'>Absenden</button</td></tr></table></form>";
}
else
{

    $sql = "SELECT time FROM games WHERE time>'0' AND finished='0' AND active='0' AND teamactive='1' ORDER BY time ASC";
    $result = $conn->query($sql);

    $times = array();
    $size = 1;

    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        $times[0] = $row['time'];
        while($row = $result->fetch_assoc())
        {
            if(isset($times[$size-1]))
            {
                if($times[$size-1] != $times[$size])
                {
                    array_push($times,$row['time']);
                    $size++;
                }
            }
        }
    }
    else
    {
        write_log("0 results for the query: ".$sql." in disp_timelist.php");
    }


    $i = 0;
    while($i < $size)
    {
        $tmp = $i;
        $anzeige = "";
        $name = "";

        $sql = "SELECT * FROM games WHERE time='".$times[$i]."' AND teamactive=1";
        $result = $conn->query($sql);

        $games = array();
        $gr = 0;

        if($result->num_rows > 0)
        {
            while($games[] = $result->fetch_assoc()) $gr++;
        }

        for($j = 0; $j < $settings['Options']['TeamsPerMatch'] || $j < $gr; $j++)
        {
            if(isset($games[$j]))
            {
                $sql = "SELECT name FROM teams WHERE teamid='".$games[$j]['team']."' AND active='1'";
                $result = $conn->query($sql);

                if($result->num_rows > 0)
                {
                    while($row = $result->fetch_assoc())
                    {
                        $name = $row['name'];
                    }
                }
                else
                {
                    write_log("0 results for the query: ".$sql." in disp_timelist.php");
                }

                if($name)
                {
                    if($j != 0 && ($gr-1)) $anzeige .= " vs. ";
                    if($anzeige != $name." vs. ") $anzeige .= $name;
                }
                $i++;
            }
        }
        if($name) echo "<option name='time' value='".$times[$tmp]."'>".int_to_time($times[$tmp])." : ".$anzeige."</option>";
    }



	echo "</select><br><button name='submit' type='submit'>Bestaetigen</button></form>";
}
if(isset($_POST['time']))
{
	$sql = "SELECT * FROM games WHERE time='".$_POST['time']."' AND active=0";		// checken ob die Spiel bereits aktiv sind
	$result = $conn->query($sql);

	if($result->num_rows > 0)
	{
		$sql = "UPDATE games SET active='1' WHERE time='".$_POST['time']."'";
		$conn->query($sql);

		header("Location: schiri.php");
	}
	else
	{
		echo "Spiel ist bereits aktiv! Bitte Seite neu laden um fortzufahren.";
		write_log("[INFORMATION-WARNING] Tried to edit allready active game in schiri.php");
	}
}
if(isset($_POST['changes']))
{

	$sql = "SELECT * FROM games WHERE time='".$_POST['changes']."' AND finished='0' AND active='1'";
	$erg = $conn->query($sql);

	if($erg->num_rows > 0)
	{
		$gesamt = 0;
		if(isset($_POST['gesamt'])) $gesamt = $_POST['gesamt'];

		for($o = 0; $o < $settings['Options']['TeamsPerMatch']; $o++)
		{

			if(isset($_POST['team'.$o]))
			{
				$points = ($_POST['team'.$o] - $gesamt) / 3;

				$sql = "UPDATE pointmanagement SET `+3`='".$points."' WHERE game='".$_POST['teamid'.$o]."'";
				$conn->query($sql);
			}
			if(isset($_POST['-1'.$o]))
			{
				$sql = "UPDATE pointmanagement SET `-1`='".$_POST['-1'.$o]."' WHERE game='".$_POST['teamid'.$o]."'";
				$conn->query($sql);
			}
			if(isset($_POST['-3'.$o]))
			{
				$sql = "UPDATE pointmanagement SET `-3`='".$_POST['-3'.$o]."' WHERE game='".$_POST['teamid'.$o]."'";
				$conn->query($sql);
			}
			if(isset($_POST['-5'.$o]))
			{
				$sql = "UPDATE pointmanagement SET `-5`='".$_POST['-5'.$o]."' WHERE game='".$_POST['teamid'.$o]."'";
				$conn->query($sql);
			}
			if(isset($_POST['teamid'.$o]))
			{
				$sql = "UPDATE games SET active=0, finished=1 WHERE gameid='".$_POST['teamid'.$o]."'";
				$conn->query($sql);
			}

			UpdateGame($_POST['teamid'.$o]);
			//UpdateTime();
			UpdateDB();
			header("Location: schiri.php");

		}
	}
	else
	{

	}
		//Beim Absenden checken ob die SPiele bereits finished sind
}
CloseCon($conn);
?>



