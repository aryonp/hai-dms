<?php

/* -----------------------------------------------------
 * File name	: config.inc.php								
 * Created by 	: aryonp@gmail.com		
 * -----------------------------------------------------				            
 * Purpose		: Change individual settings										                 			
 * -----------------------------------------------------
 */

require_once 'init.php';
require_once CONT_PATH.'rpass.class.php';
require_once LIB_PATH.'functions.lib.php';
chkLicense();
chkSession();

$page_title = "Settings";
$status 	= "";

if (isset($_POST['upd_pass']) && isset($_POST['new_pwd1']) && isset($_POST['new_pwd2'])) {
	$reset = new resetPass;
	$reset->set_email($_SESSION['email']);
	$reset->set_new_pass_1($_POST['new_pwd1']);
	$reset->set_new_pass_2($_POST['new_pwd2']);
	$reset->change_pass_wo_session();
	if(count($reset->_errors) > 0) {
		$err_msg ="<ul>\n";
		foreach($reset->_errors as $errors) {
			$err_msg .= "<li>$errors</li>\n";
		}
		$err_msg .="</ul>\n";
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\">
						<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
							<span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span>
						</button>
						$err_msg
					</div>";
	}
}

include THEME_DEFAULT.'header.php';?>
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
<div class="col-xs-6">
	<?=$status?>
	<a href="./home.php">
		<div>
			<span class="glyphicon glyphicon-circle-arrow-left"></span>
			<span>Home Page</span>
		</div>
	</a>
	<br>	
	<form class="form well" role="form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
		<div class="form-group">
			<label>New Password</label>
			<input name="new_pwd1" type="password" class="form-control">
		</div>
		<div class="form-group">
			<label>Re-type New Password</label>
			<input name="new_pwd2" type="password" class="form-control">
		</div>
		<div class="form-group">
			<button type="submit" name="upd_pass" class="btn btn-primary btn-block">
			<span class="glyphicon glyphicon-refresh"></span>&nbsp;
			Update My Password</button>
		</div>
	</form>
</div>
<//-----------------CONTENT-END---------------------------------------------------//>          					
<?php include THEME_DEFAULT.'footer.php';?>