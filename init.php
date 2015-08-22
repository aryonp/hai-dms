<?php
/* -----------------------------------------------------
 * File name  	: init.php										
 * Created by 	: aryonp@gmail.com
 * Modify date  : 15.11.2014
 * -----------------------------------------------------						           
 * Purpose    	: Initialize basic configuration of the system							                 			
 * -----------------------------------------------------
 */

/* -- Define product details here -- */

define("COMP_NAME", "Satoeboemi");
define("ADMIN", "support@satoeboemi");
define("PRODUCT", "eMangan");
define("VERSION", "1.0");
define("PRODUCT_SN", "TRQ001");

/* -- Define default settings here -- */

define("DEFAULT_MAIL_DOMAIN", "@satoeboemi");
define("DEFAULT_PASS", "satoeboemi");
define("URL_INTER", "/hai-dms");
define("URL_INTRA", "/hai-dms");
define("COMP_URL", "/hai-dms");
define('DB_HOST', 'localhost');
define('DB_USER', 'hai-dms');
define('DB_PASS', 'hai-dms123');
define('DB_USE', 'hai-dms');
define('MAX_ADMIN', '1');
define('PASS_ITERATE', '50');
define('DEFAULT_RPP', '25');
define('BUILD_YEAR', '2015');

/* -- Start defining folder & etc here -- */

$notify 		= false;
$notify_msg 	= "The system is currently on maintenance mode. Please log off from all of your activities now.";
$library_folder = "library";
$contr_folder 	= "controller";
$view_folder 	= "view";
$themes_folder 	= "themes";
$plugins_folder = "plugins";
$files_folder 	= "files";
$img_folder 	= "img";
$css_folder 	= "css";
$js_folder 		= "js";
$default_theme 	= "bootstrap";
$login_OK 		= "home.php";
$login_FAIL 	= "login.php";
$lic_INVALID	= "licenseNotValid.php";

/* -- Start defining path -- */

define('BASEPATH', realpath(dirname(__FILE__)).'/');
/* -- define('SYSPATH', '/'.basename(dirname(__FILE__)).'/'); -- */
define('SYSPATH', '/hai-dms/');

define('LIB_PATH', BASEPATH.$library_folder.'/');
define('CONT_PATH', BASEPATH.$contr_folder.'/');
define('VIEW_PATH', BASEPATH.$view_folder.'/');
define('THEME_PATH', BASEPATH.$themes_folder.'/');
define('THEME_DEFAULT', THEME_PATH.$default_theme.'/');
define('IMG_PATH', SYSPATH.$themes_folder.'/'.$default_theme.'/'.$img_folder.'/');
define('CSS_PATH', SYSPATH.$themes_folder.'/'.$default_theme.'/'.$css_folder.'/');
define('JS_PATH', SYSPATH.$themes_folder.'/'.$default_theme.'/'.$js_folder.'/');
define('FONTS_PATH', SYSPATH.$themes_folder.'/'.$default_theme.'/'.$js_folder.'/');
define('FILES_PATH', SYSPATH.$files_folder.'/');
define('LOGIN_OK_PAGE', SYSPATH.$login_OK);
define('LOGIN_FAIL_PAGE', SYSPATH.$login_FAIL);
define('LIC_INVALID_PAGE', SYSPATH.$lic_INVALID);

/* -- Start DB conn -- */

require_once LIB_PATH.'config.lib.php';

?>