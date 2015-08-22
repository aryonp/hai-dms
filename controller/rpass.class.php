<?php
/* -----------------------------------------------------
 * File name	: rpass.class.php	
 * Created by 	: aryonp@gmail.com				   
 * Created date	: 20.11.2014
 * Update date	: 20.11.2014				
 * -----------------------------------------------------			
 * Purpose		: Handling reset password	
 * -----------------------------------------------------						                 			
 */

class resetPass {
	
	var $email;
	var $rkey;
	var $new_pass_1;
	var $new_pass_2;
	var $_errors;
		
	function __construct(){
		$this->_errors = array();
	}	

	function set_email($email){
		$this->email = (!empty($email))?mysql_real_escape_string(strip_tags(trim($email))):"";
	}
	
	function set_rkey($rkey){
		$this->rkey = (!empty($rkey))?mysql_real_escape_string(strip_tags(trim($rkey))):"";
	}
	
	function set_new_pass_1($new_pass_1){
		$this->new_pass_1 = (!empty($new_pass_1))?mysql_real_escape_string(strip_tags(trim($new_pass_1))):"";
	}
	
	function set_new_pass_2($new_pass_2){
		$this->new_pass_2 = (!empty($new_pass_2))?mysql_real_escape_string(strip_tags(trim($new_pass_2))):"";
	}
	
	function gen_rkey($length){
		return randomKeys($length);
	}
	
	function gen_reset_session() {
		if(empty($this->email))
		array_push($this->_errors,"Empty email");
		
		$rst_q		= "SELECT u.email, CONCAT(u.fname,' ',u.lname) AS fullname 
				       FROM user u 
				       WHERE u.email = '".$this->email."';";
		$rst_SQL 	= @mysql_query($rst_q) or die(mysql_error());
		
		if(mysql_num_rows($rst_SQL)>=1){
			while($rst_arr = mysql_fetch_array($rst_SQL,MYSQL_ASSOC)){
				$this->email_rst_pass();
			}
		}
		else {
			array_push($this->_errors, "Invalid email input.");
		}
	}
	
	function ver_reset_session(){
		if(empty($this->email))
			array_push($this->_errors,"Empty email");
		
		if(empty($this->rkey))
			array_push($this->_errors,"Empty reset key");
		
		$ver_q 	 = "SELECT * 
				    FROM rst_sess rs
		            WHERE rs.email = '".$this->email."' AND 
		            	  rs.rkeys = '".$this->rkey."' AND 
		            	  rs.udate = '0000-00-00 00:00:00' AND 
		            	  rs.used = '0';";
		$ver_SQL = @mysql_query($ver_q) or die(mysql_error());		
		if(mysql_num_rows($ver_SQL)>=1){
			return true;
		}
		else {
			array_push($this->_errors, "Invalid reset session or misredirection.");
			return false;
		}
	}
	
	function change_pass_w_session() {
		if(empty($this->email))
			array_push($this->_errors,"Empty email");
		
		if(empty($this->rkey))
			array_push($this->_errors,"Empty reset key");
		
		if(empty($this->new_pass_1))
			array_push($this->_errors,"Empty new password");
		
		if(empty($this->new_pass_2))
			array_push($this->_errors,"Empty new matching password");
		
		if ($this->new_pass_1 == $this->new_pass_2 && count($this->_errors) == 0) {
			$chg_p_q = "UPDATE user u 
			            SET u.password = MD5('$this->new_pass_1'), u.updBy = '0', u.updDate = '".date('Y-m-d H:i:s')."' 
			            WHERE u.email = '$this->email';";
			if(mysql_query($chg_p_q) or die(mysql_error())) {
				$udate = date('Y-m-d H:i:s');
				$upd_q = "UPDATE rst_sess rs 
				          SET rs.used = '1', rs.udate = '$udate'
				          WHERE rs.email = '$this->email' AND rs.rkeys = '$this->rkey';";
				@mysql_query($upd_q) or die(mysql_error());
				array_push($this->_errors, "Your password has been sucessfully changed.");
			}
			else {
				array_push($this->_errors, "Failed to change password.");
			}
		}
		else {
			array_push($this->_errors, "Password doesn't match.");
		}
	}
	
	function change_pass_wo_session() {
		if(empty($this->email))
			array_push($this->_errors,"Empty email");
	
		if(empty($this->new_pass_1))
			array_push($this->_errors,"Empty new password");
	
		if(empty($this->new_pass_2))
			array_push($this->_errors,"Empty new matching password");
	
		if ($this->new_pass_1 == $this->new_pass_2 && count($this->_errors) == 0) {
			$chg_p_q = "UPDATE user u 
			            SET u.password = MD5('$this->new_pass_1'), u.updBy = '0', u.updDate = '".date('Y-m-d H:i:s')."' 
			            WHERE u.email = '$this->email';";
			if(mysql_query($chg_p_q) or die(mysql_error())) {
				array_push($this->_errors, "Your password has been sucessfully changed.");
			}
			else {
				array_push($this->_errors, "Failed to change password.");
			}
		}
		else {
			array_push($this->_errors, "Password doesn't match or unexpected errors.");
		}
	}
	
	function email_rst_pass() {
		if(empty($this->email))
			array_push($this->_errors,"Empty email");
		
		if(empty($this->rkey))
			array_push($this->_errors,"Empty reset key");
		
		$createSessDate = date('Y-m-d H:i:s');
		$rst_sess_q 	= "INSERT INTO rst_sess (email,rkeys,cdate) 
		                   VALUES ('$this->email','$this->rkey','$createSessDate');";
		
		if(mysql_query($rst_sess_q) or die(mysql_error())) {
			array_push($this->_errors,"A reset session has been created on the system.");
		}
		else {
			array_push($this->_errors,"A reset session cannot be created.");
		}
		
		$detRcv_q	= "SELECT u.salut, CONCAT(u.fname,' ',u.lname) AS fullname 
		               FROM user u 
		               WHERE u.email = '$this->email';";
		$detRcv_SQL = @mysql_query($detRcv_q) or die(mysql_error());
	
		$headers 		= "From: \"QMS Notification\" <support@trinqle.com>\r\nX-Mailer:".PRODUCT." ".VERSION."/MIME-Version: 1.0\n";
		$notif_pass_msg = "Dear %s %s,\n\n".
				"You have requested to reset your password. Please follow attached link below\n".
				"%s".
		        "Please ignore this email if you never request to reset your password\n\n".
		        "Kindly Regards\n".
		        "- ".PRODUCT." -";
	
		while($detRcv_arr = mysql_fetch_array($detRcv_SQL, MYSQL_ASSOC)) {
			$salut 		= ucwords($detRcv_arr["salut"]);
			$name 		= ucwords($detRcv_arr["fullname"]);
			$subject 	= "\"Reset Password\" confirmation";
			$rstLink	= URL_INTRA."/rpsess.php?remail=".$this->email."&rkey=".$this->rkey;
			$message	= sprintf($notif_pass_msg,$salut,$name,$rstLink);
			
			if(mail($this->email, $subject, $message, $headers)) {
				array_push($this->_errors,"An email has been set to your mailbox.");
				//log_hist(109,$email);
			}
			else {
				array_push($this->_errors,"Failed to sent email to reset your password or your network policy doesnt support external mail function.");
			}
			
		}
	}
}
?>