<?php


// Connecting, selecting database
$link = mysql_connect("mysql.cefns.nau.edu", "utdesktop", '$uNrayguns')
	or die('This is not the server you are looking for: ' . mysql_error());

mysql_select_db('utdesktop') or die('Could not select database');
mysql_query("DELETE FROM Device WHERE 1");
// Closing connection
mysql_close($link);


?>

