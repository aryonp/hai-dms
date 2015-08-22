<?php
/* -----------------------------------------------------
 * File name	: login.php	
 * Created by 	: M. Aryo N. Pratama				
 * -----------------------------------------------------			
 * Purpose		: Do login using auth.class.php	
 * -----------------------------------------------------						                 			
 */

require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once CONT_PATH.'auth.class.php';
session_start();



$status = "";
if (isset($_POST['submit'])) {
	$login = new Auth;
	$login->setLogin($_POST['email'],$_POST['password']);
	$login->getLogin();
	
	if(count($login->_errors) > 0) {
		$err_msg ="<ul>";
		foreach($login->_errors as $errors) { 
			$err_msg .= "<li>$errors</li>"; 
		}
		$err_msg .="</ul>";
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\">
			        	<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
							<span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span>
						</button>
					".$err_msg."
					</div>";
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=PRODUCT?> <?=VERSION?> :: Login Page</title>
	</head>
<body>
<?=public_notif($notify,$notify_msg)?>
<noscript>
	<center>
		<div class="alert" align="center">
			You must activate your Javascript support on your browser<br>
			because some of facilities in this portal are using Javascript
		</div>
	</center>
</noscript>
	<div class="container">
			<div class="col-md-4">&nbsp;</div>
			<div class="col-md-4">
				<br><br><br><br>
				<?=$status?>
				<div class="well">
					<form role="form" action="" method="POST">
						<div class="form-group">
							<a href="./index.php">
								<div>
									<span class="glyphicon glyphicon-circle-arrow-left"></span>
									<span>Panel Utama</span>
								</div>
							</a>
						</div>
						<div class="form-group">
							<label name="email">Email</label>
							<input type="text" name="email" id="email" size="40" class="form-control">
						</div>
						<div class="form-group">
							<script language="JavaScript" type="text/javascript">if(document.getElementById) document.getElementById('email').focus();</script>
							<label name="password">Password</label>
							<input name="password" type="password" size="40" class="form-control">
						</div>
						<a href="./rst_pass.php">Lupa/Reset Password</a><br/><br/>
						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-primary btn-block">
								<span class="glyphicon glyphicon-log-in"></span>&nbsp;
								 Login
							</button>
						</div>
					</form>
				</div>
				<p class="text-center">						
					<?=PRODUCT?> &copy <?=date('Y') == BUILD_YEAR?BUILD_YEAR:BUILD_YEAR.' - '.date('Y');?>. All rights reserved
				</p>
				
			</div>
			<div class="col-md-4">&nbsp;</div>
	</div>

	<script src="<?=JS_PATH?>jquery-2.1.1.min.js"></script>
	<script src="<?=JS_PATH?>bootstrap.min.js"></script>
	<link rel="stylesheet" href="<?=CSS_PATH?>bootstrap.min.css">
	<link rel="stylesheet" href="<?=CSS_PATH?>custom.css">
</body>
</html>
