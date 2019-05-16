<?php
    include_once '../lib/db_connection.php';
    include_once '../lib/db_manip.php';
?>

<table>
    <tr>
        <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='teams' >edit Teams</button></form></td>
        <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='start' >edit Start</button></form></td>
        <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='games' >edit Games</button></form></td>
        <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='blocks'>edit Blocks</button></form></td>
    </tr>
</table>
