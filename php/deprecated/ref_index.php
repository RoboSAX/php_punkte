<?php

include_once 'db_connection.php';
include_once 'db_manip.php';

$conn = OpenCon();

echo "Connected Successfully";
CloseCon($conn);
?>
<br>
<form action="adress.php" method="post">
<input type="text" name="name"><br>
<input type="text" name="password"><br>
<input type="submit">
</form>
<?php
exit();
?>

