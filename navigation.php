<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();

$page_title		= "Permission Rules";
$page_id	 	= "9";
chkSecurity($page_id);

$query 			= "SELECT id, sort, icon, name, link, permit FROM navigation WHERE del = '0' ORDER BY sort ASC ";
$pagingResult 	= new Pagination();
$pagingResult->setPageQuery($query);
$pagingResult->paginate();
$this_page = $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();

$level_list_q 	= "SELECT id, name FROM user_level WHERE del = '0' ORDER BY id ASC ;";
$level_list_SQL = @mysql_query($level_list_q) or die(mysql_error());

$status ="";

if (isset($_POST['add_nav'])){
	$sort = trim($_POST['sort']);
	$name = trim($_POST['name']);
	$link = trim($_POST['link']);
	$permit = trim($_POST['permit']);
	$icon	= trim($_POST['icon']);
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	if (!empty($sort) AND !empty($name) AND !empty($icon) AND !empty($link) AND !empty($permit)){
		$add_nav_q  ="INSERT INTO navigation (sort,icon,name,link,permit,createBy,createDate,updBy,updDate) 
		              VALUES ('$sort','$icon','$name','$link','$permit','$uid','$date','$uid','$date');"; 
		@mysql_query($add_nav_q) or die(mysql_error());
		log_hist(37,$name);
		header("location:$this_page");
	}
	
	else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to create new navigation rule. Please check all available parameters.
				    </div>";
		log_hist(38,$name);
	}
}

if (isset($_POST['upd_nav'])){
	$nid  = trim($_POST['nid']);
	$sort = trim($_POST['sort']);
	$name = trim($_POST['name']);
	$link = trim($_POST['link']);
	$permit = trim($_POST['permit']);
	$icon	= trim($_POST['icon']);
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	$upd_nav_q  = "UPDATE navigation 
	               SET sort='$sort', icon = '$icon', name='$name', link='$link', permit='$permit', updBy='$uid', updDate='$date'  
	               WHERE id ='$nid';";
	if(@mysql_query($upd_nav_q)) {
		log_hist(39,$name);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to update navigation rule. Please check all available parameters.
				    </div>";
		log_hist(40,$name);
	}
}

if (isset($_GET['did'])){
	$did  = trim($_GET['did']);
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	$del  = "UPDATE navigation SET updBy='$uid', updDate='$date', del = '1' WHERE id ='$did';";
	if (@mysql_query($del)) {
		log_hist(41,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to delete navigation rule. Please check all available parameters.
				    </div>";
		log_hist(42,$did);
	}
}

if(isset($_POST['cancel'])){
	header("location:$this_page"); 	
}

include THEME_DEFAULT.'header.php'; ?>
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
<?=$status?>
	<a href="#" data-toggle="collapse" data-target="#form-rules">
		<div>
			<span>Add New Rules</span>
		</div>
	</a><br>
<div id="form-rules" class="collapse">
		<div class="well">
<?php 	$d_perm = array();
		while($level_list_arr = mysql_fetch_array($level_list_SQL,MYSQL_ASSOC)) { 
			array_push($d_perm, $level_list_arr["id"]." = ".ucwords($level_list_arr["name"]));
 		} 
 		echo implode(", ", $d_perm);?>
		</div>
			<form method="POST" class="well form-inline" role="form">
  				<div class="form-group">
      					<input type="text" name="sort" class="form-control" placeholder="Enter Sort">
  				</div>
  				<div class="form-group">
      					<input type="text" name="icon" class="form-control" placeholder="Enter Icon">
  				</div>
  				<div class="form-group">
      					<input type="text" name="name" class="form-control" placeholder="Enter Name">
  				</div>
  				<div class="form-group">
      					<input type="text" name="link" class="form-control" placeholder="Enter Link">
  				</div>
  				<div class="form-group">
      					<input type="text" name="permit" class="form-control" placeholder="Enter Permission">
  				</div>
  				<div class="form-group">
  					<button type="submit" name="add_nav" class="btn btn-primary">Add New Rules</button>
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
            		<th width="*" align="left">&nbsp;<b>ID</b>&nbsp;</td>
                 	<th width="*" align="left">&nbsp;<b>SORT</b>&nbsp;</td>
                 	<th width="*" align="left">&nbsp;<b>ICON</b>&nbsp;</td>
                 	<th width="*" align="left">&nbsp;<b>NAME</b>&nbsp;</td>
                 	<th width="*" align="left">&nbsp;<b>LINK</b>&nbsp;</td>
                 	<th width="*" align="right">&nbsp;<b>PERMISSION</b>&nbsp;</td>
                 	<th width="*" colspan="2" align="center">&nbsp;<b>CMD</b>&nbsp;</td>
				</tr>
			</thead>
			<tbody>
<?php 	if ($pagingResult->getPageRows()>= 1) {	
			$count = $pagingResult->getPageOffset() + 1;
			$result = $pagingResult->getPageArray();
			while ($array = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if (isset($_GET['nid']) && $_GET['nid'] == $array["id"]) {?>
				<form method="POST" action="">
				<input type="hidden" name="nid" value="<?=$array["id"]?>">
				<tr bgcolor="#ffcc99" align="left" valign="top">
					<td width="25" align="left">&nbsp;<?=$count?>.</td> 
					<td>#<?=$array["id"]?></td>
					<td><input type="text" name="sort" class="form-control" value="<?=($array["sort"])?$array["sort"]:"-";?>">&nbsp;</td>
					<td><input type="text" name="icon" class="form-control" value="<?=($array["icon"])?$array["icon"]:"-";?>">&nbsp;</td>
					<td><input type="text" name="name" class="form-control" value="<?=($array["name"])?ucwords($array["name"]):"-";?>">&nbsp;</td>
					<td><input type="text" name="link" class="form-control" value="<?=($array["link"])?$array["link"]:"-";?>">&nbsp;</td>
					<td align="right"><input type="text" name="permit" class="form-control" value="<?=($array["permit"])?$array["permit"]:"-";?>" size="20">&nbsp;</td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="upd_nav">
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
			<?php } else { ?>
				<tr align="left">
					<td width="25" align="left">&nbsp;<?=$count?>.</td> 
					<td>&nbsp;#<?=($array["id"])?$array["id"]:"-";?>&nbsp;</td>
					<td>&nbsp;<?=($array["sort"])?$array["sort"]:"-";?>&nbsp;</td>
					<td>&nbsp;<?=($array["icon"])?$array["icon"]:"-";?>&nbsp;</td>
					<td>&nbsp;<?=($array["name"])?ucwords($array["name"]):"-";?>&nbsp;</td>
					<td>&nbsp;<?=($array["link"])?$array["link"]:"-";?>&nbsp;</td>
					<td align="right">&nbsp;<?=$array["permit"];?>&nbsp;</td>
					<td width="60" align="center">
						<a title="Edit Menu" class="btn btn-default" href="<?=$this_page?>&nid=<?=$array["id"]?>">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</td>
					<td width="60" align="center">
						<a title="Delete Menu" class="btn btn-default" href="<?=$this_page?>&did=<?=$array["id"]?>">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					</td>
				</tr>
<?php			} $count++;  
			}
		} else {?>
				<tr><td colspan="9" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
				<?php } ?>
		</tbody>
			</table>
				<?=$pagingResult->pagingMenu()?>
</div></div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php'; ?>