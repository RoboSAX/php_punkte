<?php
include_once '../lib/db_connection.php';
include_once '../lib/db_manip.php';
?>
<head>
<style>
table.list {
	border: 1px solid black; 
	text-align: center; 
	border-spacing: 0px;
	margin-top: 10px;
	}
table.list td {
	border: 1px solid black;
	color: black;	
	}
	
	
table.games {
	border: 1px solid black;
	text-align: left; 
	border-spacing: 0px;
	margin-top: 10px;
	margin-left: 20%;
	margin-right: 20%;
	width: 60%;
	}
	
table.games td.normal {
	border : 1px solid black;
	color: black;
	background-color: transparent;
	}
	
table.games td.changed {
	border: 1px solid black; 
	color: red;
	background-color: transparent;
	}	
	
table.games td.h {
	border: 1px solid black;
	background-color: yellow;
	}
	
table.games td.hc {
	border: 1px solid black; 
	color: red;
	background-color: yellow;
	}

table.games tr {
	
	}

table.display {
	border: 0px solid black; 
	text-align: center; 
	margin-top: 10px;
	}
table.display td {
	text-align: center;
	}
table.display td.changed {
	color: red;
	background-color: transparent;
	}
	
table.display tr {
	
}

table.main {
	text-align: left;
}

h2 {
	text-align: center;
	}
</style>
</head>
<body>
<table style='width:100%; height:100%' class='main'>
	<tr>
		<td style='width:20%; border-right:1px solid black' valign='top'><?php include "../display/disp_timelist.php"; ?></td>
		<td style='width:80%' valign='top'><?php include "../display/disp_points.php" ?></td>
	</tr>
</table>
</body>