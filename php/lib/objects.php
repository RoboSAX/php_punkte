<?php
require_once '../lib/db_main.php';

class Team
{
	/**
	  *	ID of team
	  *
	  * @var integer $teamid
	  */
	private $teamid;
	private $teamleader;
	private	$name;
	private	$points;
	private	$robot;
	private	$position;
	private	$active;
 
	/*! \brief 	Constructor of a Team object.
	 *			Sets all private variables to 0, "" or true (active).
	 *
	 */
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
	/*! \brief Loads team data from the database.
	 *
	 *	This function sends a sql request to the linked database (specified in the settings.ini file)
	 *	to get the data of the team with the ID $id. The data is safed in the private attributes.
	 *
	 *	Returns 'false' if one of the following events occure:
	 *		- $id is not an integer
	 *		- no data for a team with ID: $id ; was found in the database
	 *	
	 *	Returns 'true' if there were no errors
	 *
	 *	'false'-outputs will be logged (if enabled in the settings.ini file)
	 */
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
	/*! \brief Saves team data in the database
	 *
	 *	This function sends a sql request to the linked database (specified in the settings.ini file)
	 *	to safe the objects private attributes in the database.
	 *
	 *	There are two possible uses for this function:
	 *		- if a team with the same ID already exists in the database -> The function updates the values in the database.
	 *		- if no team with the same ID exists -> The function creates a new entry in the database 
	 *		  and updates the ID (also of the object)
	 *		  Use with care, is you may have to manually delete wrong inputs in your database
	 *
	 *	Returns 'true' if one of the following events occure:
	 *		- created a new entry in the database
	 *		- u pdated a existing entry in the database
	 *
	 *	Returns 'false' in all other cases.
	 *	For example: 
	 *		- if two teams with the same ID exists (look for the checks!)
	 *		- if a team with ID = 0 exists (it should not be the case, but you never know)
	 */
	public function save_team_to_db()
	{
		$conn = OpenCon();
		$sql = "SELECT * FROM teams WHERE teamid='".$this->teamid."'";
		$tmp = $conn->query($sql);
		if($tmp->num_rows == 0)
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
		
		CloseCon($conn);
		return false;
	}
	/*!
	 * \brief Returns the atribute $teamid of this object
	 */
	public function get_id()
	{
		return $this->teamid;
	}
	/*!
	 * \brief Returns the atribute $teamleader of this object
	 */
	public function get_teamleader() //!< \brief Returns the atribute $teamleader of this object
	{
		return $this->teamleader;
	}
	/*!
	 * \brief Returns the atribute $points of this object
	 */
	public function get_points() //!< \brief Returns the atribute $points of this object
	{
		return $this->points;
	}
	/*!
	 * \brief Returns the atribute $robot of this object
	 */
	public function get_robot() //!< \brief Returns the atribute $robot of this object
	{
		return $this->robot;
	}
	/*!
	 * \brief Returns the atribute $name of this object
	 */
	public function get_name() //!< \brief Returns the atribute $name of this object
	{
		return $this->name;
	}
	/*!
	 * \brief Returns the atribute $position of this object
	 */
	public function get_position() //!< \brief Returns the atribute $position of this object
	{
		return $this->position;
	}
	/*!
	 * \brief Returns the atribute $active of this object
	 */
	public function get_active()
	{
		return $this->active;
	}
	/*!	\brief Sets $teamid of this object to $tmp
	 *
	 *	Returns 'false' if $tmp is not of type integer.
	 *	Returns 'true' else.
	 */
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
	/*!	\brief Sets $teamleader of this object to $tmp
	 *
	 *	Returns 'false' if $tmp is not of type string.
	 *	Returns 'true' else.
	 */
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
	/*!	\brief Sets $points of this object to $tmp
	 *
	 *	Returns 'false' if $tmp is not of type integer.
	 *	Returns 'true' else.
	 */
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
	/*!	\brief Sets $robot of this object to $tmp
	 *
	 *	Returns 'false' if $tmp is not of type string.
	 *	Returns 'true' else.
	 */
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
	/*!	\brief Sets $name of this object to $tmp
	 *
	 *	Returns 'false' if $tmp is not of type string.
	 *	Returns 'true' else.
	 */
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
	/*!	\brief Sets $position of this object to $tmp
	 *
	 *	Returns 'false' if $tmp is not of type integer.
	 *	Returns 'true' else.
	 */
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
	/*!	\brief Sets $active of this object to $tmp
	 *
	 *	Returns 'false' if $tmp is not of type integer (as 0 is interpreted as false and all other numbers as true) or boolean.
	 *	Returns 'true' else.
	 */
	public function set_active($tmp)
	{
		if(!is_int($tmp) and !is_bool($tmp))
		{
			echo "Wrong datatype. Use Active (BOOL)"; //Durch LOG ersetzen
			return false;
		}
		
		$this->active = bool($tmp);
		return true;
	}
}

class Teams
{
	public $tms = [];
	private $AnzTeam;
	/*! \brief Constructor of Teams objects.
	 *
	 * Sets $tms to an empty array and $AnzTeams to 0.
	 */
	public function __construct()
	{
		$this->tms = [];
		$this->AnzTeam = 0;
	}
	/*! \brief Adds the transmitted object of type Team to tms.
	 *
	 *	Returns 'false' if one of the following events occures:
	 *	- $nt is not an object of class Team.
	 *	- ID of $nt isn't unique in $tms.
	 *
	 *	Returns 'true' if the integration of the transmitted object was successfully.
	 *
	 *	If the ID of $nt is zero, it will change automaticly to size of $tms + 1.
	 */
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
	/*	\brief Removes the team object with the ID $id from $tms.
	 *
	 *	Returns 'true' if an object was successfully deleted from $tms.
	 *
	 *	Returns 'false' if either:
	 *	- $id is not of type integer or
	 *	- no object with the transmitted ID exists in $tms.
	 *
	 */
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
	/*	\brief Loads all teams from the database.
	 *
	 *	This function uses the load_team_from_db($id) function of a Team object, to load all teams from the database.
	 *
	 *	Returns 'false' if one of the following events occure:
	 *	- no data was found in the database.
	 *	- load_team_from_db($id) returned false.
	 *
	 *	Returns 'true' if all teams were loaded successfully.
	 */
	public function load_teams_from_db()
	{
		$conn = OpenCon();
		$sql = "SELECT teamid FROM teams";
		$tmp = $conn->query($sql);
		$db_teams = [];
		if($tmp->num_rows > 0)
		{
			while($row = $tmp->fetch_assoc())
			{
				$db_teams[] = $row['teamid'];
			}
		}
		else
		{
			echo "No data found";
			CloseCon($conn);
			return false;
		}
		
		CloseCon($conn);
		
		for($i = 0; $i < sizeof($db_teams); $i++)
		{
			$tmp2 = new Team();
			if(!($tmp2->load_team_from_db($db_teams[$i])))
			{
				echo "Team with id: ". $db_teams[$i] . " could not be loaded.";
				CloseCon($conn);
				return false;
			}
			$this->add_team($tmp2);
		}
		
		return true;
	}
	/*	\brief Saves the data inside $tms in the database.
	 *
	 *	This function uses the save_team_to_db() function of a Team object in a loop to save the data inside of $tms.
	 *
	 *	Returns 'false' if save_team_to_db() returns false.
	 *
	 *	Returns 'true' if the data could be saved to the database successfully.
	 *
	 */
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
	/*	\brief Returns a Team object with the ID $tmp.
	 *
	 *	This function searches $tms for a Team object with the teamid $tmp and, if found, returns it.
	 *
	 *	Returns an empty Team object, if no Team object with the teamid $tmp exists in $tms.
	 *
	 */
	public function get_team_by_id($tmp)
	{
		if(!is_int($tmp) or $tmp < 1)
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
	/* \brief Orders $tms [desc] by points of the Team objects
	 *
	 */
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
	/*	\brief Orders $tms [asc] by teamid of the Team objects.
	 *
	 */
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
