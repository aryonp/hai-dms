<?php
/* -----------------------------------------------------
 * File name	: help.php								
 * Created by 	: M. Aryo N. Pratama		
 * -----------------------------------------------------				            
 * Purpose		: Manage help data.											                 			
 * -----------------------------------------------------
 */

require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();

$page_title = "Help";
$page_id	= "15";
chkSecurity($page_id);

$query ="SELECT h.id, 
		        CONCAT(u.fname,' ',u.lname) as creator, 
		        h.title, 
		        h.location, 
		        ul.name as level, 
		        CONCAT(upd.fname, ' ',upd.lname) as updater, 
		        h.updDate 
		 FROM help h
			LEFT JOIN user u ON (u.id = h.createBy) 
			LEFT JOIN user upd ON (upd.id = h.updBy) 
			LEFT JOIN user_level ul ON (ul.id = h.lid) 
		 WHERE h.del = '0' ";
$pagingResult = new Pagination();
$pagingResult->setPageQuery($query);
$pagingResult->paginate();
$this_page = $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();

$status = "";

$level_list_q	 	= "SELECT ul.id, ul.name 
		               FROM user_level ul
		               WHERE ul.del = '0' ORDER BY ul.id ASC ;";
$level_list_SQL 	= @mysql_query($level_list_q) or die(mysql_error());
$level_list_SQL_2 	= @mysql_query($level_list_q) or die(mysql_error());

if (isset($_POST['add_help'])){
	$title 	= trim($_POST['help_title']);
	$location 	= file_target("help",$_FILES['help_file']['name']);
	$level 	= trim($_POST['help_level']);
	$uid 	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	
	if ($level != "-" AND !empty($title)) {		
		if(move_uploaded_file($_FILES['help_file']['tmp_name'], $location)) {
			$add_help_q  = "INSERT INTO help (lid,title,location,createBy,createDate,updBy,updDate) 
			                VALUES ('$level','$title','$location','$uid','$date','$uid','$date');"; 
			if(@mysql_query($add_help_q)) {
				log_hist(25,$title);
				header("location:$this_page");
			} else {
				$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						    	Failed to add new help. Please check all available parameters.
				    		</div>";
				log_hist(26,$title);
			}
		}
		else { 
			$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
							Failed to upload new help file. Please check all available parameters.
				    	</div>";
			log_hist(26,$title);
		}
	}	else { 
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Missing required infromation. Please check all available parameters.
				    </div>";
		log_hist(26,$title);
	}
	
}

if (isset($_POST['upd_help'])){
	$nid 	= trim($_POST['nid']);
	$level 	= trim($_POST['level_new']);
	$title	= trim($_POST['title_new']);
	$uid 	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	$upd_help_q = "UPDATE help 
				   SET title = '$title', lid = '$level', updBy ='$uid', updDate = '$date'
				   WHERE id ='".$nid."';";
	if(@mysql_query($upd_help_q)) {
		log_hist(27,$nid);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to update the help information. Please check all available parameters.
				    </div>";
		log_hist(28,$nid);
	}
}

if (isset($_GET['did'])){
	$did = trim($_GET['did']);
	$uid 	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	$del_help_q = "UPDATE help SET updBy ='$uid', updDate = '$date', del = '1' WHERE id ='$did' AND del = '0';";
	if(@mysql_query($del_help_q)) {
		log_hist(29,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to delete the help information. Please check all available parameters.
				    </div>";
		log_hist(30,$did);
	}
}

if(isset($_POST['cancel'])){
	header("location:$this_page"); 	
}

include THEME_DEFAULT.'header.php'; ?>
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
<?=$status?>
	<a href="#" data-toggle="collapse" data-target="#form-help">
		<div>
			<span>Add New Help</span>
		</div>
	</a><br>	
		<div id="form-help" class="collapse">
		<form class="well form" method="POST" enctype="multipart/form-data" action="<?=$this_page?>">
			<div class="form-group">
				<label>Title</label>
				<input type="text" name="help_title" class="form-control">
			</div>
			<div class="form-group">
				<label>Location</label>
				<input type="file" size="30" name="help_file">&nbsp;&nbsp;(Max: <?=ini_get('post_max_size');?>)
			</div>
			<div class="form-group">
				<label>Level</label>
				<select name="help_level" class="form-control">
    				<option value="-">---------------------</option>
<?php while($level_list_array = mysql_fetch_array($level_list_SQL, MYSQL_ASSOC)){?>
    	<option value="<?=$level_list_array["id"]?>"><?=ucwords($level_list_array["name"])?></option>
<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<button type="submit" name="add_help" class="btn btn-primary">Create New Help</button>
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
			<form class="form" method="POST" action="<?=$this_page?>">
			<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-striped table-bordered table-condensed">	
			<thead>
				<tr><td width="25" align="left">&nbsp;<b>NO</b>&nbsp;</td> 
                 	<td width="*" align="left">&nbsp;<b>ID</b>&nbsp;</td>
                 	<td width="40" align="left">&nbsp;<b>TITLE</b>&nbsp;</td>
                 	<td width="*" align="left">&nbsp;<b>LEVEL</b>&nbsp;</td>
                 	<td width="*" align="left">&nbsp;<b>SIZE</b>&nbsp;</td>
                 	<td width="*" align="left">&nbsp;<b>LAST UPD.</b>&nbsp;</td>
                 	<td colspan="3" align="center">&nbsp;<b>CMD</b>&nbsp;</td>
				</tr>
			</thead>
			<tbody>
<?php 
   if ($pagingResult->getPageRows()>= 1) {	
			$count = $pagingResult->getPageOffset() + 1;
			$result = $pagingResult->getPageArray();
			while ($help_list_array = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if (isset($_GET['nid']) && $_GET['nid'] == $help_list_array["id"]) {?>
				<input type="hidden" name="nid" value="<?=$help_list_array["id"]?>">
				<tr bgcolor="#ffcc99" align="left" valign="top">
					<td width="25" align="left">&nbsp;<?=$count?>.&nbsp;</td> 
					<td>&nbsp;#<?=($help_list_array["id"])?$help_list_array["id"]:"-";?>&nbsp;</td>
					<td><input type="text" class="form-control" name="title_new" value="<?=($help_list_array["title"])?trim(ucwords($help_list_array["title"])):"-";?>"></td>
					<td>
					<select name="level_new">
    				<option value="-">---------------------</option>
<?php 		while($level_list_array_2 = mysql_fetch_array($level_list_SQL_2, MYSQL_ASSOC)){
  				$compare_level = ($level_list_array_2["name"] == $help_list_array["level"])?"SELECTED":"";?>
    			<option value="<?=$level_list_array_2["id"]?>" <?=$compare_level?>><?=ucwords($level_list_array_2["name"])?></option>
<?php 			} ?>
					</select>
					</td>
					<td>&nbsp;<?=($help_list_array["location"])?filesize($help_list_array["location"]):"-";?>&nbsp;</td>
					<td>&nbsp;<?=($help_list_array["updDate"] != "0000-00-00 00:00:00")?cplday('d M Y H:i:s',$help_list_array["updDate"]):"-";?>&nbsp;</td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="upd_help">UPDATE
							<span class="glyphicon glyphicon-floppy-saved"></span>
						</button>
					</td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="cancel">
							<span class="glyphicon glyphicon-floppy-remove"></span>
						</button>
					</td>
				</tr>
			<?php } else { ?>
				<tr align="left">
					<td width="25" align="left">&nbsp;<?=$count?>.</td> 
					<td>&nbsp;#<?=($help_list_array["id"])?$help_list_array["id"]:"-";?>&nbsp;</td>
					<td>&nbsp;<?=($help_list_array["title"])?ucwords($help_list_array["title"]):"-";?>&nbsp;</td>
					<td>&nbsp;<?=($help_list_array["level"])?ucwords($help_list_array["level"]):"-";?>&nbsp;</td>
					<td>&nbsp;<?=($help_list_array["location"])?filesize($help_list_array["location"]):"-";?>&nbsp;</td>
					<td>&nbsp;<?=($help_list_array["updDate"] != "0000-00-00 00:00:00")?cplday('d M Y H:i:s',$help_list_array["updDate"]):"-";?>&nbsp;</td>
					<td width="60" align="center">
						<a title="View" class="btn btn-default" href="<?=$help_list_array["location"]?>">
							<span class="glyphicon glyphicon-download-alt"></span>
						</a>
					</td>
					<td width="60" align="center">
						<a title="Edit" class="btn btn-default" href="<?=$this_page?>&nid=<?=$help_list_array["id"]?>">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</td>
					<td width="60" align="center">
						<a title="Delete" class="btn btn-default" href="<?=$this_page?>&did=<?=$help_list_array["id"]?>">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					</td>
				</tr>
			<?php	
				} $count++;  
			}
		} else {?>
				<tr><td colspan="9" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
				<?php } ?></tbody>
		</table>
		</form>
		<?=$pagingResult->pagingMenu()?>
		</div>
				
</div>
<//-----------------CONTENT-END-------------------------------------------------//>

<?php include THEME_DEFAULT.'footer.php'; ?>
