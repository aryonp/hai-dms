<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();

$page_title	= "Log Code";
$page_id	= "10";
chkSecurity($page_id);

$code_q = "SELECT id, notes FROM log_code WHERE del = '0' ORDER BY id ASC ";
$pagingResult = new Pagination();
$pagingResult->setPageQuery($code_q);
$pagingResult->paginate();

$this_page = $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();

$status = "";

if (isset($_POST['add_code']) && isset($_POST['log_code'])){
	$notes 	= strtolower(trim($_POST['log_code']));
	$uid	= $_SESSION['uid'];
	$date   = date('Y-m-d H:i:s');
	$add_code_q  ="INSERT INTO log_code (notes,createBy,createDate,updBy,updDate) VALUES ('$notes','$uid','$date','$uid','$date');"; 
	if (@mysql_query($add_code_q)) {
		log_hist(43,$notes);
		header("location:$this_page");
		exit();
	}
	else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to create log code. Please check all available parameters.
				    </div>";
		log_hist(44,$notes);
	}
}

if (isset($_POST['upd_code']) && !empty($_POST['lcnotes']) && is_numeric($_POST['lcid'])){
	$nid 	= trim($_POST['lcid']);
	$notes 	= strtolower(trim($_POST['lcnotes']));
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	$upd_code_q = "UPDATE log_code SET notes = '$notes', updBy = '$uid', updDate = '$date' WHERE id ='".$nid."';";
	if (@mysql_query($upd_code_q)) {
		log_hist(45,$nid);
		header("location:$this_page");
		exit();
	}
	else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to update log code. Please check all available parameters.
				    </div>";
		log_hist(46,$nid);
	}
}

if (isset($_GET['did'])){
	$did 	= trim($_GET['did']);
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	$del_code_q  ="UPDATE log_code SET updBy = '$uid', updDate = '$date', del = '1' WHERE id ='".$did."';";
	if(@mysql_query($del_code_q)) {
		log_hist(47,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to delete log code. Please check all available parameters.
				    </div>";
		log_hist(48,$did);
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
	<a href="#" data-toggle="collapse" data-target="#form-lcode">
		<div>
			<span>Add New Log Code</span>
		</div>
	</a><br>
	<div id="form-lcode" class="collapse">
			<form class="well form-inline" role="form" method="POST">
  				<div class="form-group">
      					<input type="text" name="log_code" size=60 class="form-control" placeholder="Enter New Log Code Here">
  				</div>
  				<div class="form-group">
  					<button type="submit" name="add_code" class="btn btn-primary">Add Code</button>
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
		<table border="0" cellpadding="1" cellspacing="1" width="100%" data-toolbar="#toolbar-buttons" class="table table-striped table-bordered table-condensed">	
			<thead>
            	<tr><th width="25" align="left">&nbsp;<b>NO</b>&nbsp;</td>
                <th width="30" align="left">&nbsp;<b>CODE</b>&nbsp;</td>
                <th width="*" align="left">&nbsp;<b>NOTES</b>&nbsp;</td>
                <th width="*" colspan="2" align="center">&nbsp;<b>CMD</b>&nbsp;</td>
			</tr>
			</thead>
			<tbody>
<?php 
   if ($pagingResult->getPageRows()>= 1) {	
			$count = $pagingResult->getPageOffset() + 1;
			$result = $pagingResult->getPageArray();
			while ($code_list_arr = mysql_fetch_array($result, MYSQL_ASSOC)) { 
				if (isset($_GET['lcid']) && $_GET['lcid'] == $code_list_arr["id"]) {?>
				<form method="POST" action="">
				<input type="hidden" name="lcid" value="<?=$code_list_arr["id"]?>">
				<tr bgcolor="#ffcc99" align="left" valign="top">
					<td width="25">&nbsp;<?=$count?>.&nbsp;</td>
					<td>&nbsp;<?=($code_list_arr["id"])?$code_list_arr["id"]."#":"-";?>&nbsp;</td>
					<td><input type="text" name="lcnotes" class="form-control" value="<?=($code_list_arr["notes"])?strtoupper($code_list_arr["notes"]):"-";?>">&nbsp;</td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="upd_code">
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
<?php 			} else { ?>
				<tr align="left" valign="top">
					<td>&nbsp;<?=$count?>.&nbsp;</td>
					<td>&nbsp;<?=($code_list_arr["id"])?$code_list_arr["id"]."#":"-";?>&nbsp;</td>
					<td>&nbsp;<?=($code_list_arr["notes"])?strtoupper($code_list_arr["notes"]):"-";?>&nbsp;</td>
					<td width="60" align="center">
						<a title="Edit Code" class="btn btn-default" href="<?=$this_page?>&lcid=<?=$code_list_arr["id"]?>">
							<span class="glyphicon glyphicon-pencil"></span>
						</a></td>
					<td width="60" align="center">
						<a title="Delete Code" class="btn btn-default" href="<?=$this_page?>&did=<?=$code_list_arr["id"]?>">
							<span class="glyphicon glyphicon-trash"></span>
						</a></td>
				</tr>
<?php	 		} $count++; 
			}
		} else {?>
				<tr><td colspan="5" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
<?php 	} ?>
		</tbody>
			</table>
			<?=$pagingResult->pagingMenu()?>
			</div>
		</div>
		</div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php'; ?>