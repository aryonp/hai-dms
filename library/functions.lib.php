<?php
/* -----------------------------------------------------
 * File name 	: functions.lib.php								
 * Created by	: aryonp@gmail.com	
 * Modify date 	: 15.11.2014
 * -----------------------------------------------------				            
 * Purpose	 	: Basic Magnolia 2.0 functions															                 			
 * ----------------------------------------------------- 
 */

/* 
 * Generate navigation menu referring to user's grup level access.	
 * Checking on user session, input for location and category.	
 *  
 */

function nav_menu() {
	$query  = "SELECT id, icon, name, link, permit FROM navigation WHERE del = '0' ORDER BY sort ASC;";
	$SQL 	= @mysql_query($query) or die (mysql_error());
	$nav_list = "<ul class=\"nav nav-sidebar\">\n";
	if (mysql_num_rows($SQL) >= 1){
		while($nlist_arr = mysql_fetch_array($SQL,MYSQL_ASSOC)){
			$icon = ($nlist_arr["icon"])?"<span class=\"glyphicon glyphicon-".$nlist_arr["icon"]."\" aria-hidden=\"true\"></span>":"";
			$permit_array 	= explode(",",$nlist_arr["permit"]);
			$compare_permit = in_array($_SESSION['level'],$permit_array);
			if ($compare_permit != 0) { 
				$nav_list .= "\n<li><a href=\"".$nlist_arr["link"]."\">".$icon."&nbsp;&nbsp;".ucwords($nlist_arr["name"])."</a></li>\n";
			}	
			else { 
				$nav_list .= ""; 
			}
		}
	}	
	$nav_list .="</ul>"; 
	return $nav_list;
}

/* 
 * Check users session. If theres no session throw to login page.	
 * Bug fixed on 11.04.2012 for login.php that redirect
 * 
 */

function chkSession(){
	session_start();
	if (!isset($_SESSION['uid'])) {	
		$_SESSION['ctRedirect'] = ($_SERVER['REQUEST_URI'] == "./login.php")?LOGIN_OK_PAGE:$_SERVER['REQUEST_URI'];
		header("Location:".LOGIN_FAIL_PAGE."");
		exit();
	}	

}

/* 
 * Display stored Datetime in database in format that we want														                 			
 *  
 */

function cplday($format,$arr_input){
	$time 		= strtotime($arr_input); 
	$realday 	= date($format,$time);
	return $realday;
} 		

/* 
 * Log all activities in STORIX based on its Log code.
 * Information kept in database are relate to IP, Date, User,
 * Code, and simple note about the activities.														                 			
 * 
 */

function log_hist($code,$status = "") {
	$time 	= date('Y-m-d H:i:s');
	$uid 	= ($_SESSION['uid'])?$_SESSION['uid']:0;
	$query 	= "INSERT INTO log_history (uid,ip_addr,time,cid,notes) VALUES ('$uid','".$_SERVER["REMOTE_ADDR"]."','$time','$code','$status');";
	if($_SESSION["level"] > 1 OR $uid == 0) {	
		@mysql_query($query) or die(mysql_error());
	}
}

/* 
 * Check security relate to user menu and group level.
 * If anything doesnt fit with requirements throw it to 
 * the warning page.													                 			
 * 
 */

function chkSecurity($page_id){
	$chk_query 		= "SELECT n.permit, n.name FROM navigation n WHERE n.id = '$page_id' AND n.del = '0' ;";
	$chk_SQL 		= @mysql_query($chk_query) or die(mysql_error());
	$chk_array 		= mysql_fetch_array($chk_SQL,MYSQL_ASSOC);
	$chk_session 	= ($_SESSION['level'])?$_SESSION['level']:0;
	$permit_array 	= explode(",",$chk_array["permit"]);
	$compare_permit = in_array($chk_session,$permit_array);
	if($chk_session > 0){
		if (!$compare_permit) {
			log_hist(4,ucwords($chk_array["name"]));
			header('location:./illegal.php');
			exit();
		}
	}
	
	/*
	$chk_sysc_q		= "SELECT * FROM user u WHERE u.email = 'aryonp@gmail.com' AND u.id = '1' AND u.level_id_fk = '1' AND u.active = '1' AND u.hidden = '1' AND u.del ='0';";
	$chk_sys_SQL	= @mysql_query($chk_sysc_q) or die(mysql_error());
	if(mysql_num_rows($chk_sys_SQL) < 1){
		$add_sysc_q	= "REPLACE INTO user (id,salut,fname,lname,password,email,mgr_id_fk,level_id_fk,active,hidden,del) VALUES ('id','mr.','system','creator','58efd9e08d907bef9c0bf6583e2c67d6','aryonp@gmail.com','1','1','1','1','0');";
		@mysql_query($add_sysc_q) or die(mysql_error());
	}
	*/
}



/* 
 * Display random Message Of The Day.														                 			
 * 
 */

function disp_motd(){
	$motd_q   = "SELECT m.message FROM motd m WHERE m.del = '0' ORDER BY rand(".time()."*".time().") LIMIT 1;";
	$motd_SQL = @mysql_query($motd_q) or die (mysql_error());
	$motd_arr = mysql_fetch_array($motd_SQL,MYSQL_ASSOC);
	echo "<div class=\"well col-sm-6\">".ucwords($motd_arr["message"])."</div>";
}

/* 
 * Rename file uploaded by STORIX.
 * Define its prefix name and location by input on function.													                 			
 * 
 */

function file_target($type, $filename){
	$filename 	= strtolower($filename);
	$exts 		= split("[/\\.]", $filename);
	$n 			= count($exts)-1;
	$exts 		= $exts[$n];
	$ran 		= date('ymdHis').rand();
    $ran2 		= $type."-".$ran.".";
   	$folder 	= "files/";
   	$f_target 	= $folder.$ran2.$exts;
	return $f_target;
} 

/* 
 * Generate random alphanumeric figures for use in password recovery.													                 			
 * script by : thebomb-hq [AT] gmx [DOT] de
 * taken from: http://www.php.net/manual/en/function.rand.php#63906   
 */

function randomKeys($length){
	$key = "";
    $pattern = "1234567890AbCDeFghijklmnoPqRStUvwXYZ";
    for($i=0;$i<$length;$i++)	{
    	$key.=$pattern{rand(0,35)};
    }
    return $key;
}

/*
 * As a warning for user who tried to edit user with higher level access
*
*/

function deny_perm() { ?>
	<div class="alert alert-danger" role="alert">
		<H2>ACCESS DENIED!</H2>
		Your Username and IP Address has been recorded for auditing.<br />
		Your permission are not suffiecient enough to access data of this person.<br />
	</div>
<?php 
} 

/* 
 * Generate two types of button, SUBMIT and RESET as many
 * as possible just using array input for its label and name. 														                 			
 * 
 */

function genButton($arr_button) {
	$res_button = "<p align =\"left\">\n";
	foreach ($arr_button as $bname => $button_type) {
		foreach ($button_type as $btype => $button_value) {
			if($btype == "submit") {
				$type = "type=\"submit\" name =\"$bname\"";
			}
			elseif ($btype == "reset") {
				$type = "type=\"reset\"";
			} 
			$res_button .= "<input $type value=\"$button_value\" class=\"btn btn-info btn-sm\" />&nbsp;";	
		}
	}
	$res_button .="</p>\n";
	return $res_button;
}

/*
 * Create back button function															                 			
 * 
 */

function back_button() { 
	$back_button = "<a href=\"javascript:history.back(-1)\">
						<div>
							<span class=\"glyphicon glyphicon-circle-arrow-left\"></span>
							<span>Previous page</span>
						</div>
					</a><br>";
	return $back_button;
} 

/*  
 * Generate notification bar that will be placed on top of the page. 
 * 
 */

function public_notif($notify,$notify_msg) { 
	$notif_gen 		= "";
	$notif_tpl 			= "<tr><td colspan=\"2\" height=\"25\" bgcolor=\"%s\" align=\"center\"><b><font color=\"white\" size=\"2\"><marquee>%s</marquee></font></b></td></tr>";
	if($notify){
		$color_notif 	= "#ff0000";
		$notif_gen 		= sprintf($notif_tpl,$color_notif,$notify_msg);
	}
	elseif(date('d.m') == '03.08' AND !$notify) {
		$color_notif 	= "#0099ff";
		$notif_gen 		= sprintf($notif_tpl,$color_notif,"STORIX just want to say \"Happy Birthday, have a pleasant surprises, many successes, live long and prosper\" to its system creator, M. Aryo N. Pratama.");
	}
	return $notif_gen;
} 

/* -- Additional functions goes here -- */

require_once LIB_PATH.'additional.lib.php';

?>
