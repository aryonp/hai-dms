<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();	

$page_title	= "Bin Location";
$page_id 	= "4";
chkSecurity($page_id);

$wh_list_q 	= "SELECT w.id, w.name, w.city
		       FROM warehouse w
		       WHERE w.del = 0;";
$wh_list_SQL = @mysql_query($wh_list_q) or die(mysql_error());

$list_q = "SELECT b.id, 
		          b.name AS bname,
				  w.name AS wname,
				  w.address,
				  w.city,
				  w.zip,
				  w.country,
			 	  w.pic,
				  w.pemail,
				  w.pphone
			FROM binloc b LEFT JOIN warehouse w ON (w.id = b.wid)
			WHERE b.del = '0'
			ORDER BY w.name ASC, b.name ASC ";
$pagingResult = new Pagination();
$pagingResult->setPageQuery($list_q);
$pagingResult->paginate();

$this_page 	= $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();
$status 	= "";
$lastupd 	= date('Y-m-d H:i:s');

if(isset($_POST['add_binloc'])){
	$name 	= strtolower(trim($_POST['name']));
	$wid 	= trim($_POST['whid']);
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	
   	if(!empty($name) AND !empty($aisle) AND !empty($address) AND !empty($city) AND !empty($pic) AND !empty($pemail) AND !empty($pphone)){
		$add_q  = "INSERT INTO warehouse (name,aisle,address,city,zip,country,pic,pemail,pphone,createID,createDate,updID,updDate)
				   VALUES ('$name','$aisle','$address','$city','$zip','$country','$pic','$pemail','$pphone','$uid','$date','$uid','$date');";
				if(mysql_query($add_q) or die(mysql_error())) {	
					$status .= "<div class=\"alert alert-success alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						    		Bin Location <b>".$name."</b> has been succesfully created.
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
					Cannot create an empty bin location. Please check all available parameters.
				    </div>";
		log_hist(7,$name);
	}
}
	
if (isset($_GET['did'])){
	$did = trim($_GET['did']);
	$uid = $_SESSION['uid'];
	$date = date('Y-m-d H:i:s');
	$del_q  = "UPDATE binloc SET updID = '$uid', updDate = '$date', del = '1' WHERE id ='$did';";
	if(@mysql_query($del_q)) {
		log_hist(10,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					Cannot delete bin location. Please check all available parameters.
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
			<span>Add New Bin Location</span>
		</div>
	</a><br>
	<div id="form-member" class="collapse">
	<form class="well form" role="form" action="" method="POST">
		<div class="form-group">
			<label>Name</label>
			<input name="name" type="text" class="form-control">
		</div>
		<div class="form-group">
			<label>Warehouse</label>
		<select class="form-control" name="whid">
    			<option SELECTED>---------------------</option>
<?php 
  	while($wh_list_array = mysql_fetch_array($wh_list_SQL,MYSQL_ASSOC)){?>
    <option value="<?=$wh_list_array["id"]?>"><?=ucwords($wh_list_array["name"])?> - <?=ucwords($wh_list_array["city"])?></option>
<? } ?></select>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary" name="add_binloc">Create New Bin Location</button>
		</div>
	</form> 
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			Registered Bin Location On The System
		</div>
		<div class="panel-body">
        	<?=$pagingResult->pagingMenu();?>
        	<br>
        	<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-striped table-bordered table-condensed">	
				<thead>
            	<tr valign="middle"> 
                 	<th width="25">&nbsp;<b>NO.</b></td>
					<th width="*" align="left">&nbsp;<b>BIN LOCATION</b></td>
					<th width="*" align="left">&nbsp;<b>WAREHOUSE</b>&nbsp;</td>
					<th width="*" align="left">&nbsp;<b>ADDRESS</b>&nbsp;</td>
					<th width="*" align="left">&nbsp;<b>CITY</b>&nbsp;</td>
                 	<th width="*" align="center" colspan="2">&nbsp;<b>CMD</b></td>
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
			<td align="left">&nbsp;<?=($array["aisle"])?$array["aisle"]:"-";?>&nbsp;</td>
			<td align="left">&nbsp;<?=($array["address"])?$array["address"]:"-";?>&nbsp;</td>
			<td align="left">&nbsp;<?=($array["city"])?ucwords($array["city"]):"-";?>&nbsp;</td>
			<td align="left">&nbsp;<?=($array["pic"])?ucwords($array["pic"]):"-";?>&nbsp;</td>
			<td align="left">&nbsp;<?=($array["pemail"])?$array["pemail"]:"-";?>&nbsp;</td>
			<td align="left">&nbsp;<?=($array["pphone"])?$array["pphone"]:"-";?>&nbsp;</td>
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
		<tr><td colspan="7" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
				<?php } ?></tbody>
			</table>
				<?=$pagingResult->pagingMenu();?>
				<br>
				</div>
	</div>
</div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php'; ?>