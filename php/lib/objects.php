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
			CloseCon($conn);
			return false;
		}
		
		CloseCon($conn);
		return true;
	}
	public function save_team_to_db()
	{
		$conn = OpenCon();
		$sql = "SELECT * FROM teams WHERE teamid='".$this->teamid."'";
		$tmp = $conn->query($sql);
		if($this->teamid != 0 OR $tmp->num_rows == 0)
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
	public function set_id($tmp)
	{
		if(!is_int($tmp) OR $tmp < 0)
		{
			echo "Wrong datatype or input. Use TeamID (pos INT)";
			return false;
		}
		
		$this->teamid = $tmp;
		return true;
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
		if(!is_int($tmp) OR $tmp < 0)
		{
			echo "Wrong datatype or input. Use Points (pos INT)"; //Durch LOG ersetzen
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
		if(!is_int($tmp) OR $tmp < 0)
		{
			echo "Wrong datatype or input. Use Position (pos INT)"; //Durch LOG ersetzen
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
	private $tms, $AnzTeam;
	public function __construct()
	{
		$this->tms = [];
		$this->AnzTeam = 0;
	}
	public function add_team($nt)
	{
		if(!is_a($nt, "Team"))
		{
			echo "Wrong Datatype. Use Object of Class 'Team'";
			return false;
		}
		
		for($i = 0; $i < $this->AnzTeam; $i++) //To make sure that IDs stay unique
		{
			if($this->tms[$i]->get_id() == $nt->get_id())
			{
				echo "TeamID allready exists. Please change ID";
				return false;
			}
		}
	
		if($nt->get_id() == 0)	//If the team doesnt have a valid ID yet, it just gets the last ID + 1 of all listed teams in the private array tms
		{
			$nt->set_id(sizeof($this->tms) + 1);
		}
		
		array_push($this->tms, $nt);
		$this->AnzTeam++;
		return true;
		
	}
	public function remove_team($id)
	{
		if(!is_int($id)) 
		{
			echo "Wrong Datatype. Use TeamID (INT)";
			return false;
		}
		
		for($i = 0; $i < sizeof($this->tms); $i++)
		{
			if($id == $this->tms[$i]->get_id())
			{
				array_splice($this->tms, $i, 1);
				$this->AnzTeam--;
				return true;
			}
		}
		
		return false;
	}
	public function get_teams()
	{
		return $this->tms;
	}
	public function load_teams_from_db()
	{
		$conn = OpenCon();
		$sql = "SELECT COUNT(*) FROM teams";
		$tmp = $conn->query($sql);
		$row = $tmp->fetch_assoc();
		$db_teams = $row['COUNT(*)'];
		CloseCon($conn);
		
		for($i = 0; $i < $db_teams; $i++)
		{
			$tmp2 = new Team();
			if(!($tmp2->load_team_from_db($i+1)))
			{
				echo "Team with id: ". $i . " could not be loaded.";
				CloseCon($conn);
				return false;
			}
			$this->add_team($tmp2);
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
	public function order_teams_by_points()
	{
		if(!sizeof($this->tms)) return false;
		$array_out = [];
		$used = [];
		
		while(sizeof($array_out) != sizeof($this->tms))
		{
			$tmp = 0;
			
			for($i = 0; $i < sizeof($this->tms); $i++)
			{
				if($this->tms[$i]->get_points() >= $this->tms[$tmp]->get_points() AND !in_array($i, $used))
				{
					$tmp = $i;
				}
			}
			
			array_push($used, $tmp);
			array_push($array_out, $this->tms[$tmp]);
		}
		
		$this->tms = $array_out;
		return true;
		
	}
	public function order_teams_by_id()
	{
		if(sizeof($this->tms) == 0) return false;
		$array_out = [];
		$used = [];
		
		while(sizeof($array_out) != sizeof($this->tms))
		{
			$tmp = 0;
			
			for($i = 0; $i < sizeof($this->tms); $i++)
			{
				if($this->tms[$i]->get_id() <= $this->tms[$tmp]->get_id() AND !in_array($i, $used))
				{
					$tmp = $i;
				}
			}
			
			array_push($used, $tmp);
			array_push($array_out, $this->tms[$tmp]);
		}
		
		$this->tms = $array_out;
		return true;
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
	{
		$conn = OpenCon();
		$sql = "SELECT * FROM games WHERE gameid='".$this->gameid."'";
		$tmp = $conn->query($sql);
		if($this->gameid != 0 AND $tmp->num_rows == 0)
		{	
			$sql = "INSERT INTO games (`block`, `time_start`, `time_act`, `points`, `objectives`, `penalties`, `team`, `active`, `finished`, `highlight`, `teamactive`) 
					VALUES (" . $this->block . ", " . $this->time_start . ", " . $this->time_act . ", " . $this->points . ", " . $this->objectives . ", " . $this->penalties . 
					", " . $this->team . ", " . $this->active . ", " . $this->finished . ", " . $this->highlight . ", " . $this->teamactive . ")";
			$conn->query($sql);		
			CloseCon($conn);
			$this->update_id();
			return true;
		}
		elseif($this->teamid != 0 AND $tmp->num_rows == 1)
		{
			$sql = "UPDATE games SET `block`=". $this->block .",
									 `time_start`=". $this->time_start .",
									 `time_act`=". $this->time_act .",
									 `points`=". $this->points . ",
									 `objectives`=". $this->objectives . ",
									 `penalties`=". $this->penalties .",
									 `team`=". $this->team .",
									 `active`=". $this->active .",
									 `finished`=". $this->finished .",
									 `highlight`=". $this->hightlight .",
									 `teamactive`=". $this->teamactive . " WHERE `gameid`=". $this->gameid;
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
}

?>
