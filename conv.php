<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();

$page_title		= "Conversion Tool";
$page_id		= "5";
chkSecurity($page_id);

$comm_list_q 	= "SELECT c.id, c.name, c.unit, c.sku
		           FROM commodity c
		           WHERE c.del = 0;";
$comm_list_SQL	= @mysql_query($comm_list_q) or die(mysql_error());


$query ="SELECT co.id, c.id AS cid, c.sku, c.name AS cname, c.unit, co.conv 
		 FROM conv AS co 
		 LEFT JOIN commodity AS c ON (c.id = co.cid)
		 WHERE co.del = '0' AND co.conv != '' ORDER BY c.name ASC ";
$pagingResult = new Pagination();
$pagingResult->setPageQuery($query);
$pagingResult->paginate();

$this_page = $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();

$status = "&nbsp;";

if (isset($_POST['add_co'])){
	$comm	 = trim($_POST['commodity']);
	$conv 	 = trim($_POST['conv']);
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	$add_q  ="INSERT INTO conv (cid,conv,createID,createDate,updID,updDate) VALUES ('$comm','$conv','$uid','$date','$uid','$date');"; 
	if (!empty($comm) AND !empty($conv)){
		mysql_query($add_q) or die(mysql_error());
		log_hist(13,$comm);
		header("location:$this_page");
		exit();
	}
	else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to create new conversion unit. Please check all available parameters.
				    </div>";
		log_hist(14,$comm);
	}
}
if (isset($_POST['upd_co'])){
	$nid 	 = trim($_POST['nid']);
	$level 	 = strip_tags(trim($_POST['level']));
	$hidden  = (is_numeric($_POST['hidden']) OR !empty($_POST['hidden']))?$_POST['hidden']:0;
	$uid	 = $_SESSION['uid'];
	$date	 = date('Y-m-d H:i:s');
	$upd_q = "UPDATE conv SET cid = '$level', conv = '$conv', updID = '$uid', updDate ='$date' WHERE id ='$nid';";
	if(@mysql_query($upd_q)){
		log_hist(15,$level);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to update the conversion unit. Please check all available parameters.
				    </div>";
		log_hist(16,$level);
	}
}
if (isset($_GET['did'])){
	$did  = trim($_GET['did']);
	$uid  = $_SESSION['uid'];
	$date = date('Y-m-d H:i:s');
	$del_q  ="UPDATE user_level SET updID = '$uid', updDate ='$date', del = '1' WHERE id ='$did';";
	if(@mysql_query($del_q)){
		log_hist(17,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to delete conversion unit. Please check all available parameters.
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
			<span>Add New Conversion Tool</span>
		</div>
	</a><br>
		<div id="form-ulevel" class="collapse">
			<form class="well form-inline" role="form" method="post">
  				<div class="form-group">
      				<select class="form-control" name="commodity">
    			<option SELECTED>---------------------</option>
<?php 
  	while($comm_list_array = mysql_fetch_array($comm_list_SQL,MYSQL_ASSOC)){?>
    <option value="<?=$comm_list_array["id"]?>"><?=ucwords($comm_list_array["name"])?> -- <?=$comm_list_array["unit"];?></option>
<? } ?></select>
  				</div>
  				<div class="form-group">
      				<input type="text" name="conv" class="form-control" placeholder="Capacity per pallet">
  				</div>
  				<div class="form-group">
  					<button type="submit" name="add_co" class="btn btn-primary">Add New Conversion</button>
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
            		<th width="*" align="left">&nbsp;<b>SKU</b>&nbsp;</td>
					<th width="*" align="left">&nbsp;<b>COMMODITY</b>&nbsp;</td>
					<th width="35" align="left">&nbsp;<b>CAPACITY</b>&nbsp;</td>
					<th width="*" colspan="2" align="center">&nbsp;<b>CMD</b>&nbsp;</td>
				</tr>
			</thead>
			<tbody>
<?php 	if($pagingResult->getPageRows()>= 1) {	
			$count = $pagingResult->getPageOffset() + 1;
			$result = $pagingResult->getPageArray();
			while ($array = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if (isset($_GET['nid']) && $_GET['nid'] == $array["id"]) {?>
				<form method="POST" action="">
				<input type="hidden" name="nid" value="<?=$array["id"]?>">
				<tr bgcolor="#ffcc99" align="left" valign="top">
					<td width="25">&nbsp;<?=$count?>.</td>
					<td><input type="text" class="form-control" name="cid" value="<?=($array["cname"])?ucwords($array["cname"]):"-";?>"></td>
					<td><input type="text" class="form-control" name="conv" value="<?=$array["conv"]?>"></td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="upd_co">
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
					<td>&nbsp;<?=($array["sku"])?$array["sku"]:"-";?> </td>
					<td>&nbsp;<?=($array["cname"])?ucwords($array["cname"]):"-";?> </td>
					<td>&nbsp;<?=($array["conv"])?$array["conv"]." ".$array["unit"]:"-";?> per pallet</td>
					<td width="60" align="center">
						<a title="Edit" class="btn btn-default" href="<?=$this_page?>&nid=<?=$array["id"]?>">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</td>
					<td width="60" align="center">
						<a title="Delete" class="btn btn-default" href="<?=$this_page?>&did=<?=$array["id"]?>">
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
