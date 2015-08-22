<?php
/* -----------------------------------------------------
 * File name	: auth.class.php	
 * Created by 	: aryonp@gmail.com				   
 * Created date	: 21.10.2008
 * Update date	: 08.11.2014				
 * -----------------------------------------------------			
 * Purpose		: Handling authorization	
 * -----------------------------------------------------						                 			
 */

class Auth {
	
	var $userid;
	var $uname;
	var $password;
	var $seccode;
	var $_errors;
	var $sid;
		
	function __construct(){
		$this->_errors = array();
		$this->sid = session_id();
	}	
	
	function setLogin($uname,$password){
		$this->uname 	= (!empty($uname))?mysql_real_escape_string(strip_tags(trim($uname))):"";
		$this->password = (!empty($password))?mysql_real_escape_string(md5(strip_tags(trim($password)))):"";
	}	
	
	function getLogin(){
		if(empty($this->uname))
		array_push($this->_errors,"Empty email");
			
		if(empty($this->password))
		array_push($this->_errors,"Empty password");
		
		$query = "SELECT CONCAT(u.fname,' ',u.lname) AS fullname, 
						 u.email, 
						 u.id as uid, 
						 u.lid AS level
		          FROM user u 
		          WHERE u.email = '".$this->uname."' AND u.password = '".$this->password."' AND u.active = '1' AND u.del = '0' ";			
		
		$sql = @mysql_query($query) or die(mysql_error());
		
		if(!mysql_num_rows($sql))
		array_push($this->_errors,"Wrong email or password");
		
		if(count($this->_errors) <= 0) {
			$this->setSession($sql);
			
			if (!empty($_SESSION['ctRedirect'])) {
				$page = $_SESSION['ctRedirect'];	
			}
			
			else {
				$page = LOGIN_OK_PAGE;
			}
			
			log_hist(1);
			Header("Location:$page");
			unset($_SESSION['ctRedirect']);
			exit();
		}
	}
	
	function setSession($sql){
		//session_start();
		$array = mysql_fetch_array($sql, MYSQL_ASSOC);
		$_SESSION['fullname'] 	= $array['fullname'];
		$_SESSION['sid']		= $this->sid;
		$_SESSION['uid'] 		= $array['uid'];
		$_SESSION['level'] 		= $array['level'];
		$_SESSION['email'] 		= $array['email'];
		$_SESSION['timestamp'] 	= date('Y-m-d H:i:s');
		//$_SESSION['auth_system']= SYS_CODE;
		//session_unregister($_SESSION['ctRedirect']);
	}
	
	function doLogout(){
		session_start();
		$old_session = $_SESSION['auth_system'];
		
		if(!isset($old_session)) {
			session_unset();
			session_destroy();
			header("Location:".LOGIN_FAIL_PAGE."");
			exit();
		}
		
		else {		
			log_hist(3);
			session_unset();
			session_destroy();
			if(!empty($old_session)) {
				log_hist(2);
				header("Location:".LOGIN_OK_PAGE."");
				exit();
			}
			else {
				array_push($this->_errors,"You cannot logout");
			}
		}
	}
}
?>