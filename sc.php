<?php 

require_once 'init.php';

function evList(){
	$ev_list = "<div class=\"row\">\n".
					"<div class=\"col-md-2 pull-left text-left vcenter\">\n".
						"<a href=\"./index.php\">\n".
							"<br><span class=\"glyphicon glyphicon-circle-arrow-left\" style=\"font-size:36px;\"></span>\n".
						"</a>\n".
					"</div>";
	$ev_q = "SELECT e.id, e.name
			 FROM events e 
			 WHERE DATE(NOW()) BETWEEN e.sdate AND e.edate AND e.del ='0'; ";
	$ev_SQL = @mysql_query($ev_q) or die(mysql_error());
	if(mysql_num_rows($ev_SQL)>=1){
		$ev_list .= "<div class=\"col-md-6 text-center vcenter\">\n<h1>List Available Events by Today</h1>\n</div>\n
					 <div class=\"col-md-2 pull-right vcenter\">&nbsp;</div>\n</div>";
		while($ev_arr = mysql_fetch_array($ev_SQL,MYSQL_ASSOC)){
			$ev_list .= "<a href=\"./sc.php?id=".$ev_arr["id"]."\">\n".
							"<div class=\"bs-callout bs-callout-info\">\n".
					    		"<h3>".ucwords($ev_arr["name"])."</h3>\n".
						"</div></a>";
		}
	}
	else {
		$ev_list .= "<h1>There are currently no events by today</h1>";
	}
	return $ev_list;
}


function detEvents($id){
	$ev_list ="";
	$dev_q = "SELECT l.name AS lname, t.tnbr
			  FROM locket l
			  	LEFT JOIN ticket t ON (t.id = l.tid)
			  WHERE l.evid = '$id'
			  ORDER BY l.name ASC;";
	$dev_SQL = @mysql_query($dev_q) or die(mysql_error());

	if(mysql_num_rows($dev_SQL)>=1){ 
		$ev_list .= "<div class=\"table-responsive\" style=\"margin-top:60px;margin-bottom:60px\">".
						"<table class=\"table table-striped table-hover\">\n".
						"<thead>\n".
							"<tr>\n".
								"<th class=\"text-left\"><h1>COUNTER</h1></th>\n".
								"<th class=\"text-right\"><h1>TICKET NBR.</h1></th>\n".
							"</tr>\n".
						"</thead>\n".
						"<tbody>\n";
		while($array = mysql_fetch_array($dev_SQL,MYSQL_ASSOC)) {
			$tnbr = ($array["tnbr"])?($array["tnbr"]):"-";
			$ev_list .= "<tr>\n".
					   		"<td class=\"text-left\"><h1>".ucwords($array["lname"])."</h1></td>\n".
					   		"<td class=\"text-right\"><h1>$tnbr</h1></td>\n".
					   	"</tr>\n";
		}
		$ev_list .=	"</tbody>\n".
					"</table>\n".
					"</div>";
	}
	else {
		$ev_list .= "<h1>There are currently no lockets for today's event</h1>";
	}
	return $ev_list;
}
function set_header($id) {
	$ev_q = "SELECT e.id, e.name
			 FROM events e
			 WHERE e.id = '$id';";
	$ev_SQL = @mysql_query($ev_q) or die(mysql_error());
	$ev_arr = mysql_fetch_array($ev_SQL,MYSQL_ASSOC);
	$ev_list_det = "<div class=\"row head_sc\">\n".
					"<div class=\"col-md-3 pull-left text-left vcenter\">\n".
						"<a href=\"./sc.php\">\n".
							"<br><span class=\"glyphicon glyphicon-circle-arrow-left\" style=\"font-size:36px;\"></span>\n".
						"</a>\n".
					"</div>\n".
					"<div class=\"col-md-6 text-center vcenter\">\n".
						"<h1>".strtoupper($ev_arr["name"])."</h1>\n".
					"</div>\n".
					"<div class=\"col-md-3 pull-right text-right vcenter\">\n".
						"<h1><div id=\"dg-clock\"></div></h1>\n".
					"</div>\n".
				 "</div>\n";
	return $ev_list_det;
}
function set_marquee($id) {
	$ev_q = "SELECT e.id, e.ticker
			 FROM events e
			 WHERE e.id = '$id';";
	$ev_SQL = @mysql_query($ev_q) or die(mysql_error());
	$ev_arr = mysql_fetch_array($ev_SQL,MYSQL_ASSOC);
	
	$ev_list =	"<div class=\"row foot_sc\">
					<marquee class=\"marquee_sc\"><h2>".$ev_arr["ticker"]."</h2></marquee>
				 </div>\n";
	
	return $ev_list;
	

}

function call_people($id) {
	$query 	= "SELECT q.tid AS tid, q.lid AS lid 
	           FROM queue_ticket q 
	           WHERE q.evid = '$id' 
	           ORDER BY q.tid ASC LIMIT 0,1;";
	$SQL 	= @mysql_query($query) or die(mysql_error());
	$arr 	= mysql_fetch_array($SQL,MYSQL_ASSOC);
	
	if(mysql_num_rows($SQL)>=1) {
		echo "ID ticket ".$arr["tid"]." di counter ".$arr["lid"];
		//$d_query = "DELETE FROM queue_ticket WHERE tid ='".$arr["tid"]."';";
		//@mysql_query($d_query) or die(mysql_error());
		//sleep(3);
		call_people($id);
		//fastcgi_finish_request();
		//exit();
	}	
	else {
		//sleep(3);
		call_people($id);
		//fastcgi_finish_request();
		//exit();
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Events - Queues Information - <?=PRODUCT?></title>
</head>
<body onload="startTime()">
<//-----------------CONTENT-START-------------------------------------------------//>
<div class="container">
<?php 
if(isset($_GET["id"]) && is_numeric($_GET["id"])) {
	echo set_header($_GET["id"]);
	echo "<div class=\"row\"><div id=\"load_sc\"></div></div>";
	echo "<script>
			function autoRefresh_div() {
      			$(\"#load_sc\").load(\"sc.php?load_ev=".$_GET["id"]."\");
			}
			setInterval('autoRefresh_div()', 500);
		  </script>";
	echo set_marquee($_GET["id"]);
	//call_people($_GET["id"]);
}
elseif(isset($_GET["load_ev"]) AND is_numeric($_GET["load_ev"])){
	echo detEvents($_GET["load_ev"]);
}
else {
	echo evList();
}

?>
</div>

<//-----------------CONTENT-END-------------------------------------------------//>
<script src="<?=JS_PATH?>jquery-2.1.1.min.js"></script>
<script src="<?=JS_PATH?>bootstrap.min.js"></script>
<script>

// Mouseover/ Click sound effect- by JavaScript Kit (www.javascriptkit.com)
// Visit JavaScript Kit at http://www.javascriptkit.com/ for full source code

//** Usage: Instantiate script by calling: var uniquevar=createsoundbite("soundfile1", "fallbackfile2", "fallebacksound3", etc)
//** Call: uniquevar.playclip() to play sound

var html5_audiotypes={ //define list of audio file extensions and their associated audio types. Add to it if your specified audio file isn't on this list:
	"mp3": "audio/mpeg",
	"mp4": "audio/mp4",
	"ogg": "audio/ogg",
	"wav": "audio/wav"
}

function createsoundbite(sound){
	var html5audio=document.createElement('audio')
	if (html5audio.canPlayType){ //check support for HTML5 audio
		for (var i=0; i<arguments.length; i++){
			var sourceel=document.createElement('source')
			sourceel.setAttribute('src', arguments[i])
			if (arguments[i].match(/\.(\w+)$/i))
				sourceel.setAttribute('type', html5_audiotypes[RegExp.$1])
			html5audio.appendChild(sourceel)
		}
		html5audio.load()
		html5audio.playclip=function(){
			html5audio.pause()
			html5audio.currentTime=0
			html5audio.play()
		}
		return html5audio
	}
	else{
		return {playclip:function(){throw new Error("Your browser doesn't support HTML5 audio unfortunately")}}
	}
}

// onclick="clicksound.playclip()"

//Initialize two sound clips with 1 fallback file each:

var mouseoversound=createsoundbite("whistle.ogg", "whistle.mp3")
var clicksound=createsoundbite("click.ogg", "click.mp3")

</script>
<script>
function startTime() {
	var today=new Date();
	var d=today.getDate();
	var mo=today.getMonth();
	var y=today.getFullYear();
	var h=today.getHours();
	var m=today.getMinutes();
	var s=today.getSeconds();
	var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun",
	                   "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
	// add a zero in front of numbers<10
	h=checkTime(h);
	m=checkTime(m);
	s=checkTime(s);
	document.getElementById('dg-clock').innerHTML=h+":"+m+":"+s;
	t=setTimeout(function(){startTime()},500);
}

function checkTime(i){
	if (i<10) {
  		i="0" + i;
  	}
	return i;
}
</script>
<link rel="stylesheet" href="<?=CSS_PATH?>bootstrap.min.css">
<link rel="stylesheet" href="<?=CSS_PATH?>docs.min.css">
<link rel="stylesheet" href="<?=CSS_PATH?>front.css">
<style media="all">
  .head_sc,.foot_sc {
  	position:fixed;
  	height:60px;
  	left:0;
  	right:0;
  	margin-left:50px;
  	margin-right:50px;
  	background-color:#FFF;
  }
  
  .head_sc {
  	top:0;	
  }
  
  .foot_sc {
  	bottom:0;
  }
  
  .marquee_sc {
  	text-align: justify;
  	text-justify: distribute-all-lines;
  	line-height: 0;
  	white-space: nowrap;
  }
  
  .vcenter {
    display: inline-block;
    vertical-align: middle;
    float: none;
	}
  
</style>
<style media="print">
  .noPrint{ display: none; }
  .yesPrint{ display: block !important; }
</style>
</body>
</html>