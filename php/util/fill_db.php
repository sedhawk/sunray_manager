<?php

class Device
{
	public $mac;
	public $bldg;
	public $room;
	public $desc;
	
	public function __construct($mac, $bldg, $room, $desc){
		$this->mac =mysql_real_escape_string($mac);
		$this->bldg=mysql_real_escape_string($bldg);
		$this->room=mysql_real_escape_string($room);
		$this->desc=mysql_real_escape_string($desc);
	}

	public function insert_db($db){
		$query="INSERT INTO Device VALUES (null, '$this->mac', '$this->bldg', '$this->room', '$this->desc')";
		echo "$query \n";
		mysql_query($query) or die('Query failed: ' . mysql_error());
	}

}

if (count($argv) < 2){
	die('Usage: php fill_db.php filename');
}

$devices=array();

$file = fopen($argv[1], "r") or die ("Could not open file $argv[1]");
echo "parsing file \n";
while(!feof($file)){
	$tmp = trim(fgets($file));
	if (strpos($tmp, "Printer") === false){
		$tmp2 = explode (",", $tmp);
		if (count($tmp2) == 3){
			$tmp3 = explode ("/", $tmp2[1]);
			if (count($tmp3) == 1) $tmp3[1] = " ";

			array_push ($devices, new Device($tmp2[0], $tmp3[0], $tmp3[1], $tmp2[2]));
		}
		else
			array_push ($devices, new Device($tmp2[0], " ", " ", " "));
		}
	}

fclose($file);

// Connecting, selecting database
$link = mysql_connect("mysql.cefns.nau.edu", "utdesktop", 'YOUR_PASSWORD')
	or die('This is not the server you are looking for: ' . mysql_error());
echo "Connected successfully\n";
mysql_select_db('utdesktop') or die('Could not select database');
echo "filling database\n";
foreach($devices as $dev){
	$dev->insert_db($link);
}

// Closing connection
mysql_close($link);

echo "done \n";
?>

