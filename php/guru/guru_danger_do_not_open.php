<?php
    # include main function for settings and database connection
    include_once '../lib/db_main.php';
?>

<?php
$conn = OpenCon();
?>

<form action='guru_danger_do_not_open.php' method='post'><button type='submit' name='safety'>Datenbank wirklich neu aufsetzen?</button></form>

<?php
if(isset($_POST['safety']))
{

	$conn->query('SET foreign_key_checks = 0');
	if ($result = $conn->query("SHOW TABLES"))
	{
		while($row = $result->fetch_array(MYSQLI_NUM))
		{
			$conn->query('DROP TABLE IF EXISTS '.$row[0]);
		}
	}

	$conn->query('SET foreign_key_checks = 1');

	$sql = "CREATE TABLE `changed` (
	  `changedid` int(11) NOT NULL,
	  `game` int(11) NOT NULL,
	  `time` tinyint(1) NOT NULL,
	  `points` tinyint(1) NOT NULL,
	  `objectives` tinyint(1) NOT NULL,
	  `penalties` tinyint(1) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
	$conn->query($sql);

	$sql = "
	INSERT INTO `changed` (`changedid`, `game`, `time`, `points`, `objectives`, `penalties`) VALUES
	(1, 1, 0, 0, 0, 0),
	(2, 2, 0, 0, 0, 0),
	(3, 3, 0, 0, 0, 0),
	(4, 4, 0, 0, 0, 0),
	(5, 5, 0, 0, 0, 0),
	(6, 6, 0, 0, 0, 0),
	(7, 7, 0, 0, 0, 0),
	(8, 8, 0, 0, 0, 0),
	(9, 9, 0, 0, 0, 0),
	(10, 10, 0, 0, 0, 0),
	(11, 11, 0, 0, 0, 0),
	(12, 12, 0, 0, 0, 0),
	(13, 13, 0, 0, 0, 0),
	(14, 14, 0, 0, 0, 0),
	(15, 15, 0, 0, 0, 0),
	(16, 16, 0, 0, 0, 0),
	(17, 17, 0, 0, 0, 0),
	(18, 18, 0, 0, 0, 0),
	(19, 19, 0, 0, 0, 0),
	(20, 20, 0, 0, 0, 0),
	(21, 21, 0, 0, 0, 0),
	(22, 22, 0, 0, 0, 0),
	(23, 23, 0, 0, 0, 0),
	(24, 24, 0, 0, 0, 0),
	(25, 25, 0, 0, 0, 0),
	(26, 26, 0, 0, 0, 0),
	(27, 27, 0, 0, 0, 0),
	(28, 28, 0, 0, 0, 0),
	(29, 29, 0, 0, 0, 0),
	(30, 30, 0, 0, 0, 0),
	(31, 31, 0, 0, 0, 0),
	(32, 32, 0, 0, 0, 0),
	(33, 33, 0, 0, 0, 0),
	(34, 34, 0, 0, 0, 0),
	(35, 35, 0, 0, 0, 0),
	(36, 36, 0, 0, 0, 0),
	(37, 37, 0, 0, 0, 0),
	(38, 38, 0, 0, 0, 0),
	(39, 39, 0, 0, 0, 0),
	(40, 40, 0, 0, 0, 0),
	(41, 41, 0, 0, 0, 0),
	(42, 42, 0, 0, 0, 0),
	(43, 43, 0, 0, 0, 0),
	(44, 44, 0, 0, 0, 0),
	(45, 45, 0, 0, 0, 0),
	(46, 46, 0, 0, 0, 0),
	(47, 47, 0, 0, 0, 0),
	(48, 48, 0, 0, 0, 0),
	(49, 49, 0, 0, 0, 0),
	(50, 50, 0, 0, 0, 0),
	(51, 51, 0, 0, 0, 0),
	(52, 52, 0, 0, 0, 0),
	(53, 53, 0, 0, 0, 0),
	(54, 54, 0, 0, 0, 0),
	(55, 55, 0, 0, 0, 0),
	(56, 56, 0, 0, 0, 0),
	(57, 57, 0, 0, 0, 0),
	(58, 58, 0, 0, 0, 0),
	(59, 59, 0, 0, 0, 0),
	(60, 60, 0, 0, 0, 0),
	(61, 61, 0, 0, 0, 0),
	(62, 62, 0, 0, 0, 0),
	(63, 63, 0, 0, 0, 0),
	(64, 64, 0, 0, 0, 0),
	(65, 65, 0, 0, 0, 0),
	(66, 66, 0, 0, 0, 0),
	(67, 67, 0, 0, 0, 0),
	(68, 68, 0, 0, 0, 0),
	(69, 69, 0, 0, 0, 0),
	(70, 70, 0, 0, 0, 0),
	(71, 71, 0, 0, 0, 0),
	(72, 72, 0, 0, 0, 0),
	(73, 73, 0, 0, 0, 0),
	(74, 74, 0, 0, 0, 0),
	(75, 75, 0, 0, 0, 0),
	(76, 76, 0, 0, 0, 0),
	(77, 77, 0, 0, 0, 0),
	(78, 78, 0, 0, 0, 0),
	(79, 79, 0, 0, 0, 0),
	(80, 80, 0, 0, 0, 0),
	(81, 81, 0, 0, 0, 0),
	(82, 82, 0, 0, 0, 0),
	(83, 83, 0, 0, 0, 0),
	(84, 84, 0, 0, 0, 0),
	(85, 85, 0, 0, 0, 0),
	(86, 86, 0, 0, 0, 0),
	(87, 87, 0, 0, 0, 0),
	(88, 88, 0, 0, 0, 0),
	(89, 89, 0, 0, 0, 0),
	(90, 90, 0, 0, 0, 0),
	(91, 91, 0, 0, 0, 0),
	(92, 92, 0, 0, 0, 0),
	(93, 93, 0, 0, 0, 0),
	(94, 94, 0, 0, 0, 0),
	(95, 95, 0, 0, 0, 0),
	(96, 96, 0, 0, 0, 0);
	";
	$conn->query($sql);

	$sql = "CREATE TABLE `games` (
	  `gameid` int(11) NOT NULL,
	  `block` int(11) NOT NULL,
	  `time` int(11) NOT NULL,
	  `points` int(11) NOT NULL,
	  `objectives` int(11) NOT NULL,
	  `penalties` int(11) NOT NULL,
	  `team` int(11) NOT NULL,
	  `active` tinyint(1) NOT NULL,
	  `finished` tinyint(1) NOT NULL,
	  `highlight` tinyint(1) NOT NULL,
	  `teamactive` tinyint(1) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	";
	$conn->query($sql);

	$sql = "INSERT INTO `games` (`gameid`, `block`, `time`, `points`, `objectives`, `penalties`, `team`, `active`, `finished`, `highlight`, `teamactive`) VALUES
	(1, 1, 1430, 0, 0, 0, 1, 0, 0, 0, 1),
	(2, 1, 1425, 0, 0, 0, 2, 0, 0, 0, 1),
	(3, 1, 1430, 0, 0, 0, 3, 0, 0, 0, 1),
	(4, 1, 1435, 0, 0, 0, 4, 0, 0, 0, 1),
	(5, 1, 1425, 0, 0, 0, 5, 0, 0, 0, 1),
	(6, 1, 1435, 0, 0, 0, 6, 0, 0, 0, 1),
	(7, 1, 1440, 0, 0, 0, 7, 0, 0, 0, 1),
	(8, 1, 1440, 0, 0, 0, 8, 0, 0, 0, 1),
	(9, 1, 1445, 0, 0, 0, 9, 0, 0, 0, 1),
	(10, 1, 1445, 0, 0, 0, 10, 0, 0, 0, 1),
	(11, 1, 1450, 0, 0, 0, 11, 0, 0, 0, 1),
	(12, 1, 1450, 0, 0, 0, 12, 0, 0, 0, 1),
	(13, 1, 1455, 0, 0, 0, 13, 0, 0, 0, 1),
	(14, 1, 1455, 0, 0, 0, 14, 0, 0, 0, 1),
	(15, 1, 1500, 0, 0, 0, 15, 0, 0, 0, 1),
	(16, 1, 1500, 0, 0, 0, 16, 0, 0, 0, 1),
	(17, 2, 0, 0, 0, 0, 1, 0, 0, 0, 1),
	(18, 2, 0, 0, 0, 0, 2, 0, 0, 0, 1),
	(19, 2, 0, 0, 0, 0, 3, 0, 0, 0, 1),
	(20, 2, 0, 0, 0, 0, 4, 0, 0, 0, 1),
	(21, 2, 0, 0, 0, 0, 5, 0, 0, 0, 1),
	(22, 2, 0, 0, 0, 0, 6, 0, 0, 0, 1),
	(23, 2, 0, 0, 0, 0, 7, 0, 0, 0, 1),
	(24, 2, 0, 0, 0, 0, 8, 0, 0, 0, 1),
	(25, 2, 0, 0, 0, 0, 9, 0, 0, 0, 1),
	(26, 2, 0, 0, 0, 0, 10, 0, 0, 0, 1),
	(27, 2, 0, 0, 0, 0, 11, 0, 0, 0, 1),
	(28, 2, 0, 0, 0, 0, 12, 0, 0, 0, 1),
	(29, 2, 0, 0, 0, 0, 13, 0, 0, 0, 1),
	(30, 2, 0, 0, 0, 0, 14, 0, 0, 0, 1),
	(31, 2, 0, 0, 0, 0, 15, 0, 0, 0, 1),
	(32, 2, 0, 0, 0, 0, 16, 0, 0, 0, 1),
	(33, 3, 0, 0, 0, 0, 1, 0, 0, 0, 1),
	(34, 3, 0, 0, 0, 0, 2, 0, 0, 0, 1),
	(35, 3, 0, 0, 0, 0, 3, 0, 0, 0, 1),
	(36, 3, 0, 0, 0, 0, 4, 0, 0, 0, 1),
	(37, 3, 0, 0, 0, 0, 5, 0, 0, 0, 1),
	(38, 3, 0, 0, 0, 0, 6, 0, 0, 0, 1),
	(39, 3, 0, 0, 0, 0, 7, 0, 0, 0, 1),
	(40, 3, 0, 0, 0, 0, 8, 0, 0, 0, 1),
	(41, 3, 0, 0, 0, 0, 9, 0, 0, 0, 1),
	(42, 3, 0, 0, 0, 0, 10, 0, 0, 0, 1),
	(43, 3, 0, 0, 0, 0, 11, 0, 0, 0, 1),
	(44, 3, 0, 0, 0, 0, 12, 0, 0, 0, 1),
	(45, 3, 0, 0, 0, 0, 13, 0, 0, 0, 1),
	(46, 3, 0, 0, 0, 0, 14, 0, 0, 0, 1),
	(47, 3, 0, 0, 0, 0, 15, 0, 0, 0, 1),
	(48, 3, 0, 0, 0, 0, 16, 0, 0, 0, 1),
	(49, 4, 0, 0, 0, 0, 1, 0, 0, 0, 1),
	(50, 4, 0, 0, 0, 0, 2, 0, 0, 0, 1),
	(51, 4, 0, 0, 0, 0, 3, 0, 0, 0, 1),
	(52, 4, 0, 0, 0, 0, 4, 0, 0, 0, 1),
	(53, 4, 0, 0, 0, 0, 5, 0, 0, 0, 1),
	(54, 4, 0, 0, 0, 0, 6, 0, 0, 0, 1),
	(55, 4, 0, 0, 0, 0, 7, 0, 0, 0, 1),
	(56, 4, 0, 0, 0, 0, 8, 0, 0, 0, 1),
	(57, 4, 0, 0, 0, 0, 9, 0, 0, 0, 1),
	(58, 4, 0, 0, 0, 0, 10, 0, 0, 0, 1),
	(59, 4, 0, 0, 0, 0, 11, 0, 0, 0, 1),
	(60, 4, 0, 0, 0, 0, 12, 0, 0, 0, 1),
	(61, 4, 0, 0, 0, 0, 13, 0, 0, 0, 1),
	(62, 4, 0, 0, 0, 0, 14, 0, 0, 0, 1),
	(63, 4, 0, 0, 0, 0, 15, 0, 0, 0, 1),
	(64, 4, 0, 0, 0, 0, 16, 0, 0, 0, 1),
	(65, 5, 0, 0, 0, 0, 1, 0, 0, 0, 1),
	(66, 5, 0, 0, 0, 0, 2, 0, 0, 0, 1),
	(67, 5, 0, 0, 0, 0, 3, 0, 0, 0, 1),
	(68, 5, 0, 0, 0, 0, 4, 0, 0, 0, 1),
	(69, 5, 0, 0, 0, 0, 5, 0, 0, 0, 1),
	(70, 5, 0, 0, 0, 0, 6, 0, 0, 0, 1),
	(71, 5, 0, 0, 0, 0, 7, 0, 0, 0, 1),
	(72, 5, 0, 0, 0, 0, 8, 0, 0, 0, 1),
	(73, 5, 0, 0, 0, 0, 9, 0, 0, 0, 1),
	(74, 5, 0, 0, 0, 0, 10, 0, 0, 0, 1),
	(75, 5, 0, 0, 0, 0, 11, 0, 0, 0, 1),
	(76, 5, 0, 0, 0, 0, 12, 0, 0, 0, 1),
	(77, 5, 0, 0, 0, 0, 13, 0, 0, 0, 1),
	(78, 5, 0, 0, 0, 0, 14, 0, 0, 0, 1),
	(79, 5, 0, 0, 0, 0, 15, 0, 0, 0, 1),
	(80, 5, 0, 0, 0, 0, 16, 0, 0, 0, 1),
	(81, 6, 0, 0, 0, 0, 1, 0, 0, 0, 1),
	(82, 6, 0, 0, 0, 0, 2, 0, 0, 0, 1),
	(83, 6, 0, 0, 0, 0, 3, 0, 0, 0, 1),
	(84, 6, 0, 0, 0, 0, 4, 0, 0, 0, 1),
	(85, 6, 0, 0, 0, 0, 5, 0, 0, 0, 1),
	(86, 6, 0, 0, 0, 0, 6, 0, 0, 0, 1),
	(87, 6, 0, 0, 0, 0, 7, 0, 0, 0, 1),
	(88, 6, 0, 0, 0, 0, 8, 0, 0, 0, 1),
	(89, 6, 0, 0, 0, 0, 9, 0, 0, 0, 1),
	(90, 6, 0, 0, 0, 0, 10, 0, 0, 0, 1),
	(91, 6, 0, 0, 0, 0, 11, 0, 0, 0, 1),
	(92, 6, 0, 0, 0, 0, 12, 0, 0, 0, 1),
	(93, 6, 0, 0, 0, 0, 13, 0, 0, 0, 1),
	(94, 6, 0, 0, 0, 0, 14, 0, 0, 0, 1),
	(95, 6, 0, 0, 0, 0, 15, 0, 0, 0, 1),
	(96, 6, 0, 0, 0, 0, 16, 0, 0, 0, 1);
	";
	$conn->query($sql);

	$sql = "
	CREATE TABLE `pointmanagement` (
	  `pointid` int(11) NOT NULL,
	  `game` int(11) NOT NULL,
	  `+1` int(11) NOT NULL,
	  `+3` int(11) NOT NULL,
	  `+5` int(11) NOT NULL,
	  `-1` int(11) NOT NULL,
	  `-3` int(11) NOT NULL,
	  `-5` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	";
	$conn->query($sql);

	$sql = "INSERT INTO `pointmanagement` (`pointid`, `game`, `+1`, `+3`, `+5`, `-1`, `-3`, `-5`) VALUES
	(1, 1, 0, 0, 0, 0, 0, 0),
	(2, 2, 0, 0, 0, 0, 0, 0),
	(3, 3, 0, 0, 0, 0, 0, 0),
	(4, 4, 0, 0, 0, 0, 0, 0),
	(5, 5, 0, 0, 0, 0, 0, 0),
	(6, 6, 0, 0, 0, 0, 0, 0),
	(7, 7, 0, 0, 0, 0, 0, 0),
	(8, 8, 0, 0, 0, 0, 0, 0),
	(9, 9, 0, 0, 0, 0, 0, 0),
	(10, 10, 0, 0, 0, 0, 0, 0),
	(11, 11, 0, 0, 0, 0, 0, 0),
	(12, 12, 0, 0, 0, 0, 0, 0),
	(13, 13, 0, 0, 0, 0, 0, 0),
	(14, 14, 0, 0, 0, 0, 0, 0),
	(15, 15, 0, 0, 0, 0, 0, 0),
	(16, 16, 0, 0, 0, 0, 0, 0),
	(17, 17, 0, 0, 0, 0, 0, 0),
	(18, 18, 0, 0, 0, 0, 0, 0),
	(19, 19, 0, 0, 0, 0, 0, 0),
	(20, 20, 0, 0, 0, 0, 0, 0),
	(21, 21, 0, 0, 0, 0, 0, 0),
	(22, 22, 0, 0, 0, 0, 0, 0),
	(23, 23, 0, 0, 0, 0, 0, 0),
	(24, 24, 0, 0, 0, 0, 0, 0),
	(25, 25, 0, 0, 0, 0, 0, 0),
	(26, 26, 0, 0, 0, 0, 0, 0),
	(27, 27, 0, 0, 0, 0, 0, 0),
	(28, 28, 0, 0, 0, 0, 0, 0),
	(29, 29, 0, 0, 0, 0, 0, 0),
	(30, 30, 0, 0, 0, 0, 0, 0),
	(31, 31, 0, 0, 0, 0, 0, 0),
	(32, 32, 0, 0, 0, 0, 0, 0),
	(33, 33, 0, 0, 0, 0, 0, 0),
	(34, 34, 0, 0, 0, 0, 0, 0),
	(35, 35, 0, 0, 0, 0, 0, 0),
	(36, 36, 0, 0, 0, 0, 0, 0),
	(37, 37, 0, 0, 0, 0, 0, 0),
	(38, 38, 0, 0, 0, 0, 0, 0),
	(39, 39, 0, 0, 0, 0, 0, 0),
	(40, 40, 0, 0, 0, 0, 0, 0),
	(41, 41, 0, 0, 0, 0, 0, 0),
	(42, 42, 0, 0, 0, 0, 0, 0),
	(43, 43, 0, 0, 0, 0, 0, 0),
	(44, 44, 0, 0, 0, 0, 0, 0),
	(45, 45, 0, 0, 0, 0, 0, 0),
	(46, 46, 0, 0, 0, 0, 0, 0),
	(47, 47, 0, 0, 0, 0, 0, 0),
	(48, 48, 0, 0, 0, 0, 0, 0),
	(49, 49, 0, 0, 0, 0, 0, 0),
	(50, 50, 0, 0, 0, 0, 0, 0),
	(51, 51, 0, 0, 0, 0, 0, 0),
	(52, 52, 0, 0, 0, 0, 0, 0),
	(53, 53, 0, 0, 0, 0, 0, 0),
	(54, 54, 0, 0, 0, 0, 0, 0),
	(55, 55, 0, 0, 0, 0, 0, 0),
	(56, 56, 0, 0, 0, 0, 0, 0),
	(57, 57, 0, 0, 0, 0, 0, 0),
	(58, 58, 0, 0, 0, 0, 0, 0),
	(59, 59, 0, 0, 0, 0, 0, 0),
	(60, 60, 0, 0, 0, 0, 0, 0),
	(61, 61, 0, 0, 0, 0, 0, 0),
	(62, 62, 0, 0, 0, 0, 0, 0),
	(63, 63, 0, 0, 0, 0, 0, 0),
	(64, 64, 0, 0, 0, 0, 0, 0),
	(65, 65, 0, 0, 0, 0, 0, 0),
	(66, 66, 0, 0, 0, 0, 0, 0),
	(67, 67, 0, 0, 0, 0, 0, 0),
	(68, 68, 0, 0, 0, 0, 0, 0),
	(69, 69, 0, 0, 0, 0, 0, 0),
	(70, 70, 0, 0, 0, 0, 0, 0),
	(71, 71, 0, 0, 0, 0, 0, 0),
	(72, 72, 0, 0, 0, 0, 0, 0),
	(73, 73, 0, 0, 0, 0, 0, 0),
	(74, 74, 0, 0, 0, 0, 0, 0),
	(75, 75, 0, 0, 0, 0, 0, 0),
	(76, 76, 0, 0, 0, 0, 0, 0),
	(77, 77, 0, 0, 0, 0, 0, 0),
	(78, 78, 0, 0, 0, 0, 0, 0),
	(79, 79, 0, 0, 0, 0, 0, 0),
	(80, 80, 0, 0, 0, 0, 0, 0),
	(81, 81, 0, 0, 0, 0, 0, 0),
	(82, 82, 0, 0, 0, 0, 0, 0),
	(83, 83, 0, 0, 0, 0, 0, 0),
	(84, 84, 0, 0, 0, 0, 0, 0),
	(85, 85, 0, 0, 0, 0, 0, 0),
	(86, 86, 0, 0, 0, 0, 0, 0),
	(87, 87, 0, 0, 0, 0, 0, 0),
	(88, 88, 0, 0, 0, 0, 0, 0),
	(89, 89, 0, 0, 0, 0, 0, 0),
	(90, 90, 0, 0, 0, 0, 0, 0),
	(91, 91, 0, 0, 0, 0, 0, 0),
	(92, 92, 0, 0, 0, 0, 0, 0),
	(93, 93, 0, 0, 0, 0, 0, 0),
	(94, 94, 0, 0, 0, 0, 0, 0),
	(95, 95, 0, 0, 0, 0, 0, 0),
	(96, 96, 0, 0, 0, 0, 0, 0);";
	$conn->query($sql);

	$sql = "CREATE TABLE `teams` (
	  `teamid` int(11) NOT NULL,
	  `name` varchar(40) NOT NULL,
	  `roboter` varchar(20) NOT NULL,
	  `points` int(11) NOT NULL,
	  `teamleiter` varchar(20) NOT NULL,
	  `games` int(11) NOT NULL,
	  `active` tinyint(1) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
	$conn->query($sql);

	$sql = "INSERT INTO teams (`teamid`, `name`, `roboter`, `points`, `teamleiter`, `games`, `active`) VALUES \n";

	for($i = 0; $i < $settings['Options']['AnzTeams']; $i++)
	{
		$sql .= "(".($i+1).", 'team".($i+1)."', 'roboter".($i+1)."', 0, 'Mr Nobody', 0, 1)";
		
		if($i < ($settings['Options']['AnzTeams'] - 1)) $sql .= ", \n";
		else $sql .= ";";

	}
	
	$conn->query($sql);

	$sql = "ALTER TABLE `changed`
	  ADD PRIMARY KEY (`changedid`);";
	$conn->query($sql);

	$sql = "ALTER TABLE `games`
	  ADD PRIMARY KEY (`gameid`);";
	$conn->query($sql);

	$sql = "ALTER TABLE `pointmanagement`
	  ADD PRIMARY KEY (`pointid`);";
	$conn->query($sql);

	$sql = "ALTER TABLE `teams`
	  ADD PRIMARY KEY (`name`);";
	$conn->query($sql);

	$sql = "ALTER TABLE `changed`
	  MODIFY `changedid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;";
	$conn->query($sql);

	$sql = "ALTER TABLE `games`
	  MODIFY `gameid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;";
	$conn->query($sql);

	$sql = "ALTER TABLE `pointmanagement`
	  MODIFY `pointid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;";
	$conn->query($sql);
}
CloseCon($conn);
?>
