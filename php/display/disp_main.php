<?php
include_once '../lib/db_connection.php';
include_once '../lib/db_manip.php';
?>

<body style="overflow:hidden">
<table style='width:100%; height:100%' class='main'>
	<tr>
		<td style='width:20%; border-right:1px solid black' valign='top'><iframe style='width:100%; height:100%; border: 0' src="../display/disp_timelist.php" ></iframe></td>
		<td style='width:80%' valign='top'><iframe style='width:100%; height:100%; border: 0' src="../display/disp_points.php"></iframe></td>
	</tr>
</table>
</body>
 <meta http-equiv="refresh" content="5; URL=<?php echo basename(__FILE__) ?>">
