<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();

$page_title 	= "MOTD";
$page_id	 	= "6";
chkSecurity($page_id);

$motd_list_q 	= "SELECT id, message FROM motd WHERE del = 0 ORDER BY id ASC ";
$pagingResult 	= new Pagination();
$pagingResult->setPageQuery($motd_list_q);
$pagingResult->paginate();

$this_page = $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();

$status = "";

if (isset($_POST['add_motd'])){
	$message = strtolower(trim($_POST['motd_msg']));
	$uid	 = $_SESSION['uid'];
	$date	 = date('Y-m-d H:i:s');
	$add_motd_q  ="INSERT INTO motd (message,createBy,createDate,updBy,updDate) VALUES ('$message','$uid','$date','$uid','$date');"; 
	
	if (!empty($message)){
		if(@mysql_query($add_motd_q)) {
			log_hist(19,$message);
			header("location:$this_page");
			exit();
		} else {
			$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
							Failed to create new message of the day. Internal error occured.
				    	</div>";
			log_hist(20,$message);
		}
	}
	else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to create new message of the day. Missing required information.
				    </div>";
		log_hist(20,$message);
	}
	
}

if (isset($_POST['upd_motd'])){
	$nid 	 = trim($_POST['nid']);
	$message = trim($_POST['msg']);
	$uid	 = $_SESSION['uid'];
	$date	 = date('Y-m-d H:i:s');
	$upd_motd_q  ="UPDATE motd SET message = '$message', updBy = '$uid', updDate ='$date' WHERE id ='$nid';";
	if(@mysql_query($upd_motd_q)) {
		log_hist(21,$nid);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to update new message of the day. Please check all available parameters.
				    </div>";
		log_hist(22,$nid);
	}
}

if (isset($_GET['did'])){
	$did = trim($_GET['did']);
	$uid = $_SESSION['uid'];
	$date = date('Y-m-d H:i:s');
	$del_motd_q  ="UPDATE motd SET updBy = '$uid', updDate ='$date', del = '1' WHERE id ='$did';";
	if(@mysql_query($del_motd_q)) {
		log_hist(23,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to delete new message of the day. Please check all available parameters.
				    </div>";
		log_hist(24,$did);
	}
}

if(isset($_POST['cancel'])){
	header("location:$this_page"); 	
}

include THEME_DEFAULT.'header.php'; ?>
<//-----------------CONTENT-START-------------------------------------------------//>
<?=$status?>
<h1 class="page-header"><?=$page_title?> Page</h1>
<div class="sub-header">
	<a href="#" data-toggle="collapse" data-target="#form-motd">
		<div>
			<span>Add New MOTD</span>
		</div>
	</a><br>
	<div id="form-motd" class="collapse">
			<form class="well form-inline" role="form" method="post">
  				<div class="form-group">
      					<input type="text" name="motd_msg" size=60 class="form-control" placeholder="Enter New MOTD Here">
  				</div>
  				<div class="form-group">
  					<button type="submit" name="add_motd" class="btn btn-primary">Add New MOTD</button>
  				</div>
			</form>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			&nbsp;
		</div>
		<div class="panel-body">
        	<?=$pagingResult->pagingMenu();?>
        	<br>
			<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-striped table-bordered table-condensed">	
			<thead>
            <tr align="left" valign="middle">
            	<th width="25" align="left">&nbsp;<b>NO</b></td>
                <th width="*" align="left">&nbsp;<b>MESSAGE</b></td>
                <th width="*" colspan="2" align="center">&nbsp;<b>CMD</b>&nbsp;</td>
			</tr>
			</thead>
			<tbody>
<?php 
   if ($pagingResult->getPageRows()>= 1) {	
			$count = $pagingResult->getPageOffset() + 1;
			$result = $pagingResult->getPageArray();
			while ($motd_list_arr = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if (isset($_GET['nid']) && $_GET['nid'] == $motd_list_arr["id"]) {?>
				<form method="POST" action="">
				<input type="hidden" name="nid" value="<?=$motd_list_arr["id"]?>">
				<tr bgcolor="#ffcc99" align="left" valign="top">
					<td width="25">&nbsp;<?=$count?>.</td>
					<td><textarea cols="60" class="form-control" rows="2" name="msg" wrap="virtual"><?=ucwords($motd_list_arr["message"])?></textarea></td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="upd_motd">
							<span class="glyphicon glyphicon-floppy-saved"></span>
						</button>
					</td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="cancel">
							<span class="glyphicon glyphicon-floppy-remove"></span>
						</button>
					</td>
				</tr>
				</form>
	<?php 		
			} else { ?>
				<tr align="left" valign="top">
					<td>&nbsp;<?=$count?>.</td>
					<td>&nbsp;<?=ucwords($motd_list_arr["message"])?> </td>
					<td width="60" align="center">
						<a title="Edit Message" class="btn btn-default" href="<?=$this_page?>&nid=<?=$motd_list_arr["id"]?>">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</td>
					<td width="60" align="center">
						<a title="Delete Message" class="btn btn-default" href="<?=$this_page?>&did=<?=$motd_list_arr["id"]?>">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					</td>
				</tr>
			<?php	 
				} $count++; 
			}
		} else {?>
				<tr><td colspan="4" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
				<?php } ?>
			</tbody>
			</table>
			<?=$pagingResult->pagingMenu()?>
		</div>
		</div>
</div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php'; ?>