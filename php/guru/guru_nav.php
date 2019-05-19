<?php
    # include main function for settings and database connection
    require_once '../lib/db_main.php';
?>

<table>
    <tr>
        <td  style="text-align:center" colspan="3">guru</td>
        <td></td>
        <td style="text-align:center">referee</td>
        <td></td>
        <td style="text-align:center">display</td>
    </tr>
    <tr>
        <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='teams' >edit Teams</button></form></td>
        <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='start' >edit Start</button></form></td>
        <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='games' >edit Games</button></form></td>
        <!-- <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='blocks'>edit Blocks</button></form></td> -->

        <td></td>
        <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='ref' >view Referee</button></form></td>
        <td></td>
        <td><form action='guru_main.php' method='post'><button type='submit' name='changeview' value='disp' >view Display</button></form></td>
    </tr>
</table>
