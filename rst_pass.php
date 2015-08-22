<?php
/* -----------------------------------------------------
 * File name	: login.php	
 * Created by 	: M. Aryo N. Pratama				
 * -----------------------------------------------------			
 * Purpose		: Do login using auth.class.php	
 * -----------------------------------------------------						                 			
 */

require_once 'init.php';
require_once CONT_PATH.'rpass.class.php';
require_once LIB_PATH.'functions.lib.php';

$status ="";

if (isset($_POST['rst-button']) && isset($_POST['email'])) {
	$reset = new resetPass;
	$reset->set_email($_POST['email']);
	$reset->set_rkey($reset->gen_rkey(32));
	$reset->gen_reset_session();
	
	if(count($reset->_errors) > 0) {
		$err_msg ="<ul>";
		foreach($reset->_errors as $errors) {
			$err_msg .= "<li>$errors</li>";
		}
		$err_msg .="</ul>";
		$status .= "<div class=\"alert alert-warning alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>$err_msg</div>";
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Reset Request Page</title>
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
			<div class="col-xs-4">&nbsp;</div>
			<div class="col-xs-4">
				<br><br><br><br>
				<?=$status?>
				<div class="well">
					<div class="container-fluid">
						<div class="row-fluid">
							<form class="form" role="form" action="" method="POST">
								<div class="form-group">
									<a href="./login.php">
										<div>
											<span class="glyphicon glyphicon-circle-arrow-left"></span>
											<span>Login Page</span>
										</div>
									</a>
								</div>
								<div class="form-group">
									<label>Provide Your Email Below</label>
									<input type="text" name="email" class="form-control">
								</div>
								<div class="form-group">
						  			<button type="submit" name="rst-button" class="btn btn-primary btn-block">
						  				<span class="glyphicon glyphicon-repeat"></span>&nbsp;
						  				Reset My Password
						  			</button>
						 		</div>
							</form>
						</div>
					</div>
     	 		</div>
			</div>
			<div class="col-xs-4">&nbsp;</div>
	</div>
<script src="<?=JS_PATH?>jquery-2.1.1.min.js"></script>
<script src="<?=JS_PATH?>bootstrap.min.js"></script>	
<link rel="stylesheet" href="<?=CSS_PATH?>bootstrap.min.css">
<link rel="stylesheet" href="<?=CSS_PATH?>custom.css">
</body>
</html>
