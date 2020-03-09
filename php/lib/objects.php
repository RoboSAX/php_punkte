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
		$new_id = 0;
		$conn = OpenCon();
		$sql = "SELECT MAX(teamid) FROM teams;";
		$tmp = $conn->query($sql);
		if ($tmp->num_rows == 1) {
			$row = $tmp->fetch_assoc();
			$new_id = $row['MAX(teamid)'] + 1;
		}
		CloseCon($conn);
		$this->teamid = $new_id;
	}
	/*! \brief Loads team data from the database.
	 *
	 *	This function sends a sql request to the linked database (specified in the settings.ini file)
	 *	to get the data of the team with the ID $id. The data is safed in the private attributes.
	 *
	 *	Returns 'false' if one of the following events occure:
	 *		- $id is not an integer
	 *		- no data for a team with ID: $id ; was found in the database
	 *		- more than one entry with ID: $id ; was found in the database
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

		if ($tmp->num_rows < 1) {
			echo "No Data found"; //Durch LOG ersetzen
			return false;
		} elseif ($tmp->num_rows == 1) {
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
		} else {
			echo "More than one entry found"; //Durch LOG ersetzen
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
    public function save_team_to_db() {

        $result = false;
        $conn = OpenCon();
        $sql = "SELECT * FROM teams WHERE teamid='".$this->teamid."'";
        $tmp = $conn->query($sql);
        if($tmp->num_rows == 0) {
            $sql = "INSERT INTO teams (`position`, `name`, `robot`, `points`, `teamleader`, `active`) VALUES (" . $this->position . ", '" . $this->name . "', '" . $this->robot . "', " . $this->points . ", '" . $this->teamleader . "', " . ($this->active ? 'true' : 'false') . ")";
            echo "neu (0): ".$sql."<br>\n";
            $conn->query($sql);
            CloseCon($conn);
            $this->update_id();
            return true;
        } elseif(($this->teamid != 0) && ($tmp->num_rows == 1)) {
            $sql = "UPDATE teams SET `name`='". $this->name ."',
                                     `robot`='". $this->robot ."',
                                     `points`=". $this->points .",
                                     `teamleader`='". $this->teamleader . "',
                                     `position`=". $this->position . ",
                                     `active`=". ($this->active ? 'true' : 'false') . " WHERE `teamid`=". $this->teamid;

            $conn->query($sql);
            $result = true;
        } else {
            echo "error: <br>\n";
        }

        CloseCon($conn);
        return $result;
    }
	/*!
	 * \brief Returns the attribute $teamid of this object
	 */
	public function get_id()
	{
		return $this->teamid;
	}
	/*!
	 * \brief Returns the attribute $teamleader of this object
	 */
	public function get_teamleader()
	{
		return $this->teamleader;
	}
	/*!
	 * \brief Returns the attribute $points of this object
	 */
	public function get_points()
	{
		return $this->points;
	}
	/*!
	 * \brief Returns the attribute $robot of this object
	 */
	public function get_robot()
	{
		return $this->robot;
	}
	/*!
	 * \brief Returns the attribute $name of this object
	 */
	public function get_name()
	{
		return $this->name;
	}
	/*!
	 * \brief Returns the attribute $position of this object
	 */
	public function get_position()
	{
		return $this->position;
	}
	/*!
	 * \brief Returns the attribute $active of this object
	 */
	public function get_active()
	{
		return $this->active;
	}
	# 2019 11 11 wepet: teamid should not be changed by the user!
	#/*!	\brief Sets $teamid of this object to $tmp
	# *
	# *	Returns 'false' if $tmp is not of type integer.
	# *	Returns 'true' else.
	# */
	#public function set_id($tmp)
	#{
	#	if(!is_int($tmp) OR $tmp < 0)
	#	{
	#		echo "Wrong datatype or input. Use TeamID (pos INT)";
	#		return false;
	#	}
    #
	#	$this->teamid = $tmp;
	#	return true;
	#}
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
		$this->active = (BOOL)$tmp;
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
				echo "TeamID already exists. Please change ID";
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
	/*!	\brief Removes the team object with the ID $id from $tms.
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
	/*!	\brief Loads all teams from the database.
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
			if(!($tmp2->load_team_from_db((int)$db_teams[$i])))
			{
				echo "Team with id: ". $db_teams[$i] . " could not be loaded.";
				CloseCon($conn);
				return false;
			}
			$this->add_team($tmp2);
		}

		return true;
	}
	/*!	\brief Saves the data inside $tms in the database.
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
	/*!	\brief Returns a Team object with the ID $tmp.
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
	/*! \brief Orders $tms [desc] by points of the Team objects
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
	/*!	\brief Orders $tms [asc] by teamid of the Team objects.
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
	private $gameid, $block, $time_start, $time_act, $points, $teamid, $active, $finished, $highlight;
	public function __construct()
	{
		$this->gameid = 0;
		$this->block = 0;
		$this->time_start = 0;
		$this->time_act = 0;
		$this->points = 0;
		$this->teamid = 0;
		$this->active = 0;
		$this->finished = 0;
		$this->highlight = 0;
	}
	private function update_id()
	{
		$new_id = 0;
		$conn = OpenCon();
		$sql = "SELECT MAX(gameid) FROM games;";
		$tmp = $conn->query($sql);
		if ($tmp->num_rows == 1) {
			$row = $tmp->fetch_assoc();
			$new_id = $row['MAX(gameid)'] + 1;
		}
		CloseCon($conn);
		$this->gameid = $new_id;
	}
	/*! \brief Loads game data from the database.
	 *
	 *	This function sends a sql request to the linked database (specified in the settings.ini file)
	 *	to get the data of the game with the ID $id. The data is safed in the private attributes.
	 *
	 *	Returns 'false' if one of the following events occure:
	 *		- $id is not an integer
	 *		- no data for a game with ID: $id ; was found in the database
	 *
	 *	Returns 'true' if there were no errors
	 *
	 *	'false'-outputs will be logged (if enabled in the settings.ini file)
	 */
	public function load_game_from_db($id)
	{
		if(!is_int($id))
		{
			echo 'Wrong datatype. Use GameID (INT)'; //Durch LOG ersetzen, sonst müllt das die Seite zu
			return false;
		}
		$conn  = OpenCon();
		$sql = 'SELECT * FROM games WHERE gameid='.$id;
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
				$this->teamid = $row['teamid'];
				$this->active = $row['active'];
				$this->finished = $row['finished'];
				$this->highlight = $row['highlight'];
			}
		}
		else
		{
			echo 'No Data found'; //Durch LOG ersetzen
			return false;
		}

		return true;
	}
	/*! \brief Saves game data in the database
	 *
	 *	This function sends a sql request to the linked database (specified in the settings.ini file)
	 *	to safe the objects private attributes in the database.
	 *
	 *	There are two possible uses for this function:
	 *		- if a game with the same ID already exists in the database -> The function updates the values in the database.
	 *		- if no game with the same ID exists -> The function creates a new entry in the database
	 *		  and updates the ID (also of the object)
	 *		  Use with care, is you may have to manually delete wrong inputs in your database
	 *
	 *	Returns 'true' if one of the following events occure:
	 *		- created a new entry in the database
	 *		- updated a existing entry in the database
	 *
	 *	Returns 'false' in all other cases.
	 *	For example:
	 *		- if two games with the same ID exists (look for the checks!)
	 *		- if a game with ID = 0 exists (it should not be the case, but you never know)
	 */
	public function save_game_to_db()
	{
		$conn = OpenCon();
		$sql = 'SELECT * FROM games WHERE gameid='.$this->gameid;
		$tmp = $conn->query($sql);
		if($tmp->num_rows == 0)
		{
			$sql = 'INSERT INTO games (`block`, `time_start`, `time_act`, `points`, `teamid`, `active`, `finished`, `highlight`)
					VALUES (' . $this->block . ', ' . $this->time_start . ', ' . $this->time_act . ', ' . $this->points . ', ' .
					', ' . $this->teamid . ', ' . $this->active . ', ' . $this->finished . ', ' . $this->highlight . ')';
			$conn->query($sql);
			CloseCon($conn);
			$this->update_id();
			return true;
		}
		elseif($this->gameid != 0 AND $tmp->num_rows == 1)
		{
			$sql = "UPDATE games SET `block`=". $this->block .",
									 `time_start`=". $this->time_start .",
									 `time_act`=". $this->time_act .",
									 `points`=". $this->points . ",
									 `teamid`=". $this->teamid .",
									 `active`=". $this->active .",
									 `finished`=". $this->finished .",
									 `highlight`=". $this->hightlight ." WHERE `gameid`=". $this->gameid;
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
	/*!
	 * \brief Returns the attribute $block of this object
	 */
	public function get_block()
	{
		return $this->block;
	}
	/*!
	 * \brief Returns the attribute $gameid of this object
	 */
	public function get_id()
	{
		return $this->gameid;
	}
	/*!
	 * \brief Returns both time attributes of this object
	 *
	 *	Returns $time_start and $time_act in an array, where $time_start has the index 0 and $time_act has the index 1.
	 */
	public function get_time()
	{
		return array('time_start' => $this->time_start, 'time_act' => $this->time_act);
	}
	/*!
	 * \brief Returns the attribute $points of this object
	 */
	public function get_points()
	{
		return $this->points;
	}
	/*!
	 * \brief Returns the attribute $team of this object
	 */
	public function get_team()
	{
		return $this->teamid;
	}
	/*!
	 * \brief Returns the attribute $active of this object
	 */
	public function get_active()
	{
		return $this->active;
	}
	/*!
	 * \brief Returns the attribute $finished of this object
	 */
	public function get_finished()
	{
		return $this->finished;
	}
	/*!
	 * \brief Returns the attribute $highlight of this object
	 */
	public function get_highlight()
	{
		return $this->highlight;
	}
	/*!	\brief Set planed starting time ($time_start) and actual starting time ($time_act)
	 *
	 *	Requires and array of 2 integers, first of which is $time_start and the second one will be $time_act.
	 *	Its possible to use the keys "time_start" and "time_act" instead.
	 *
	 *
	 *	Returns false in one of the following events:
	 *	- transmitted data is not an array
	 *	- transmitted array has not exactly 2 elements.
	 *	- one of the elements of the array is not of type integer.
	 *
	 *	Returns true if the transmitted data could be safed successfully.
	 */
	public function set_time($tmp)
	{
		if(!is_array($tmp))
		{
			echo "Wrong Datatype. Use array of 2 integer: [time_start, time_act]";	//LOG
			return false;
		}
		if(!is_int($tmp[0]) or !is_int($tmp[1]) or sizeof($tmp) != 2)
		{
			echo "Wrong Datatype. Use array of 2 integer: [time_start, time_act]";	//LOG
			return false;
		}

		if(array_key_exists("time_start",$tmp) and array_key_exists("time_act",$tmp))
		{
			$this->time_start = $tmp["time_start"];
			$this->time_act = $tmp["time_act"];
			return true;
		}
		else
		{
			$this->time_start = $tmp[0];
			$this->time_act = $tmp[1];
			return true;
		}
	}
	/*! \brief Set block number
	 *
	 *	Returns false if $tmp is not of type integer.
	 *
	 *	Returns true when block was set successfully.
	 */
	public function set_block($tmp)
	{
		if(!is_int($tmp))
		{
			echo "Wrong datatype. Use Block (INT)";
			return false;
		}

		$this->block = $tmp;
		return true;
	}
	/*! \brief Set points
	 *
	 *	Returns false if $tmp is not of type integer.
	 *
	 *	Returns true when points were set successfully.
	 */
	public function set_points($tmp)
	{
		if(!is_int($tmp))
		{
			echo "Wrong datatype. Use Points (INT)";
			return false;
		}

		$this->points = $tmp;
		return true;
	}
	/*! \brief Set the ID of the team for this game
	 *
	 *	Returns false if $tmp is not of type integer.
	 *
	 *	Returns true when team was set successfully.
	 */
	public function set_team()
	{
		if(!is_int($tmp))
		{
			echo "Wrong datatype. Use TeamID (INT)";
			return false;
		}

		$this->teamid = $tmp;
		return true;
	}
	/*! \brief Set active status
	 *
	 *	Returns false if $tmp is not of type boolean.
	 *
	 *	Returns true when active was set successfully.
	 */
	public function set_active($tmp)
	{
		if(!is_bool($tmp))
		{
			echo "Wrong datatype. Use Active (BOOL)";
			return false;
		}

		$this->active = $tmp;
		return true;

	}
	/*! \brief Set finished status
	 *
	 *	Returns false if $tmp is not of type boolean.
	 *
	 *	Returns true when finished was set successfully.
	 */
	public function set_finished($tmp)
	{
		if(!is_bool($tmp))
		{
			echo "Wrong datatype. Use Finished (BOOL)";
			return false;
		}

		$this->finished = $tmp;
		return true;
	}
	/*! \brief Set highlight status
	 *
	 *	Returns false if $tmp is not of type boolean.
	 *
	 *	Returns true when highlight was set successfully.
	 */
	public function set_highlight($tmp)
	{
		if(!is_bool($tmp))
		{
			echo "Wrong datatype. Use Highlight (BOOL)";
			return false;
		}

		$this->highlight = $tmp;
		return true;
	}
}

class Block
{
	public $games = [];
	private $AnzGames = 0;
	private $time_start;
	private $options;
	private $blocknumber;

	private function time_add($time1, $time2)
	{
		if($time2 > $time1)
		{
			$tmp = $time1;
			$time1 = $time2;
			$time2 = $tmp;
		}

		$h = (int)($time1/100) + (int)($time2/100);
		$m = (int)($time1%100) + (int)($time2%100);

		if($m > 59)
		{
			$h++;
			$m -= 60;
		}

		return (100 * $h) + $m;
	}
	private function check_start_time()
	{
		if($this->blocknumber < 2)
		{
			//TODO LOG
			return false;
		}

		$conn = OpenCon();
		$sql = 'SELECT time_act FROM games WHERE block=' . $this->blocknumber - 1 . ' AND time_act > ' . time_add($this->time_start, 5) . ' ORDER BY time_act DESC LIMIT 1';
		$result = query($sql);

		if($result->num_rows < 1)
		{
			//TODO LOG
			return false;
		}

		$res = $result->fetch_assoc();
		$this->time_start = time_add($res['time_act'], 5);

		for($i = 0, $j = 0; $i < $this->AnzGames; $i++)
		{
			$time = $this->games[$i]->get_time();

			if($i%$settings['Options']['TeamsPerMatch'] == 0)
			{
				$time = [time_add($j*5, $this->blocktime), 0];
				$j++;
			}

			$this->games[$i]->set_time($time);

		}

		CloseCon($conn);
		return true;
	}
	private function check_time()
	{
		for($i = 1, $found = false; $i < $this->AnzGames; $i++)
		{
			$time1 = $this->games[$i]->get_time();
			$time2 = $this->games[$i - 1]->get_time();
			if($time2['time_act'] > $time1['time_start'])
			{
				$time1['time_start'] = time_add($time2['time_act'], 5);
				$this->games[$i]->set_time($time1);
				$found = true;
			}
			if($found)
			{
				$time1['time_start'] = time_add($time2['time_start'], 5);
				$this->games[$i]->set_time($time1);
			}
		}
	}
	public function __construct($block = 'NXT')
	{
		if(is_string($block))
		{
			if(!in_array($block, ['CUR','NXT']))
			{
				echo 'Wrong input. Using NXT instead.';
				$block = 'NXT';
			}

			$conn = OpenCon();
			$sql = 'SELECT MAX(block) FROM games';
			$result = $conn->query($sql);
			if($result->num_rows < 1)
			{
				$this->blocknumber = 1;
			}
			else
			{
				$res = $result->fetch_assoc();
				$this->blocknumber = $res['MAX(block)'];
			}

			if($block == 'CUR' and $this->blocknumber > 1) $this->blocknumber--;

		}
		if(is_int($block) and $block > 0 and $block <= 6) $this->blocknumber = $block;

		$this->options = $settings['Blockoptions']['Block'.$this->blocknumber];
		$this->time_start = $settings['Blocktime']['Block'.$this->blocknumber];
		/*
		if(strpos($this->options, 'fb') === true)
		{
			$sql = 'SELECT teamid FROM teams WHERE active = 1 ORDER BY points DESC position ASC';
			$result = query($sql);

			if($result->num_rows < 1)
			{
				//TODO LOG
				break;
			}
			else
			{
				for($j = 0; $j < $result->num_rows; $j++, $res = $result->fetch_assoc())
				{
					$tmp = new Game();

					$tmp->set_team($res['teamid']);
					$tmp->set_time([time_add($j*5, $this->blocktime), 0]);
					$tmp->set_block($this->blocknumber);
					$tmp->safe_game_to_db();

					$this->games[$j] = $tmp;
					$this->AnzGames++;
				}
			}
		}
		else */

		$sql = 'SELECT gameid FROM games INNER JOIN teams WHERE games.teamid = teams.teamid AND teams.active = 1 AND games.block='.$this->blocknumber.' ORDER BY teams.points DESC, teams.position ASC';
		$result = query($sql);
		for($i = 1, $j = 0; $res = $result->fetch_assoc(); $i++)
		{
			$tmp = new Game();
			$tmp->load_game_from_db($res['gameid']);
			$times = $tmp->get_time();

			if($times['time_start'] == 0)
			{
				$tmp->set_time([time_add($j*5, $this->blocktime), 0]);
				$tmp->save_game_to_db();
			}
			if($i%$settings['Options']['TeamsPerMatch'] == 0) $j++;
				
			$this->games[$i-1] = $tmp;
			$this->AnzGames++;
		}

		CloseCon($conn);

		check_start_time();
		check_time();
	}
	/*
	TODO move, cause functionallity isnt set for block objects
	
	public function add_Game($tmp)
	{
		if(!is_a($tmp, 'Game'))
		{
			//TODO LOG
			return false;
		}

		$newGame = new Game();
		$newGame->set_team($tmp->get_team());
		$newGame->set_block($tmp->get_block() + 1);
		
		return $newGame->save_game_to_db();
	}
	*/
	public function get_AnzGames()
	{
		return  $this->AnzGames;
	}
}

?>
