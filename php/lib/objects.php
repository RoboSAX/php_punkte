<?php
require_once '../lib/db_main.php';

class Team
{
	private $teamid, $teamleader, $points, $robot, $name, $position, $active;

	public function __construct()
	{
		$this->teamid = 0;
		$this->teamleader = "";
		$this->points = 0;
		$this->robot = "";
		$this->name = "";
		$this->position = 0;
		$this->active = true;
	}
	private function update_id()
	{
		$conn = OpenCon();
		$sql = "SELECT COUNT(*) FROM teams";
		$tmp = $conn->query($sql);
		$row = $tmp->fetch_assoc();
		$new_id = $row['COUNT(*)'] + 1;
		$this->teamid = $new_id;
		CloseCon($conn);
	}
	public function load_team_from_db($id)
	{
		if(!is_int($id))
		{
			echo "Wrong datatype. Use TeamID (INT)"; //Durch LOG ersetzen, sonst müllt das die Seite zu
			return false;
		}
		$conn  = OpenCon();
		$sql = "SELECT * FROM teams WHERE teamid='".$id."'";
		$tmp = $conn->query($sql);
		
		if($tmp->num_rows > 0)
		{
			while($row = $tmp->fetch_assoc())
			{
				$this->teamid = $row['teamid'];
				$this->teamleader = $row['teamleader'];
				$this->points = $row['points'];
				$this->robot = $row['robot'];
				$this->name = $row['name'];
				$this->position = $row['position'];
				$this->active = $row['active'];
			}
		}
		else
		{
			echo "No Data found"; //Durch LOG ersetzen
			return false;
		}
		
		return true;
	}
	public function save_team_to_db()
	{
		$conn = OpenCon();
		$sql = "SELECT * FROM teams WHERE teamid='".$this->teamid."'";
		$tmp = $conn->query($sql);
		if($this->teamid != 0 AND $tmp->num_rows == 0)
		{	
			$sql = "INSERT INTO teams (`position`, `name`, `robot`, `points`, `teamleader`, `active`) VALUES (" . $this->position . ", '" . $this->name . "', '" . $this->robot . "', " . $this->points . ", '" . $this->teamleader . "', " . $this->active . ")";
			$conn->query($sql);		
			CloseCon($conn);
			$this->update_id();
			return true;
		}
		elseif($this->teamid != 0 AND $tmp->num_rows == 1)
		{
			$sql = "UPDATE teams SET `name`='". $this->name ."',
									 `robot`='". $this->robot ."',
									 `points`=". $this->points .",
									 `teamleader`='". $this->teamleader . "',
									 `position`=". $this->position . ",
									 `active`=". $this->active . " WHERE `teamid`=". $this->teamid;
			echo $sql;
			$conn->query($sql);
			CloseCon($conn);
			return true;
		}
		else
		{
			CloseCon($conn);
			//Noch irgendein LOG
			return false;
		}
		
		return false;
	}
	public function get_id()
	{
		return $this->teamid;
	}
	public function get_teamleader()
	{
		return $this->teamleader;
	}
	public function get_points()
	{
		return $this->points;
	}
	public function get_robot()
	{
		return $this->robot;
	}
	public function get_name()
	{
		return $this->name;
	}
	public function get_position()
	{
		return $this->position;
	}
	public function get_active()
	{
		return $this->active;
	}
	public function set_teamleader($tmp)
	{
		if(!is_string($tmp))
		{
			echo "Wrong datatype. Use Teamleader (STR)"; //Durch LOG ersetzen
			return false;
		}
		
		$this->teamleader = $tmp;
		return true;
	}
	public function set_points($tmp)
	{
		if(!is_int($tmp))
		{
			echo "Wrong datatype. Use Points (INT)"; //Durch LOG ersetzen
			return false;
		}
		
		$this->points = $tmp;
		return true;
	}
	public function set_robot($tmp)
	{
		if(!is_string($tmp))
		{
			echo "Wrong datatype. Use Robot (STR)"; //Durch LOG ersetzen
			return false;
		}
		
		$this->robot = $tmp;
		return true;
	}
	public function set_name($tmp)
	{
		if(!is_string($tmp))
		{
			echo "Wrong datatype. Use Name (STR)"; //Durch LOG ersetzen
			return false;
		}
		
		$this->name = $tmp;
		return true;
	}
	public function set_position($tmp)
	{
		if(!is_int($tmp))
		{
			echo "Wrong datatype. Use Position (INT)"; //Durch LOG ersetzen
			return false;
		}
		
		$this->position = $tmp;
		return true;
	}
	public function set_active($tmp)
	{
		if(!is_int($tmp))
		{
			echo "Wrong datatype. Use Active (BOOL)"; //Durch LOG ersetzen
			return false;
		}
		
		$this->active = $tmp;
		return true;
	}
}

class Teams
{
	private $tms = [], $AnzTeam;
	public function __construct()
	{
		$settings_filename='settings.ini';					//Keine Ahnung warum, aber offensichtlich kann eine Funktion nicht auf global variablen zugreifen
		$settings_file='../../config/'.$settings_filename;
		if (!file_exists($settings_file)) {
			die("Can't find ".$settings_filename."!");
		}
		$settings = parse_ini_file($settings_file,true);
		
		$this->AnzTeam = $settings['Options']['AnzTeams'];
		
		for($i = 0; $i < $this->AnzTeam; $i++)
		{
			$tmp = new Team();
			array_push($this->tms, $tmp);
		}
	}
	public function get_teams()
	{
		return $this->tms;
	}
	public function load_teams_from_db()
	{
		for($i = 0; $i < $this->AnzTeam; $i++)
		{
			if(!($this->tms[$i]->load_team_from_db($i+1)))
			{
				echo "Team with id: ". $this->tms[$i]->get_id() . " could not be loaded.";
				return false;
			}
		}
		
		return true;
	}
	public function save_teams_to_db()
	{
		for($i = 0; $i < $this->AnzTeam; $i++)
		{
			if(!($this->tms[$i]->save_team_to_db()))
			{
				echo "ERROR by team with id: ". $this->tms[$i]->get_id(); //LOG wieder
				return false;
			}
		}
		
		return true;
	}
	public function get_team_by_id($tmp)
	{
		if(!is_int($tmp))
		{
			echo "Wrong datatype. Use TeamID (INT)"; //Durch LOG ersetzen
			return;
		}
		
		for($i = 0; $i < sizeof($this->tms); $i++)
		{
			if($this->tms[$i]->get_id() == $tmp)
			{
				return $this->tms[$i];
			}
		}
		echo "No Team with ID".$tmp."found"; //Durch LOG ersetzen
	}	
}

class Game
{
	private $gameid, $block, $time_start, $time_act, $points, $objectives, $penalties, $team, $active, $finished, $highlight, $teamactive;
	public function __construct()
	{
		$this->gameid = 0;
		$this->block = 0;
		$this->time_start = 0;
		$this->time_act = 0;
		$this->points = 0;
		$this->objectives = 0;
		$this->penalties = 0;
		$this->team = 0;
		$this->active = 0;
		$this->finished = 0;
		$this->highlight = 0;
		$this->teamactive = 0;
	}
	public function load_game_from_db($id)
	{
		if(!is_int($id))
		{
			echo "Wrong datatype. Use GameID (INT)"; //Durch LOG ersetzen, sonst müllt das die Seite zu
			return false;
		}
		$conn  = OpenCon();
		$sql = "SELECT * FROM games WHERE gameid='".$id."'";
		$tmp = $conn->query($sql);
		
		if($tmp->num_rows > 0)
		{
			while($row = $tmp->fetch_assoc())
			{
				$this->gameid = $row['gameid'];
				$this->block = $row['block'];
				$this->time_start = $row['time_start'];
				$this->time_act = $row['time_act'];
				$this->points = $row['points'];
				$this->objectives = $row['objectives'];
				$this->penalties = $row['penalties'];
				$this->team = $row['team'];
				$this->active = $row['active'];
				$this->finished = $row['finished'];
				$this->highlight = $row['highlight'];
				$this->teamactive = $row['teamactive'];
			}
		}
		else
		{
			echo "No Data found"; //Durch LOG ersetzen
			return false;
		}
		
		return true;
	}
	public function save_game_to_db()
	{/*
		$conn = OpenCon();
		$sql = "SELECT * FROM games WHERE gameid=" . $this->gameid;
		$tmp = $conn->query($sql);
		if($this->gameid != 0 AND $tmp->num_rows == 0)
		{
			$sql = "INSERT INTO `games` (`gameid`"
		}
		
		*/
	}
}

?>
