<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();	

$page_title	= "Warehouse";
$page_id 	= "4";
chkSecurity($page_id);

$level_list_q 	= "SELECT ul.id, ul.name 
		           FROM user_level ul 
		           WHERE ul.id > '".$_SESSION['level']."' AND ul.del = 0 AND ul.hidden = 0;";
$level_list_SQL	= @mysql_query($level_list_q) or die(mysql_error());

$members_list_q = "SELECT u.id, 
		                  CONCAT(u.fname,' ',u.lname) AS fullname, 
		                  u.email, 
		                  ul.name as level, 
		                  u.createDate as joindate, 
		                  u.active
				   FROM user u 
					 	LEFT JOIN user_level ul ON (ul.id = u.lid)
				   WHERE u.del = '0' AND u.hidden = '0'
				   ORDER BY u.fname ASC ";
$pagingResult = new Pagination();
$pagingResult->setPageQuery($members_list_q);
$pagingResult->paginate();

$this_page 	= $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();
$status 	= "";
$lastupd 	= date('Y-m-d H:i:s');

if(isset($_POST['add_user'])){
   	$password 	= trim(md5(DEFAULT_PASS));
	$salut 	= strtolower(trim($_POST['salut']));
	$fname 	= strtolower(trim($_POST['fname']));
	$lname 	= strtolower(trim($_POST['lname']));
	$email 	= strtolower(trim($_POST['email']));
	$level 	= strtolower(trim($_POST['level']));
	$uid	= $_SESSION['uid'];
	$date	= date('Y-m-d H:i:s');
	
   	if(!empty($salut) AND !empty($fname) AND !empty($lname) AND !empty($email) AND !empty($level)){
		$chk_email_q	= "SELECT u.email FROM user u WHERE u.email = '$email';";
		$chk_email_SQL 	= @mysql_query($chk_email_q) or die(mysql_error());
			if (mysql_num_rows($chk_email_SQL) >= 1) {
				$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						    	Email already registered on the system.
							</div>";
			}
			else {
				$add_user_q  = "INSERT INTO user (salut,fname,lname,email,password,lid,createBy,createDate,updBy,updDate,active,hidden)
							   	VALUES ('$salut','$fname','$lname','$email','$password','$level','$uid','$date','$uid','$date','1','0');";
				if(@mysql_query($add_user_q)) {	
					$status .= "<div class=\"alert alert-success alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						    		Account for <b>".$email."</b> has been succesfully created.
							    </div>";
					log_hist(6,$email);
				} else {
					$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						    	 Cannot create account for <b>".$email."</b>. Please check all available parameters.
							    </div>";
					log_hist(7,$email);
				}
			} 
	}	
	else {	
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					Cannot create an empty account. Please check all available parameters.
				    </div>";
		log_hist(7,$email);
	}
}
	
if (isset($_GET['did'])){
	$did = trim($_GET['did']);
	$uid = $_SESSION['uid'];
	$date = date('Y-m-d H:i:s');
	$del_user_q  = "UPDATE user SET updBy = '$uid', updDate = '$date', del = '1' , active = '0' WHERE id ='$did';";
	if(@mysql_query($del_user_q)) {
		log_hist(10,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					Cannot delete account. Please check all available parameters.
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
			<span>Add New User</span>
		</div>
	</a><br>
	<div id="form-member" class="collapse">
	<form class="well form" role="form" action="" method="POST">
		<div class="form-group">
			<label>Salutation</label>
			<select class="form-control" name="salut">
				<option>---------------------</option>
   				<option value="mr.">Mr.</option>
				<option value="mrs.">Mrs.</option>
				<option value="ms.">Ms.</option>
 			</select>
		</div>
		<div class="form-group">
			<label>First Name</label>
			<input name="fname" type="text" class="form-control">
		</div>
		<div class="form-group">
			<label>Last Name</label>
			<input name="lname" type="text" class="form-control">
		</div>
		<div class="form-group">
			<label>Email</label>
			<input name="email" type="text" class="form-control">
		</div>
		<div class="form-group">
			<label>Password</label>
			<br>Default password : <b>'<?=DEFAULT_PASS?>'</b>
		</div>
		<div class="form-group">
			<label>Level</label>
			<select class="form-control" name="level">
    			<option SELECTED>---------------------</option>
<?php 
  	while($level_list_array = mysql_fetch_array($level_list_SQL,MYSQL_ASSOC)){?>
    <option value="<?=$level_list_array["id"]?>"><?=ucwords($level_list_array["name"])?></option>
<? } ?></select>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary" name="add_user">Create New User</button>
		</div>
	</form> 
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			Registered Users On The System
		</div>
		<div class="panel-body">
        	<?=$pagingResult->pagingMenu();?>
        	<br>
        	<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-striped table-bordered table-condensed">	
				<thead>
            	<tr valign="middle"> 
                 	<th width="25">&nbsp;<b>NO.</b></td>
					<th width="*" align="left">&nbsp;<b>NAME</b></td>
                 	<th width="*" align="left">&nbsp;<b>EMAIL</b>&nbsp;</td>
            		<th width="*" align="left">&nbsp;<b>LEVEL</b>&nbsp;</td>
                 	<th width="*" align="left">&nbsp;<b>REG. DATE</b>&nbsp;</td>
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
			<td align="left">&nbsp;<?=($array["fullname"])?ucwords($array["fullname"]):"-";?>&nbsp;</td>
			<td align="left">&nbsp;<?=($array["email"])?$array["email"]:"-";?>&nbsp;</td>
			<td align="left">&nbsp;<?=ucwords($array["level"])?>&nbsp;</td>
			<td align="left">&nbsp;<?=cplday('d M Y',$array["joindate"])?>&nbsp;</td>
			<td width="60" align="center" valign="middle">
				<a title="View Details" class="btn btn-default" href="./member_det.php?id=<?=$array["id"]?>">
					<span class="glyphicon glyphicon-pencil"></span>
				</a>
			</td>
			<td width="60" align="center" valign="middle">
				<a title="Delete Member" class="btn btn-default" href="<?=$this_page?>&did=<?=$array["id"]?>">
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