<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();

$page_title = "Log History";
$page_id 	= "11";
chkSecurity($page_id);

$log_list_q ="SELECT lh.id, 
				CONCAT(u.fname,' ',u.lname) AS fullname, 
				lh.ip_addr, 
				lh.time, 
				CONCAT(lc.notes,' ',lh.notes) AS notes ".
		     "FROM log_history lh 
		     	LEFT JOIN user u ON (u.id = lh.uid) 
		     	LEFT JOIN log_code lc ON (lc.id = lh.cid) 
		      WHERE lh.del = '0' 
		      ORDER BY lh.time DESC ";

$pagingResult = new Pagination();
$pagingResult->setPageQuery($log_list_q);
$pagingResult->paginate();

$this_page = $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();

include THEME_DEFAULT.'header.php'; ?>
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
<div class="panel panel-default">
	<div class="panel-heading">
		&nbsp;
	</div>
	<div class="panel-body">
        <?=$pagingResult->pagingMenu()?>
        	<br>
        	<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-striped table-bordered table-condensed">	
			<thead>
            	<tr><th width="25" align="left">&nbsp;<b>NO</b>&nbsp;</td>
                 	<th width="*">&nbsp;<b>NAME</b>&nbsp;</td>
                 	<th width="*">&nbsp;<b>IP ADDRESS</b>&nbsp;</td>
                 	<th width="*">&nbsp;<b>TIME</b>&nbsp;</td>
                 	<th width="*">&nbsp;<b>REMARKS</b>&nbsp;</td>
				</tr>
			</thead>
			<tbody>
<?php 
   if ($pagingResult->getPageRows()>= 1) {	
			$count = $pagingResult->getPageOffset() + 1;
			$result = $pagingResult->getPageArray();
			while ($log_list_arr = mysql_fetch_array($result, MYSQL_ASSOC)) {?>
				<tr align="left" valign="top">
					<td width="25" align="left">&nbsp;<?=$count?>.</td>
					<td>&nbsp;<?=($log_list_arr["fullname"])?ucwords($log_list_arr["fullname"]):"-"?>&nbsp;</td>
					<td>&nbsp;<?=($log_list_arr["ip_addr"])?ucwords($log_list_arr["ip_addr"]):"-"?>&nbsp;</td>
					<td>&nbsp;<?=($log_list_arr["time"])?cplday('d M Y H:i:s',$log_list_arr["time"]):"-"?>&nbsp;</td>
					<td>&nbsp;<?=($log_list_arr["notes"])?strtoupper($log_list_arr["notes"]):"-"?>&nbsp;</td>
				</tr>
<?php			$count++;  
				}
			} else {?>
				<tr><td colspan="5" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
<?php 			}	?>
			</tbody>
			</table>
		</td></tr>
<?=$pagingResult->pagingMenu()?>
</div></div>

<//-----------------CONTENT-END-------------------------------------------------//>       				
<?php include THEME_DEFAULT.'footer.php';?>