<?php

include("session.php");

$StatusColor = array(" "=> "IT'S A TRAP", "U" => "active", "IU" => "vacant", "UI" => "vacant", "DI" => "frozen", "D" => "idle");
function parseStatus($status, $lookup){

	$status = explode("\n", $status);
	foreach ($status as $stat){
		$vals = preg_split('/[\s]+/', trim($stat));
		if ($vals[0] ==	"Token") continue;
		if (substr($vals[0], 0, 1) == '-') continue;
		if ($vals[0] == "tatooine") continue;
		if ($vals[0] == "corellia") continue;
		if ($vals[0] == "rishi") continue;
		if ($vals[0] == "iego") continue;
		if ($vals[0] == "dantooine") continue;
		$id = explode(".", $vals[0]);
		$mac = $id[1];
		$lookup[$mac]["status"] = end ($vals);
		$lookup[$mac]["mac"] = $mac;
	}
	return $lookup;
}


function parseInfo($info, $lookup){

	$info = explode("\n", $info);
	foreach ($info as $infos){
		$tmp = explode(" ", $infos);
		if ($tmp[0] == "tatooine" ||
			$tmp[0] == "corellia" ||
			$tmp[0] == "rishi" ||
			$tmp[0] == "iego" ||
			$tmp[0] == "dantooine"){
				$lookup[$mac]["server"] = $tmp[0];
				$infos = str_replace($infos[0]." ", " ", $infos);
			}
				
		$vals = explode(",", trim($infos));
		$mac = $vals[0];
		if (count($vals)>=1){
			$temp = explode(" ", $vals[1]);
			$tmp2 = split("/", end($temp));
			$lookup[$mac]["bldg"] = $tmp2[0];
			$lookup[$mac]["room"] = $tmp2[1];
		}
		if (count($vals)>=2) $lookup[$mac]["desc"] = $vals[2];
	}
	return $lookup;
}

function statFilter($lookup, $que){
	$queLookup = array();
	foreach ($lookup as $lookupRow){
		if (strcasecmp($lookupRow["status"], $que) == 0) {
			array_push($queLookup, $lookupRow);
		}
	}
	return $queLookup;
}

function filter($lookup, $que){
	$queLookup = array();
	foreach ($lookup as $lookupRow){
		if (stripos($lookupRow["mac"], $que) !== false || 
			stripos($lookupRow["bldg"], $que) !== false ||
			stripos($lookupRow["room"], $que) !== false ||
			stripos($lookupRow["desc"], $que) !== false ) {
			array_push($queLookup, $lookupRow);
		}
	}
	return $queLookup;
}

function WriteTable ($lookup, $query = "*"){
?>
	<div id="accordionList" class="accordion" width="100%" >
		<?php foreach($lookup as $row) { ?>
		
			<table width="100%" class="accordion-header <?php echo $row["status"]; ?>">
				<td width="12%" onclick="info('<?php echo $row["mac"]; ?>')"> <?php echo $row["mac"]; ?> </td>
				<td width="9%" onclick="info('<?php echo $row["mac"]; ?>')"> <?php echo $row["bldg"]; ?>  </td>
				<td width="9%" onclick="info('<?php echo $row["mac"]; ?>')"> <?php echo $row["room"]; ?>  </td>
				<td width="30%" onclick="info('<?php echo $row["mac"]; ?>')"> <?php echo $row["desc"]; ?> </td>
				<td width="20%" onclick="info('<?php echo $row["mac"]; ?>')"> <?php echo $row["server"]; ?> </td>
				<td width="5%"><i class="icon-remove icon-white tt" rel="tooltip" title="Kill" onclick="killdtu('<?php echo $row["mac"]."',"."'$query"; ?>')"></i></td>
				<td width="15%"><i class="icon-info-sign icon-white tt" rel="tooltip" title="Info" onclick="info('<?php echo $row['mac']; ?>')"</i></td>
			</table>
			
			<div class="content" id="info_<?php echo $row["mac"] ?>">
			</div>	
		
		<?php } ?>
	</div>
<?php
}

function makeTable($que, $statFilter = false){
	// Connecting, selecting database
	/*$link = mysql_connect("mysql.cefns.nau.edu", "utdesktop", '$uNrayguns')
		or die('This is not the server you are looking for: ' . mysql_error());
	//echo 'Connected successfully';
	mysql_select_db('utdesktop') or die('Could not select database');

	// Performing SQL query
	$query = 'SELECT MAC,BLDG,ROOM,DESCRIPTION FROM Device';
	if ($que != "*") $query .=" WHERE MAC LIKE '%$que%' OR BLDG LIKE '%$que%' OR ROOM LIKE '%$que%' OR DESCRIPTION LIKE '%$que%'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error()); */


	if (!isset($_SESSION["lookup"])){

		// Get status and info of all DTU's
		$output = shell_exec ("../scripts/srall.sh");
		$lookup = parseStatus($output, $lookup);
		$output = shell_exec ("../scripts/srx.sh");
		$lookup = parseInfo($output, $lookup);

		// Caching lookup table
		$_SESSION["lookup"] = $lookup;	
	}
	else {
		$lookup = $_SESSION["lookup"];
	}
	if (strcasecmp ("*", $que) == 0) WriteTable($lookup);
	elseif(!$statFilter) WriteTable (filter($lookup, $que),$que);
	else WriteTable (statFilter($lookup, $que));


	// Sorts other properties of DTU's
	/*$values = array();
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$mac = current($line);
		$tokens = explode (" ", $out);
		$status = $lookup[$mac];
		$col = 0;
		foreach ($line as $col_value) {
			if ($col==0) $values[$count]["mac"] = $col_value;
			else if ($col==1) $values[$count]["bldg"] = $col_value;
			else if ($col==2) $values[$count]["room"] = $col_value;
			else if ($col==3) $values[$count]["desc"] = $col_value;
			$col++;
		}
		$values[$count]["status"] = $status;
		$count++;
	}*/



	/*
	// start parsing
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$count++;
		$mac = current($line);
		$tokens = explode (" ", $out);
		$status = $lookup[$mac];

		echo "<div class=\"accordion-heading $StatusColor[$status]\" style=\"border-style:solid; border-bottom-style:none; border-width:1px; border-color:#454545; border-radius:5px\">";
		echo "<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#info$count\" href=\"#info_show$count\">";
		echo "<table width=\"100%\" > <tr>";

		$width=array("12%", "9%", "9%", "40%");
		$index=0;
		foreach ($line as $col_value) {
			if ($mac==0) $mac=$col_value;
			$tmp=$width[$index++];
			echo "\t\t<td width=$tmp>$col_value</td>\n";
		}
		echo "<td width=\"5%\"><i class=\"icon-remove icon-white tt\" rel=\"tooltip\" title=\"Kill\" onclick=\"kill('$mac')\"></i></td>";
		echo "<td width=\"25%\"><i class=\"icon-info-sign icon-white tt\" rel=\"tooltip\" title=\"Info\" onclick=\"info('$mac')\"</i></td>";
		echo "\t</tr>\n";
		echo "</table>";
		echo "</a>";
		echo "</div>";
		echo "<div id=\"info_show$count\" class=\"accordion-body collapse\">";
		echo "<div class=\"accordion-enter\"><div id=\"info_".$mac."\"></div> ";
		echo "</div>";
		echo "</div>";

	}
	*/

	// Free resultset
	//mysql_free_result($result);

	// Closing connection
	//mysql_close($link);
}

function rebuildCache($query){
	unset($_SESSION["lookup"]);
	makeTable($query);

}

function killdtu($mac, $query){
	shell_exec("../scripts/srkill ".$mac);
	echo rebuildCache($query);
}

function info($mac){
	$output = shell_exec ("../scripts/srinfo ".$mac);
	echo $output;
}


if ($_GET["cmd"] == "ping") print_r(parseStatus(shell_exec ("../scripts/srall.sh")));

else if ($_GET["cmd"] == "kill") killdtu($_GET["mac"], $_GET["que"]);
else if ($_GET["cmd"] == "info") info($_GET["mac"]);
else if ($_GET["cmd"] == "list") makeTable($_GET["que"]);
else if ($_GET["cmd"] == "statlist") makeTable($_GET["que"], true);
else if ($_GET["cmd"] == "refresh") rebuildCache($_GET["que"]);
else if ($_GET["cmd"] == "testing") {
	$out = shell_exec ( "../scripts/srinfo ".$_GET["mac"]);
	echo $out;
}
?>
