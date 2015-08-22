<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();	

$page_title	= "Report";
$page_id 	= "12";
chkSecurity($page_id);

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
	     WHERE e.createBy = '".$_SESSION["uid"]."' AND e.del = '0'
		 ORDER BY e.sdate DESC ";

$pagingResult = new Pagination();
$pagingResult->setPageQuery($ev_q);
$pagingResult->paginate();

$this_page 	= $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();

include THEME_DEFAULT.'header.php'; ?>             			
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>

<div class="sub-header">
	<div class="panel panel-default">
		<div class="panel-heading">&nbsp;
		</div>
		<div class="panel-body">
        	<?=$pagingResult->pagingMenu();?>
        	<br>
        	<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-striped table-bordered table-condensed">	
				<thead>
            	<tr valign="middle"> 
                 	<th width="25">&nbsp;<b>NO.</b></td>
					<th width="*" align="left">&nbsp;<b>NAME</b></td>
                 	<th width="*" align="left">&nbsp;<b>START DATE</b>&nbsp;</td>
                 	<th width="*" align="left">&nbsp;<b>END DATE</b>&nbsp;</td>
                 	<th width="*" align="left">&nbsp;<b>LOCKET</b>&nbsp;</td>
                 	<th width="*" align="center">&nbsp;<b>CMD</b></td>
				</tr>
				</thead>
				<tbody>
<?php 
   if ($pagingResult->getPageRows()>= 1) {	
		$count = $pagingResult->getPageOffset() + 1;
		$result = $pagingResult->getPageArray();
		while ($array = mysql_fetch_array($result, MYSQL_ASSOC)) { ?>
			<tr valign="top">
				<td align="left">&nbsp;<?=$count?>.&nbsp;</td>
				<td align="left">&nbsp;<?=($array["evname"])?ucwords($array["evname"]):"-";?>&nbsp;</td>
				<td align="left">&nbsp;<?=($array["sdate"])?cplday('D, j M Y',$array["sdate"]):"-";?>&nbsp;</td>
				<td align="left">&nbsp;<?=($array["edate"])?cplday('D, j M Y',$array["edate"]):"-";?>&nbsp;</td>
				<td align="left">&nbsp;<?=($array["qty_locket"])?$array["qty_locket"]:"-";?>&nbsp;</td>
				<td width="60" align="center" valign="middle">
					<a title="View Details" class="btn btn-default" href="./report_det.php?evid=<?=$array["id"]?>">
						<span class="glyphicon glyphicon-stats"></span>
						DASHBOARD
					</a>
				</td>
			</tr>
<?php 	$count++;  
		}
	} else { ?>
		<tr><td colspan="6" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
				<?php } ?></tbody>
			</table>
				<?=$pagingResult->pagingMenu();?>
				<br>
	</div>
</div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php'; ?>