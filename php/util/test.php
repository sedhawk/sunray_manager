<?php

exec("fill_db.php desktop.txt", $output);
foreach ($output as $line)
	echo $line."\n";

?>
