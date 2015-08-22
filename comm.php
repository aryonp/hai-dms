<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();	

$page_title	= "Commodity";
$page_id 	= "4";
chkSecurity($page_id);

$list_q = "SELECT c.id, 
		          c.name,
				  c.satuan,
				  c.expiry
			FROM commodity c
			WHERE c.del = '0'
			ORDER BY c.name ASC ";
$pagingResult = new Pagination();
$pagingResult->setPageQuery($list_q);
$pagingResult->paginate();

$this_page 	= $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();
$status 	= "";
$lastupd 	= date('Y-m-d H:i:s');

if(isset($_POST['add_comm'])){
	$name 	= strtolower(trim($_POST['name']));
	$satuan	= strtolower(trim($_POST['satuan']));
	$kadaluarsa = trim($_POST['kadaluarsa']);
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	
   	if(!empty($name) AND !empty($satuan) AND !empty($kadaluarsa)){
		$add_q  = "INSERT INTO commodity (name,satuan,expiry,createID,createDate,updID,updDate)
				   VALUES ('$name','$satuan','$kadaluarsa','$uid','$date','$uid','$date');";
				if(mysql_query($add_q) or die(mysql_error())) {	
					$status .= "<div class=\"alert alert-success alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						    		Commodity <b>".ucwords($name)."</b> has been succesfully created.
							    </div>";
					log_hist(6,$name);
				}
				/* else {
					$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						    	 Cannot create warehouse <b>".$name."</b>. Please check all available parameters.
							    </div>";
					log_hist(7,$name);
				} */
	}	
	else {	
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					Cannot create an empty commodity. Please check all available parameters.
				    </div>";
		log_hist(7,$name);
	}
}
	
if (isset($_GET['did'])){
	$did = trim($_GET['did']);
	$uid = $_SESSION['uid'];
	$date = date('Y-m-d H:i:s');
	$del_q  = "UPDATE commodity SET updID = '$uid', updDate = '$date', del = '1' WHERE id ='$did';";
	if(@mysql_query($del_q)) {
		log_hist(10,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					Cannot delete commodity. Please check all available parameters.
				    </div>";
		log_hist(11,$did);
	}
}

include THEME_DEFAULT.'header.php'; ?>             			
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
<?=$status?>
<div class="sub-header">
	<a href="#" data-toggle="collapse" data-target="#form-member">
		<div>
			<span>Add New Commodity</span>
		</div>
	</a><br>
	<div id="form-member" class="collapse">
	<form class="well form" role="form" action="" method="POST">
		<div class="form-group">
			<label>Name</label>
			<input name="name" type="text" class="form-control">
		</div>
		<div class="form-group">
			<label>Satuan</label>
			<input name="satuan" type="text" class="form-control" placeholder="Kg/Litre/Piece/etc">
		</div>
		<div class="form-group">
			<label>Kadaluarsa</label>
			<input name="kadaluarsa" type="text" class="form-control" placeholder="Please input in days">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary" name="add_comm">Create New Commodity</button>
		</div>
	</form> 
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			Registered Commodity On The System
		</div>
		<div class="panel-body">
        	<?=$pagingResult->pagingMenu();?>
        	<br>
        	<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-striped table-bordered table-condensed">	
				<thead>
            	<tr valign="middle"> 
                 	<th width="25">&nbsp;<b>NO.</b></th>
					<th width="*" align="left">&nbsp;<b>NAME</b></th>
					<th width="*" align="left">&nbsp;<b>UNIT</b>&nbsp;</th>
					<th width="*" align="left">&nbsp;<b>EXPIRY TIME</b>&nbsp;</th>
                 	<th width="*" align="center" colspan="2">&nbsp;<b>CMD</b></th>
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
			<td align="left">&nbsp;<?=($array["name"])?ucwords($array["name"]):"-";?>&nbsp;</td>
			<td align="left">&nbsp;<?=($array["satuan"])?$array["satuan"]:"-";?>&nbsp;</td>
			<td align="left">&nbsp;<?=($array["expiry"])?$array["expiry"]:"-";?>&nbsp;hari&nbsp;</td>
			<td width="60" align="center" valign="middle">
				<a title="View Details" class="btn btn-default" href="./wh_det.php?id=<?=$array["id"]?>">
					<span class="glyphicon glyphicon-pencil"></span>
				</a>
			</td>
			<td width="60" align="center" valign="middle">
				<a title="Delete" class="btn btn-default" href="<?=$this_page?>&did=<?=$array["id"]?>">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</td>
		</tr>

<?php	$count++;  
		}
	} else {?>
		<tr><td colspan="6" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
				<?php } ?></tbody>
			</table>
				<?=$pagingResult->pagingMenu();?>
				<br>
				</div>
	</div>
</div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php'; ?>