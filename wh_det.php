<?php
require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
chkLicense();
chkSession();

$page_title 	= "Warehouse Details";
$page_id	 	= "4";
chkSecurity($page_id);

$mid 		= ((isset ($_GET['id']) && $_GET['id'] != '')?trim($_GET['id']):'');
$old_user_q ="SELECT u.id, 
				     u.salut, 
					 u.fname, 
					 u.lname, 
					 u.email, 
					 u.lid AS level, 
					 u.createDate AS cdate, 
					 u.active,
					 u.del 
				  FROM user u 
				  WHERE u.id ='$mid' AND u.del ='0';";
$old_user_SQL 	= @mysql_query($old_user_q) or die(mysql_error());
$old_user_array = mysql_fetch_array($old_user_SQL,MYSQL_ASSOC);

$level_list_q 	= "SELECT ul.id, ul.name 
				   FROM user_level ul 
				   WHERE ul.del = '0' AND ul.hidden = '0' ;";
$level_list_SQL = @mysql_query($level_list_q) or die(mysql_error());

$this_page 	= $_SERVER['PHP_SELF']."?id=".$mid;
$salut 		= array("mr."=>"Mr.","mrs."=>"Mrs.","ms."=>"Ms.");
$act_det 	= array("0"=>"disabled","1"=>"active");

$status="&nbsp;";

$lastupd = date('Y-m-d H:i:s');

if (isset($_POST['upd_user'])){
	$upd_id = $_POST['upd_id'];
	$salut 	= strtolower(trim($_POST['salut']));
	$fname 	= strtolower(trim($_POST['fname']));
	$lname 	= strtolower(trim($_POST['lname']));
	$level 	= strtolower(trim($_POST['level']));
	$active = trim($_POST['active']);
	$uid 	= $_SESSION['uid'];
	$date 	= date('Y-m-d H:i:s');
	$upd_user_q ="UPDATE user 
	              SET salut ='$salut', fname = '$fname', lname = '$lname', lid ='$level', active ='$active', updBy = '$uid', updDate = '$date' 
	              WHERE id ='$upd_id';";
	if(@mysql_query($upd_user_q)) {
		log_hist(8,$old_user_array["email"]);
		header("location:$this_page");
	} else {
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					Cannot update account. Please check all available parameters.
				    </div>";
		log_hist(9,$old_user_array["email"]);
	}

}

elseif(isset($_POST['reset_pass'])) {
		$email	= $old_user_array["email"];
		$upd_id = $_POST['upd_id'];
		$pass_reset = trim(md5(DEFAULT_PASS));
		$uid 	= $_SESSION['uid'];
		$date 	= date('Y-m-d H:i:s');
		$reset_pass_q = "UPDATE user SET password = '$pass_reset', updBy = '$uid', updDate = '$date' WHERE id = '$upd_id';";
		
		if(@mysql_query($reset_pass_q)) {
			log_hist(12,$email);
			$status .= "<div class=\"alert alert-success alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					    Password for this account has been resetted to default password of the system.
				        </div>";
		}
		else {
			$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
							Cannot reset password for this account. Please check all available parameters.
				    	</div>";
		}
}

else {
	$status="&nbsp;";
}
	
include THEME_DEFAULT.'header.php'; ?>

<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
	<div class="col-md-6">
	<?=$status?>
		<a href="./members.php">
		<div>
			<span class="glyphicon glyphicon-circle-arrow-left"></span>
			<span>Members Page</span>
		</div>
		</a>
		<br>	
<?php if($_SESSION['level'] <= $old_user_array["level"] AND $old_user_array["del"] == 0) {?>
		<form method="POST" action="<?=$this_page?>" class="well form">
    		<input type="hidden" name="upd_id" value="<?=$mid?>">
			<div class="form-group">
				<label>SALUTATION</label>
				<select class="form-control" name="salut">
					<option value="-">--------</option>
<?php foreach($salut as $key => $name) {
		$compare_salut = ($key == $old_user_array["salut"])?"SELECTED":"";?>
				<option value ="<?=$key?>" <?=$compare_salut?>><?=$name?></option>
<?php	} ?>
 			    </select>
 			</div>
			<div class="form-group">
				<label>FIRST NAME</label>
				<input type="text" class="form-control" name="fname" value="<?=ucwords($old_user_array["fname"])?>">
			</div>
			<div class="form-group">
				<label>LAST NAME</label>
				<input type="text" class="form-control" name="lname" value="<?=ucwords($old_user_array["lname"])?>">
			</div>
			<div class="form-group">
				<label>EMAIL</label>
				<br>
				<?=($old_user_array["email"])?$old_user_array["email"]:"-"?>
			</div>
			<div class="form-group">
				<label>PASSWORD</label>
				<br>
				<?=($_SESSION['level'] > $old_user_array["level"])?"":"<button type=\"submit\" class=\"btn btn-warning\" name=\"reset_pass\">Reset User Password</button>&nbsp;&nbsp;";?>(Default password : <b>'<?=DEFAULT_PASS?>'</b>)
			</div>
			<div class="form-group">
				<label>LEVEL</label>
<?php if ($_SESSION['level'] > $old_user_array["level"]){?><?=$old_user_array["level"]?><input type="hidden" name="level" value="<?=$old_user_array[11]?>" /><?php } else {?>
				<select class="form-control" name="level">
    				<option value="-">---------------------</option>
<?php 
  	while($level_list_array = mysql_fetch_array($level_list_SQL)){
  	$compare_level = ($level_list_array["id"] == $old_user_array["level"])?"SELECTED":"";?>
    <option value="<?=$level_list_array["id"]?>" <?=$compare_level?>><?=ucwords($level_list_array["name"])?></option>
<? } }?>
				</select>
			</div>
			<div class="form-group">
				<label>ACCOUNT STATUS</label>
<?php if ($_SESSION['level'] > $old_user_array["level"]){?><?=($old_user_array["active"] == '1')?"active":"disabled"?><input type="hidden" name="active" value="<?=$old_user_array["actives"]?>" /><?php } else {?>
			<select class="form-control" name="active">
<?php foreach($act_det as $act_key => $act_status) {
		$compare_act = ($act_key == $old_user_array["active"])?"SELECTED":"";?>
				<option value ="<?=$act_key?>" <?=$compare_act?>><?=ucwords($act_status)?></option>
<?php	} ?>
 			    </select>
<?php } ?>
 			</div>   
 			<div class="form-group">
				<label>JOIN DATE</label>
				<br>
				<?=cplday('d F Y',$old_user_array["cdate"])?>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary" name="upd_user">Update User</button>
			</div>
		</form>
<?php 
} else {
	deny_perm();
	log_hist(4, " TO USER ".$old_user_array["email"]);
}
?>
</div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php'; ?>