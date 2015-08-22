<?php
/* -----------------------------------------------------
 * File name	: sitemap.php								
 * Created by 	: M. Aryo N. Pratama		
 * -----------------------------------------------------				            
 * Purpose		: Generate sitemap according to group access
 * in navigation table and sort it in alphabetic order.											                 			
 * -----------------------------------------------------
 */

require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
chkLicense();
chkSession();

$page_title="Sitemap";

include THEME_DEFAULT.'header.php';?>
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
<?php
  $smap_q ="SELECT n.id, n.sort, n.name, n.link, n.permit FROM navigation n WHERE del = '0' ORDER BY n.name ASC;";
  $nav_list_SQL = @mysql_query($smap_q);
  $prev_row = ""; 
  $letterlinks = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  echo '<a name="top"></a>';
  for ($i = 0; $i < 26; $i++){
     echo '<b><a href="#'.$letterlinks[$i].'">'.$letterlinks[$i].'</a></b>&nbsp;&nbsp;&nbsp;';
  }
  echo "<br/><br/>"; 
  if (mysql_num_rows($nav_list_SQL)>=1){
	while($sitemap_list_array = mysql_fetch_array($nav_list_SQL,MYSQL_ASSOC)){
		$permit_array = explode(",",$sitemap_list_array["permit"]);
		$compare_permit = in_array($_SESSION['level'],$permit_array);
		if ($compare_permit != 0) {
      		$letter = strtoupper(substr($sitemap_list_array["name"],0,1)); 
 			if ($letter != $prev_row && !is_numeric($letter)) { ?>
				<strong><a name="<?=$letter?>" href="#top"><?=$letter?></a></strong><br>
			<?php } ?>
			- <a href="<?=$sitemap_list_array["link"]?>"><?=ucwords($sitemap_list_array["name"])?></a><br><br> 
			<?php 
			$prev_row = $letter; 
			}
		} 
  	}	 
?>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php';?>