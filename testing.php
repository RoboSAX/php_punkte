<?php

$test = array(array(1,2,3),array(234,34,2));



array_multisort($test[1], SORT_DESC, $test[0], $test);
var_dump($test);


?>