<?php include("session.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="css/core.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<!-- <link rel="stylesheet" href="css/jquery.mobile.1.3.0.min.css" /> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootbox.min.js"></script>
<script src="js/jquery.mobile-1.3.0.min.js"></script>
<style type="text/css">
/*green*/
.U {color:#00CC00;}
td.U {background-color:#00CC00;}
/*red*/
.DI {color:#FF0000;}
td.DI{background-color:#FF0000;}
/*grey*/
.accordion-heading.all table{color:#787873;}
td.all{background-color:#787873;}
/*yellow*/
.D {color:#FFFF00;}
td.D {background-color: #FFFF00;}
/*white*/
.UI {color:#FFFFFF;}
td.UI{background-color:#FFFFFF;}
/*white*/
.IU {color:#FFFFFF;}
td.IU{background-color:#FFFFFF;}
/*blue*/
.accordion-heading.multi table{color:#0C17E1;}
td.multi{background-color:#0C17E1;}
</style>

<script>

function killdtu(mac, que){
	$.get("php/sunray.php?cmd=kill&mac="+mac+"&que="+que, function(data){
		//$("#Message").text(data);
		$("#sunList").html(data);
	});
}

function info(mac){
	/*$.mobile.loading( 'show',{
		text: 'loading',
		textVisible: true,
		theme: 'a',
		html: ""
	});*/
	$.get("php/sunray.php?cmd=info&mac="+mac, function(data){
		//$.mobile.loading( 'hide');
		$("#info_"+mac).html(data.slice(1, -1).trim());
		$("#accordionList").accordion("refresh");
	});
}

function filter(que){
	$.get("php/sunray.php?cmd=list&que=" + que, function(data){
		$("#sunList").html(data);
	});
}

function statFilter(que){
	$.get("php/sunray.php?cmd=statlist&que=" + que, function(data){
		$("#sunList").html(data);
	});
}

$(document).ready(function() {
	$(".tt").each(function(){
		$(this).tooltip();
	})	
	$("#query").keyup(function(event) {
		if ($(this).val() == false) filter("*");
		else filter($(this).val());
	});
	$("#recache").click(function(event){
		query = $("#query").val();
		if (query.val().length == 0) query = "*";
		$.get("php/sunray.php?cmd=refresh&que="+query, function(data){
			$("#sunList").html(data);
		});
	});
	$.get("php/sunray.php?cmd=list&que=*", function(data){
		$("#sunList").html(data);
		$("#accordionList").accordion({
			collapsible: true,
			header: "table" 
		});

	});	
});

</script>
</head>
<body>
<!-- Bootstrap Container -->
<div class="container-fluid">

<!-- Banner -->
<div class="row-fluid">
	<div class="span8 offset2 hero-unit">
	<h1 id="target">SunraySupport</h1>
	<p>
		Click on the colored blocks below to filter DTU's by status.
	</p>

		<table>
			<tr><td colspan="5" align="left">STATUS KEY</td></tr>
			<tr style="cursor:pointer;">
				<td class="U" onclick="statFilter('U')" style=" width: 16px; height:16px; border-radius:5px;"/>
				<td onclick="statFilter('U')">&nbsp;In-Session</td>
				<td style="width: 32px;"/>	
				<td class="DI" onclick="statFilter('DI')"style="width: 16px; height:16px; border-radius:5px;"/>
				<td onclick="statFilter('DI')">&nbsp;Frozen</td>
				<td style="width: 32px;"/>
				<td class="all" onclick="statFilter('*')"style="width: 16px; height:16px; border-radius:5px;"/>
				<td onclick="statFilter('*')">&nbsp;Show All</td>
			</tr>
			<tr style="height:5px;"><td/><td/></tr>
			<tr style="cursor:pointer;">
				<td class="D" onclick="statFilter('D')"style="width: 16px; height:16px; border-radius:5px;"/>
				<td onclick="statFilter('D')">&nbsp;Idle</td>	
				<td style="width:32px;"/>
				<td class="IU" onclick="statFilter('IU')"style="width: 16px; height:16px; border-radius:5px;"/>
				<td onclick="statFilter('IU')">&nbsp;Vacant</td>
				<td style="width:32px;"/>
				<td class="multi" onclick="statFilter('multi')"style="width: 16px; height:16px; border-radius:5px;"/>
				<td onclick="statFilter('multi')">&nbsp;Multi-Users</td>
			</tr>
			
		</table>

	</div>
</div>

<div class="row-fluid">

<div class="span10 offset1">

<p><table>
	<tr><td><input id="query" type="text" size="20" style="border: none;" /></td><td><i class="icon-search icon-white"></i></td></tr>
</table></p>

	<table width="100%">
		<tr>
			<td width="1%" > &nbsp; </td>
			<td width="11%" ><h4>MAC</h4></td>
			<td width="9%" ><h4>BLDG</h4></td>
			<td width="9%" ><h4>ROOM</h4></td>
			<td width="30%" ><h4>DESCRIPTION</h4>
			<td width="20%" ><h4>SERVER</h4></td>
			<td width="20%" ><table width="100%"><tr><td width="25%"><h4>ACTIONS</h4></td><td align="right" width="*"><a href="#" class="btn" id="recache" color="#A4A4A4" > Refresh List</a></td></tr></table></td>
		</tr>
	</table>

<div id="sunList">
</div>
</div>
</div>

<div id="Message"> &nbsp;
</div>

</body>
</html>
