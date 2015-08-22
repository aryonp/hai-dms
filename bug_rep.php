<?php

/* -----------------------------------------------------
 * File name	: config.inc.php								
 * Created by 	: aryonp@gmail.com		
 * -----------------------------------------------------				            
 * Purpose		: Change individual settings										                 			
 * -----------------------------------------------------
 */

require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
chkLicense();
chkSession();

$page_title 	= "Bug Report";
$status			= "";

if (isset($_POST['send_bug_report']) && isset($_POST['bug_sub']) && isset($_POST['bug_msg'])) {
	$email		= "support@trinqle.com";
	$headers 	= "From: \"QMS Notification\" <no-reply@trinqle.com>\r\nX-Mailer:".PRODUCT." ".VERSION."/MIME-Version: 1.0\n";
	$subject	= trim($_POST['bug_sub']);
	$message 	= trim($_POST['bug_msg']);
	if(mail($email, $subject, $message, $headers)){
		$status .= "<div class=\"alert alert-info alert-dismissable\" role=\"alert\">
				    	<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
							<span aria-hidden=\"true\">&times;</span>
							<span class=\"sr-only\">Close</span>
						</button>
						Your report has been sent to support@trinqle.com
					</div>";
	}
	else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\">
						<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
							<span aria-hidden=\"true\">&times;</span>
							<span class=\"sr-only\">Close</span>
						</button>
						Failed to sent report or your network policy doesnt support external mail function
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
	<form class="well form" role="form" action="" method="POST">
		<div class="form-group">
			<label>Subject</label>
			<input type="text" name="bug_sub" class="form-control">
		</div>
		<div class="form-group">
			<label>Message</label>
			<textarea class="form-control" name="bug_msg" rows="7"></textarea>
		</div>
		<div class="form-group">
			<button type="submit" name="send_bug_report" class="btn btn-primary btn-block">
				<span class="glyphicon glyphicon-send"></span>&nbsp;
				Send My Report
			</button>
		</div>
	</form>
</div>
<//-----------------CONTENT-END---------------------------------------------------//>          					
<?php include THEME_DEFAULT.'footer.php';?>