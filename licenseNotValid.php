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

/* Start Script */

$page_title = "Illegal Access";
$page_id	= "2";
chkSecurity($page_id);

/* End Script */

include THEME_DEFAULT.'header.php'; ?>
<//-----------------CONTENT-START-------------------------------------------------//>
<div class="container">
	<div class="row">
		<div class="col-md-2">&nbsp;</div>
		<div class="col-md-6">
			<?=back_button();?>
			<div class="alert alert-warning" role="alert">
				<h2>INVALID LICENSE KEY OR NO LICENSE KEY INSTALLED</h2><br />
				Please contact your Administrator for this problem.<br />
			</div>
		</div>
		<div class="col-md-2">&nbsp;</div>
	</div>
</div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php';?>