<?php
/* -----------------------------------------------------
 * File name  : index.php	
 * Created by : aryonp@gmail.com
 * -----------------------------------------------------						
 * Purpose	  : First page after login. Display MOTD, 
 * summary of system statistics, and 7 last transaction 
 * of the user.					                 			
 * -----------------------------------------------------
 */

require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
chkLicense();
chkSession();

$page_title = "Home";
$page_id 	= "1";
chkSecurity($page_id);

include THEME_DEFAULT.'header.php';?>
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
<?=disp_motd();?>

<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php';?>
