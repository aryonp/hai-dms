<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();

$page_title		= "User Level";
$page_id		= "5";
chkSecurity($page_id);

$query ="SELECT id, name, hidden FROM user_level WHERE del = '0' ORDER BY id ASC ";
$pagingResult = new Pagination();
$pagingResult->setPageQuery($query);
$pagingResult->paginate();

$this_page = $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();

$status = "&nbsp;";

if (isset($_POST['add_level'])){
	$level  = strip_tags(trim($_POST['level_msg']));
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	$hidden = (is_numeric($_POST['level_hide']) OR !empty($_POST['level_hide']))?$_POST['level_hide']:0;
	$add_level_q  ="INSERT INTO user_level (name,createBy,createDate,updBy,updDate,hidden) VALUES ('$level','$uid','$date','$uid','$date','$hidden');"; 
	if (!empty($level)){
		mysql_query($add_level_q) or die(mysql_error());
		log_hist(13,$level);
		header("location:$this_page");
		exit();
	}
	else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to create new user level. Please check all available parameters.
				    </div>";
		log_hist(14,$level);
	}
}
if (isset($_POST['upd_level'])){
	$nid 	 = trim($_POST['nid']);
	$level 	 = strip_tags(trim($_POST['level']));
	$hidden  = (is_numeric($_POST['hidden']) OR !empty($_POST['hidden']))?$_POST['hidden']:0;
	$uid	 = $_SESSION['uid'];
	$date	 = date('Y-m-d H:i:s');
	$upd_level_q = "UPDATE user_level SET name = '$level', hidden = '$hidden', updBy = '$uid', updDate ='$date' WHERE id ='$nid';";
	if(@mysql_query($upd_level_q)){
		log_hist(15,$level);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to update the user level. Please check all available parameters.
				    </div>";
		log_hist(16,$level);
	}
}
if (isset($_GET['did'])){
	$did  = trim($_GET['did']);
	$uid  = $_SESSION['uid'];
	$date = date('Y-m-d H:i:s');
	$del_level_q  ="UPDATE user_level SET updBy = '$uid', updDate ='$date', del = '1' WHERE id ='$did';";
	if(@mysql_query($del_level_q)){
		log_hist(17,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to delete user level. Please check all available parameters.
				    </div>";
		log_hist(18,$did);
	}
}

if(isset($_POST['cancel'])){
	header("location:$this_page"); 	
}

include THEME_DEFAULT.'header.php'; ?>
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
<?=$status?>
<div class="sub-header">
	<a href="#" data-toggle="collapse" data-target="#form-ulevel">
		<div>
			<span>Add New User Level</span>
		</div>
	</a><br>
		<div id="form-ulevel" class="collapse">
			<form class="well form-inline" role="form" method="post">
  				<div class="form-group">
      				<input type="text" name="level_msg" class="form-control" placeholder="Enter New User Level Here">
  				</div>
  				<div class="form-group">
      				<input type="text" name="level_hide" class="form-control" placeholder="1 for Hide, 0 for Show">
  				</div>
  				<div class="form-group">
  					<button type="submit" name="add_level" class="btn btn-primary">Add New Level</button>
  				</div>
			</form>
		</div>
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
					<th width="*" align="left">&nbsp;<b>LEVEL</b>&nbsp;</td>
					<th width="35" align="left">&nbsp;<b>HIDDEN</b>&nbsp;</td>
					<th width="*" colspan="2" align="center">&nbsp;<b>CMD</b>&nbsp;</td>
				</tr>
			</thead>
			<tbody>
<?php 	if($pagingResult->getPageRows()>= 1) {	
			$count = $pagingResult->getPageOffset() + 1;
			$result = $pagingResult->getPageArray();
			while ($level_list_array = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if (isset($_GET['nid']) && $_GET['nid'] == $level_list_array["id"]) {?>
				<form method="POST" action="">
				<input type="hidden" name="nid" value="<?=$level_list_array["id"]?>">
				<tr bgcolor="#ffcc99" align="left" valign="top">
					<td width="25">&nbsp;<?=$count?>.</td>
					<td><input type="text" class="form-control" name="level" size="30" value="<?=($level_list_array["name"])?ucwords($level_list_array["name"]):"-";?>"></td>
					<td><input type="text" class="form-control" name="hidden" size="5" value="<?=$level_list_array["hidden"]?>"></td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="upd_level">
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
<?php 		} else { ?>
				<tr align="left" valign="top">
					<td>&nbsp;<?=$count?>.</td>
					<td>&nbsp;<?=($level_list_array["name"])?ucwords($level_list_array["name"]):"-";?> </td>
					<td>&nbsp;<?=($level_list_array["hidden"] == '1')?"YES":"NO";?></td>
					<td width="60" align="center">
						<a title="Edit Level" class="btn btn-default" href="<?=$this_page?>&nid=<?=$level_list_array["id"]?>">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</td>
					<td width="60" align="center">
						<a title="Delete Level" class="btn btn-default" href="<?=$this_page?>&did=<?=$level_list_array["id"]?>">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					</td>
				</tr>
<?php	 		} $count++; 
			}
		} else {?>
				<tr><td colspan="5" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
				<?php } ?>
			</tbody>
			</table>
				<?=$pagingResult->pagingMenu()?>
		</div>
		</div>
</div>
<//-----------------CONTENT-END-------------------------------------------------//>

<?php include THEME_DEFAULT.'footer.php'; ?>
