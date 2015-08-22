<?php
/* -----------------------------------------------------
 * File name	: rpsess.php
 * Created by 	: M. Aryo N. Pratama
 * Created on	: 21.11.2014				
 * -----------------------------------------------------			
 * Purpose		: Do login using auth.class.php	
 * -----------------------------------------------------						                 			
 */

require_once 'init.php';
require_once CONT_PATH.'rpass.class.php';
require_once LIB_PATH.'functions.lib.php';
$status = "";

if(isset($_GET['remail']) && isset($_GET['rkey'])) {
	$reset1 = new resetPass;
	$reset1->set_email($_GET['remail']);
	$reset1->set_rkey($_GET['rkey']);
	$reset1->ver_reset_session();
	
	if(count($reset1->_errors) > 0) {
		$err_msg1 ="<ul>";
		foreach($reset1->_errors as $errors1) {
			$err_msg1 .= "<li>$errors1</li>";
		}
		$err_msg1 .="</ul>";
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>$err_msg1</div>";
	}
}

if (isset($_POST['upd_pass']) && isset($_POST['h_email']) && isset($_POST['h_rkey']) && isset($_POST["new_pwd1"]) & isset($_POST["new_pwd2"])) {
	$reset2 = new resetPass;
	$reset2->set_email($_POST['h_email']);
	$reset2->set_rkey($_POST['h_rkey']);
	$reset2->set_new_pass_1($_POST['new_pwd1']);
	$reset2->set_new_pass_2($_POST['new_pwd2']);
	$reset2->change_pass_w_session();
	
	if(count($reset2->_errors) > 0) {
		$err_msg2 ="<ul>";
		foreach($reset2->_errors as $errors2) {
			$err_msg2 .= "<li>$errors2</li>";
		}
		$err_msg2 .="</ul>";
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>$err_msg2</div>";
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Reset Password Session Page</title>
	</head>
<body>
<noscript>
	<center>
		<div class="alert" align="center">
			You must activate your Javascript support on your browser<br>
			because some of facilities in this portal are using Javascript
		</div>
	</center>
</noscript>
	<div class="container">
			<div class="col-xs-4"></div>
			<div class="col-xs-4">
				<br>
				<?=$status?>
				<a href="./login.php">
					<div>
						<span class="glyphicon glyphicon-circle-arrow-left"></span>
						<span>Login Page</span>
					</div>
				</a>
				<br>
<?php if(isset($_GET['remail']) && isset($_GET['rkey']) && $reset1->ver_reset_session()) { ?>
							<form class="form well" role="form" action="<?=$_SERVER['PHP_SELF']?>?remail=<?=$_GET['remail']?>&rkey=<?=$_GET['rkey']?>" method="POST">
								<div class="form-group">
									<label>New Password</label>
									<input type="hidden" name="h_email" value="<?=$_GET['remail']?>" />
									<input type="hidden" name="h_rkey" value="<?=$_GET['rkey']?>" />
									<input name="new_pwd1" type="password" class="form-control">
								</div>
								<div class="form-group">
									<label>Re-type New Password</label>
									<input name="new_pwd2" type="password" class="form-control">
								</div>
								<div class="form-group">
									<button type="submit" name="upd_pass" class="btn btn-primary btn-block">Update My Password</button>
								</div>
							</form>

<?php } ?>	</div>
			<div class="col-xs-4"></div>
	</div>
<script src="<?=JS_PATH?>jquery-2.1.1.min.js"></script>
<script src="<?=JS_PATH?>bootstrap.min.js"></script>	
<link rel="stylesheet" href="<?=CSS_PATH?>bootstrap.min.css">
<link rel="stylesheet" href="<?=CSS_PATH?>custom.css">
</body>
</html>
