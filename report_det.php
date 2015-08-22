<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();	

$page_title	= "Dashboard";
$page_id 	= "12";
chkSecurity($page_id);

$evid 		= ((isset ($_GET['evid']) && $_GET['evid'] != '')?trim($_GET['evid']):'');

$ev_q = "SELECT e.id,
		 		CONCAT(u.fname,' ',u.lname) AS fullname,
				e.name AS evname,
				e.ticker,
				e.sdate,
				e.edate,
				e.createBy,
				e.createDate,
				e.qty_locket
		FROM events e
		INNER JOIN user u ON (u.id = e.createBy)
		WHERE e.id = '$evid' AND e.del = '0'";
$ev_SQL = @mysql_query($ev_q) or die(mysql_error());
$ev_arr = mysql_fetch_array($ev_SQL,MYSQL_ASSOC);

$lo_det_q = "SELECT res.lname AS lname,
					SUM(CASE res.tstatus WHEN 'occupied' THEN 1 ELSE 0 END) AS occupied,
					SUM(CASE res.tstatus WHEN 'skip' THEN 1 ELSE 0 END) AS skip,
					SUM(CASE res.tstatus WHEN 'closed' THEN 1 ELSE 0 END) AS closed,SUM(1) AS total
			FROM rep_ev_status res
			WHERE res.eid = '$evid'
			GROUP BY res.lname";
$lo_det_SQL = @mysql_query($lo_det_q) or die(mysql_error());

$time_det_q = "SELECT ret.fullname AS fullname, COUNT(ret.tnbr) AS ttotal, AVG(ret.tdiff) AS tdiff
			   FROM rep_ev_time ret
			   WHERE ret.evid = '$evid'
			   GROUP BY ret.oid
			   ORDER BY ret.fullname ASC;";
$time_det_SQL = @mysql_query($time_det_q) or die(mysql_error());

include THEME_DEFAULT.'header.php'; ?>             			
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>

<a href="./report.php">
	<div>
		<span class="glyphicon glyphicon-circle-arrow-left"></span>
		<span>Report Page</span>
	</div>
</a>
<br>
<?php if(mysql_num_rows($ev_SQL)>=1) { ?>

Event : <?=($ev_arr["evname"])?ucwords($ev_arr["evname"]):"-";?>
<br>
Event's Period : <?=($ev_arr["sdate"])?cplday('D, j M Y',$ev_arr["sdate"]):"-";?> - <?=($ev_arr["edate"])?cplday('D, j M Y',$ev_arr["edate"]):"-";?>
<br>
Event Organizer : <?=($ev_arr["fullname"])?ucwords($ev_arr["fullname"]):"-";?>

<br>
<br>
<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-striped table-hover table-condensed">	
	<thead>
    	<tr valign="middle">
			<th width="*">&nbsp;<b>LOCKET</b></td>
            <th width="*" class="text-center">&nbsp;<b>OCCUPIED</b>&nbsp;</td>
           	<th width="*" class="text-center">&nbsp;<b>SKIP</b>&nbsp;</td>
            <th width="*" class="text-center">&nbsp;<b>CLOSED</b>&nbsp;</td>
            <th width="*" class="text-center">&nbsp;<b>TOTAL</b></td>
		</tr>
	</thead>
	<tbody>
<?php 
   if (mysql_num_rows($lo_det_SQL) >= 1) {	
   		$t_occupied = 0;$t_skip = 0;$t_closed = 0;$t_total = 0;
		while ($lo_det_arr = mysql_fetch_array($lo_det_SQL,MYSQL_ASSOC)) { ?>
		<tr valign="top">
			<td>&nbsp;<?=($lo_det_arr["lname"])?$lo_det_arr["lname"]:"-";?>&nbsp;</td>
			<td class="text-center">&nbsp;<?=($lo_det_arr["occupied"])?$lo_det_arr["occupied"]:"-";?>&nbsp;</td>
			<td class="text-center">&nbsp;<?=($lo_det_arr["skip"])?$lo_det_arr["skip"]:"-";?>&nbsp;</td>
			<td class="text-center">&nbsp;<?=($lo_det_arr["closed"])?$lo_det_arr["closed"]:"-";?>&nbsp;</td>
			<td class="text-center">&nbsp;<?=($lo_det_arr["total"])?$lo_det_arr["total"]:"-";?>&nbsp;</td>
		</tr>
<?php 	$t_occupied += $lo_det_arr["occupied"];
		$t_skip += $lo_det_arr["skip"];
		$t_closed += $lo_det_arr["closed"];
		$t_total += $lo_det_arr["total"];		
		} ?>
		<tr valign="top">
			<td>&nbsp;<b>TOTAL</b>&nbsp;</td>
		    <td class="text-center">&nbsp;<b><?=$t_occupied?></b>&nbsp;</td>
		    <td class="text-center">&nbsp;<b><?=$t_skip?></b>&nbsp;</td>
		    <td class="text-center">&nbsp;<b><?=$t_closed?></b>&nbsp;</td>
		    <td class="text-center">&nbsp;<b><?=$t_total?></b>&nbsp;</td>
	 	</tr>
<?php } else {?>
		<tr><td colspan="5" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
<?php } ?>
	</tbody>
</table>

<br>
<br>
<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-hover table-striped table-condensed">	
	<thead>
    	<tr valign="middle">
			<th width="*" align="left">&nbsp;<b>NAME</b></td>
			<th width="*" class="text-center">&nbsp;<b>TOTAL CLOSED TICKET</b></td>
            <th width="*" class="text-center">&nbsp;<b>AVG TIME SERVICE/CUSTOMER</b>&nbsp;</td>
		</tr>
	</thead>
	<tbody>
<?php 
   if (mysql_num_rows($time_det_SQL) >= 1) {
		while ($time_det_arr = mysql_fetch_array($time_det_SQL,MYSQL_ASSOC)) { ?>
		<tr valign="top">
			<td align="left">&nbsp;<?=($time_det_arr["fullname"])?ucwords($time_det_arr["fullname"]):"-";?>&nbsp;</td>
			<td class="text-center">&nbsp;<?=($time_det_arr["ttotal"])?$time_det_arr["ttotal"]:"-";?>&nbsp;</td>
			<td class="text-center">&nbsp;<?=($time_det_arr["tdiff"])?$time_det_arr["tdiff"]:"-";?>&nbsp;</td>
		</tr>
<?php 		
		} 
	} else {?>
		<tr><td colspan="3" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
<?php } ?>
	</tbody>
</table>

<?php } ?>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php'; ?>