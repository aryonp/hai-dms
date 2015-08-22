<?php
/* -----------------------------------------------------
 * File name	: illegal.php								
 * Created by 	: M. Aryo N. Pratama		
 * -----------------------------------------------------				            
 * Purpose		: Warning message for illegal access											                 			
 * -----------------------------------------------------
 */

require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
chkSession();

$page_title = "Illegal Access";
$page_id	= "1";
chkSecurity($page_id);

include THEME_DEFAULT.'header.php'; ?>
<//-----------------CONTENT-START-------------------------------------------------//>
<div class="container">
	<div class="row">
		<div class="col-md-2">&nbsp;</div>
		<div class="col-md-6">
			<?=back_button();?>
			<div class="alert alert-danger" role="alert">
				<h2>ILLEGAL ACCESS !!!</h2>
				Your Username and IP Address has been recorded for auditing.<br />
				Please navigate only to the page that allowed for your user level.<br />
			</div>
		</div>
		<div class="col-md-2">&nbsp;</div>
	</div>
</div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php';?>