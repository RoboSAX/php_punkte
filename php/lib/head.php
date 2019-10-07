<!doctype html>
<head>
<?php
	if(isset($head)) echo $head;

	if(!isset($styles)) $styles = array();
	array_push($styles, "main.css");

	foreach($styles as $name)
		echo "\t<link rel='stylesheet' href='".$settings['Server']['base_url']."../css/$name'/>\n";
?>
</head>
