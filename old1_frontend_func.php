<?php
function login($uname,$pword) {
	
	//$sql = "SELECT cust_id,firstname,`status`,email FROM customer_info WHERE email = '".$uname."' AND password = '".md5($pword)."'";
	
	$uname = mysql_real_escape_string($uname);
	$pword = mysql_real_escape_string($pword);
	
	$sql = "SELECT cust_id,firstname,`status`,email FROM customer_info WHERE email = '%s' AND password = '%s'";
	$qry_result = mysql_query( sprintf($sql, $uname, md5($pword)) );
	
	if(mysql_num_rows($qry_result) > 0) {
		
		$result = mysql_fetch_row($qry_result);

		$_SESSION['cust_id'] = $result[0];
                $_SESSION['firstname'] = $result[1];
                $_SESSION['status'] = $result[2];
                $_SESSION['email'] = $result[3];
		$_SESSION['logged_in'] = TRUE;

		
		$_SESSION['free'] = 1;
		$_SESSION['basic'] = 5;
		$_SESSION['premium'] = 10;
		return TRUE;
		
	} else {
		
		return FALSE;
		
	}
}

//get notfication details
function fetch_data_notify() {
	
	$sql = "SELECT * FROM notify WHERE cust_id='".$_SESSION['cust_id']."'";	
	$qry = mysql_query($sql);
	
	return mysql_fetch_row($qry);
}

//get url details
function fetch_data_url() {
	
	$sql = "SELECT *,count(cust_id) FROM url WHERE cust_id='".$_SESSION['cust_id']."'";	
	$qry = mysql_query($sql);
	
	return mysql_fetch_array($qry);
	
}

function fetch_data_country() {
	
	$sql = "SELECT country,code FROM country_code";
	$qry = mysql_query($sql);
	
	return mysql_fetch_array($qry);
}

function fetch_data_info() {

	$sql = "SELECT firstname,lastname,phone_area,phone_number,mobile_area,mobile_number,address1,address2,zip,city,state,email,country FROM customer_info WHERE cust_id='".$_SESSION["cust_id"]."'";
	$qry = mysql_query($sql);

	return mysql_fetch_row($qry);	
}

//validate encoded data from myaccount.php
function validate_data($input) {
	
	$validation = array(
                        'success'       => TRUE,
                        'error'         => NULL
                        );
               
        if($input['url'] == NULL) {
		$validation['success'] = FALSE;
		$validation['error'] = "URL must not be empty";
	} elseif(!check_url($input['url'])) {
        	$validation['success'] = FALSE;
        	$validation['error'] = "Invalid URL format";        	
        }
        if((!is_numeric($input['check_period']) && $input["check_period"] != NULL) || $input["check_period"] == NULL) {
                $validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                if($input["check_period"] == NULL) {
                	$validation['error'] .= "Check cycle must not be empty";
                } else {
                	$validation['error'] .= "Check cycle only accepts numbers";
                }
        }
        if((!is_numeric($input['summary']) && $input['summary'] != NULL) || $input['summary'] == NULL) {
                $validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                if($input['summary'] == NULL) {
                	$validation['error'] .= "Summary delivery must not be empty";
                } else {
                	$validation['error'] .= "Summary delivery only accepts numbers";
                }
        }
        if(!check_email($input['email']) && $input['email'] != NULL) {
                $validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                $validation['error'] .= "Invalid email format";
        }
        if($input['http_status'] == "active") {
        	if($input['http_port'] == NULL) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "HTTP Port must not be blank";
        	}
        	if(!is_numeric($input['http_port'])) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "HTTP Port only accepts numbers";
        	}
        }
        if($input['smtp_status'] == "active") {
        	if($input['smtp_port'] == NULL) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "SMTP Port must not be blank";
        	}
        	if(!is_numeric($input['smtp_port'])) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "SMTP Port only accepts numbers";
        	}
        }
        if($input['pop3_status'] == "active") {
        	if($input['pop3_port'] == NULL) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "POP3 Port must not be blank";
        	}
        	if(!is_numeric($input['pop3_port'])) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "POP3 Port only accepts numbers";
        	}
        }
        if($input['imap_status'] == "active") {
        	if($input['imap_port'] == NULL) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "IMAP Port must not be blank";
        	}
        	if(!is_numeric($input['imap_port'])) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "IMAP Port only accepts numbers";
        	}
        }
        if($input['ssmtp_status'] == "active") {
        	if($input['ssmtp_port'] == NULL) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "SSMTP Port must not be blank";
        	}
        	if(!is_numeric($input['ssmtp_port'])) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "SSMTP Port only accepts numbers";
        	}
        }        
	if($input['notify'] == NULL) {
		$validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                $validation['error'] .= "Must pick atleast one notification process";
	}
        foreach($input['notify'] as $n) {
		if((($input[$n] == NULL && $n != "email") || ($n == "email" && $input["email"] == NULL)) && $n != "web") {
			$validation['success'] = FALSE;
			if($validation['error'] != NULL)
	                        $validation['error'] .= " | ";
	                $validation['error'] .= $n." field must not be blank";
		}
	}
        
        return $validation;
	
}

//validate encoded data from myaccount.php
function validate_data_new($input) {
	
	$validation = array(
                        'success'       => TRUE,
                        'error'         => NULL
                        );
               
        if($input['url'] == NULL) {
		$validation['success'] = FALSE;
		$validation['error'] = "URL must not be empty";
	} elseif(!check_url($input['url'])) {
        	$validation['success'] = FALSE;
        	$validation['error'] = "Invalid URL format";        	
        }
        if((!is_numeric($input['check_period']) && $input["check_period"] != NULL) || $input["check_period"] == NULL) {
                $validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                if($input["check_period"] == NULL) {
                	$validation['error'] .= "Check cycle must not be empty";
                } else {
                	$validation['error'] .= "Check cycle only accepts numbers";
                }
        }
        if((!is_numeric($input['summary']) && $input['summary'] != NULL) || $input['summary'] == NULL) {
                $validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                if($input['summary'] == NULL) {
                	$validation['error'] .= "Summary delivery must not be empty";
                } else {
                	$validation['error'] .= "Summary delivery only accepts numbers";
                }
        }
        if(!check_email($input['email']) && $input['email'] != NULL) {
                $validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                $validation['error'] .= "Invalid email format";
        }
        if($input['url_status'] == "active") {
        	if($input['http_port'] == NULL) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "Port must not be blank";
        	}
        	if(!is_numeric($input['http_port'])) {
        		$validation['success'] = FALSE;
			if($validation['error'] != NULL)
				$validation['error'] .= " | ";
			$validation['error'] .= "Port only accepts numbers";
        	}
        }              
	if($input['notify'] == NULL) {
		$validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                $validation['error'] .= "Must pick atleast one notification process";
	}
        foreach($input['notify'] as $n) {
		if((($input[$n] == NULL && $n != "email") || ($n == "email" && $input["email"] == NULL)) && $n != "web") {
			$validation['success'] = FALSE;
			if($validation['error'] != NULL)
	                        $validation['error'] .= " | ";
	                $validation['error'] .= $n." field must not be blank";
		}
	}
        
        return $validation;
	
}

//validate url format
function check_url($url) {
	$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
	if (eregi($urlregex, $url)) 
		return TRUE;
	 else 
		return FALSE;
}

//validate email format
function check_email($email) {
	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
		return FALSE;
	else
		return TRUE;
}

function email_exist($email) {
	
	$qry = "SELECT id FROM customer_info WHERE email = '".$email."'";
	$qry_res = mysql_query($qry) or die(mysql_error());
        if(mysql_num_rows($qry_res) > 0)
		$exist = TRUE;
        else
                $exist = FALSE;
                
}

//update customer notifications
function update_notify($data) {
	
	$sql = "UPDATE notify SET check_period='".$data['check_period']."', ";
	$sql.= "email_summary_period='".$data['summary']."', ";
	$sql.= "notify_email='".$data['email']."', ";
	$sql.= "notify_sms='".$data['sms']."', ";
	$sql.= "notify_call='".$data['call']."', ";
	$sql.= "notify_by='".$data['notify']."' ";
	$sql.= "WHERE cust_id='".$_SESSION['cust_id']."'";	
	mysql_query($sql);
	
}

//update URLs to be tested
function update_url($data,$url_num) {
	
	$sql = "UPDATE url SET ";
	$sql.= "url='".$data['url']."', ";
	$sql.= "status='".$data['url_status']."', ";
	$sql.= "http_port='".$data['http_port']."', ";
	$sql.= "http_status='".$data['http_status']."', ";
	$sql.= "smtp_port='".$data['smtp_port']."', ";
	$sql.= "smtp_status='".$data['smtp_status']."', ";
	$sql.= "pop3_port='".$data['pop3_port']."', ";
	$sql.= "pop3_status='".$data['pop3_status']."', ";
	$sql.= "imap_port='".$data['imap_port']."', ";
	$sql.= "imap_status='".$data['imap_status']."', ";
	$sql.= "ssmtp_port='".$data['ssmtp_port']."', ";
	$sql.= "ssmtp_status='".$data['ssmtp_status']."', ";
	$sql.= "running='t', ";
	$sql.= "check_type='".$data['check_type']."' ";
	$sql.= "WHERE cust_id='".$_SESSION['cust_id']."' AND url_num='".$url_num."'";	
	mysql_query($sql) or die(mysql_error());
	
}

//update URLs to be tested
function update_url_new($data,$url_num) {
	
	$sql = "UPDATE url SET ";
	$sql.= "url='".$data['url']."', ";
	$sql.= "status='".$data['url_status']."', ";
	$sql.= "port='".$data['port']."', ";
	$sql.= "running='t', ";
	$sql.= "check_type='".$data['check_type']."' ";
	$sql.= "WHERE cust_id='".$_SESSION['cust_id']."' AND url_num='".$url_num."'";	
	mysql_query($sql) or die(mysql_error());
	
}

function check_url_empty($cust_id,$url_num) {
	
	$sql = "SELECT cust_id FROM url WHERE cust_id='".$cust_id."' AND url_num='".$url_num."'";
	$qry = mysql_query($sql) or die(mysql_error());
	
	if(mysql_num_rows($qry) > 0) {
		return FALSE;
	} else {
		return TRUE;
	}
	
}

function save_url_new($data) {
	
	$sql = "INSERT INTO url (url,status,port,check_type,cust_id,url_num,running) VALUES ";
	$sql.= "(";
	
	foreach($data as $d) {
		
		$sql.= "'".$d."',";
	}
	
	$sql.= "'t')";
	mysql_query($sql) or die(mysql_error());
	
}

function save_url($cust_id,$url_num) {
	
	$sql = "INSERT INTO url (cust_id,url_num,running) VALUES ('".$cust_id."','".$url_num."','y')";
	mysql_query($sql) or die(mysql_error());
	
}

function save_notify($id,$email) {

	$qry = "INSERT INTO notify (cust_id,notify_email) VALUES ('".$id."','".$email."')";
	mysql_query($qry) or die(mysql_error());
}

function save_customer($data) {
	$qry = "INSERT INTO customer_info (email,password,cust_id,country,firstname,lastname) VALUES ('".$data["email"]."','".$data["password"]."','".$data["cust_id"]."','".$data["country"]."','".$data["firstname"]."','".$data["lastname"]."')";
        mysql_query($qry) or die(mysql_error());
}

function generate_code($c_code) {

	//$charset = "abcdefghijklmnopqrstuvwxyz";
        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charset .= "0123456789";

	do {
		for ($i=0; $i<8; $i++) 
			$key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
		$qry = "SELECT id FROM customer_info WHERE cust_id = '".$c_code.$key."'";
		$qry_res = mysql_query($qry) or die(mysql_error());
		if(mysql_num_rows($qry_res) > 0)
			$exist = TRUE;
		else
			$exist = FALSE;
	} while($exist);
	return $c_code.$key;
}

function validate_reg($input) {
	
	$validation = array(
			"success" 	=> TRUE,
			"error"		=> NULL
			);

	if($input["email"] == NULL) {
		
		$validation["success"] = FALSE;
		$validation["error"] = "Email required!";
		
	}
	
	if(!check_email($input["email"])) {
		
		$validation["success"] = FALSE;
		if($validation["error"] != NULL)
                        $validation["error"] .= " | ";
                $validation["error"] .= "Invalid email format";
                
	}
	
	if(email_exist($input["email"])) {
		
                $validation["success"] = FALSE;
                if($validation["error"] != NULL)
                        $validation["error"] .= " | ";
                $validation["error"] .= "Email taken";
                
        }
        
	if($input["pass"] == NULL) {
		
		$validation["success"] = FALSE;
		if($validation["error"] != NULL)
			$validation["error"] .= " | ";
		$validation["error"] .= "Password required";
		
	}
	
	if($input["email"] != $input["email2"]) {
		
		$validation["success"] = FALSE;
		if($validation["error"] != NULL)
                        $validation["error"] .= " | ";
		$validation["error"] .= "Encoded emails did not match";
		
	}
	
	if($input["pass"] != $input["pass2"]) {
		
                $validation["success"] = FALSE;
                if($validation["error"] != NULL)
                        $validation["error"] .= " | ";
                $validation["error"] .= "Encoded passwords did not match";
                
        }
        
        return $validation;
}


/* ***************** BENEDICT **************************************************/

/*  Description     : USER DEFINE FUNCTIONS (UDF)
 *  Auhtor          : Benedict
 *  version date    : 03.25.2011,04.13.2011
 *  version         : 1.1
 */

# used      : monitoring/accounts.php
# purpose   : to update the customer account information
function update_profile($data=array()){
    if(!count($data)){
        return FALSE;
    }

    mysql_query(
       "UPDATE customer_info SET
            firstname      = '{$data['firstname']}',
            lastname       = '{$data['lastname']}',
            email          = '{$data['email']}',
            phone_area     = '{$data['p_area']}',
            phone_number   = '{$data['p_num']}',
            mobile_area    = '{$data['m_area']}',
            mobile_number  = '{$data['m_num']}',
            address1       = '{$data['add1']}',
            address2       = '{$data['add2']}',
            zip            = '{$data['zip']}',
            city           = '{$data['city']}',
            state          = '{$data['state']}',
            country        = '{$data['country']}'
       WHERE cust_id       = '{$_SESSION['cust_id']}'");

     return TRUE;

}

# used      : monitoring/accounts.php
# purpose   : to check if old password is correct
function is_old_password($oldpass=''){    
    if($oldpass){
        $oldpass = md5($oldpass);
        $sql = "SELECT password from customer_info WHERE cust_id = '{$_SESSION['cust_id']}' AND password = '{$oldpass}'";
        $qry = mysql_query($sql);
        if(mysql_num_rows($qry) == 0 ){
            return FALSE;
        }
        
        return TRUE;
    }
}

# used      : monitoring/accounts.php
# purpose   : to update the password
function update_password($newpass=''){
    if($newpass){
       $newpass = md5($newpass);
       $qry = mysql_query(
           "UPDATE customer_info SET
               password = '{$newpass}'
           WHERE cust_id = '{$_SESSION['cust_id']}'");

           if(mysql_affected_rows($qry) < 0){
                return FALSE;
           }

           $sql = "SELECT email from customer_info WHERE cust_id = '{$_SESSION['cust_id']}'";
           $qry = mysql_query($sql);

           if(mysql_num_rows($qry) == 0 ){
                $result = mysql_fetch_row($qry);
                login($result[0],$newpass);
           }

          return TRUE;
    }

    return FALSE;
}

# used      : monitoring/form.php
# purpose   : to send email after registering.
function send_email($to='',$pwd='',$conf=''){

    // subject
    $subject = 'Your CheckWoo account info.';

    // message
    $message = '
    <html>
    <head>
      <title>Account Infomation</title>
    </head>
    <body>

      <p>Thank your for registering to CheckWoo. Here are your account information.!</p><br />
      
      Please confirm to this link : http://184.106.130.164/serverMonitoring/monitoring/home.php?conf='.$conf.'

      <br /><br />
      <table>
        <tr>
          <td>Email</td><td>: '.$to.'</td>
        </tr>
        <tr>
          <td>Password</td><td>: '.$pwd.'</td>
        </tr>
      </table>
    </body>
    </html>
    ';

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Additional headers
    $headers .= 'From: CheckWoo <admin@checkwoo.com>' . "\r\n";

    // Mail it
    mail($to, $subject, $message, $headers);
}

# used      : monitoring/form.php
# purpose   : to set the confirmation of the accounts
function set_confirm_account($cust_id=''){
  $conf = md5($cust_id);
  $qry  = mysql_query(
       "UPDATE customer_info SET
           confirmation = '{$conf}'
       WHERE cust_id = '{$cust_id}'");
            
}

# used      : monitoring/home.php
# purpose   : to check if the account is confirmed
function get_confirm_account($code=''){
    $sql = "SELECT id,email,password,firstname from customer_info WHERE confirmation = '{$code}'";

    $qry = mysql_query($sql);

    if(mysql_num_rows($qry) <= 0 ){
        return FALSE;
    }

   $result = mysql_fetch_row($qry);
   
   $qry = mysql_query(
       "UPDATE customer_info SET
           confirmation = ''
       WHERE id = '{$result[0]}'");

   if(mysql_affected_rows($qry) < 0){
        return FALSE;
   } 
    
   return $result;

   }

function get_has_confirm(){
    $code = md5($_SESSION['cust_id']);
    $sql = "SELECT confirmation FROM customer_info WHERE cust_id = '{$_SESSION['cust_id']}'";
    $qry = mysql_query($sql);
    if(mysql_num_rows($qry) > 0 ){
        if(mysql_result($qry,0,'confirmation') == ''){
            return TRUE;
        }else{
            return FALSE;
        }        
    }else{
        return FALSE;
    }
}

# used      : monitoring/reports.php
# purpose   : to report query
function get_has_url(){

    $qry = mysql_query("
        SELECT cust_id,url_num FROM url WHERE cust_id = '{$_SESSION['cust_id']}'
        ");

    if(mysql_num_rows($qry)){
        $cuEID = mysql_fetch_row($qry);
        return $_SESSION['cust_id'].'-'.$cuEID[1];
    }

    return FALSE;
    
}


function get_url_checks($cust_ID='',$from='',$to='',$check ='http',$stat='Y'){
	
	$sql = "
        SELECT SUBSTR(timestamp,1,5) as month,count(http) as status,timestamp,http
        FROM test_result WHERE cust_id_num = '{$cust_ID}'        
        AND ( timestamp BETWEEN UNIX_TIMESTAMP('{$from}') AND UNIX_TIMESTAMP('{$to}'))
        GROUP BY month,{$check}
        ORDER BY id desc
        LIMIT 8";

        echo $from." ".$to." ".$sql;
   $qry = mysql_query($sql) or die(mysql_error());

    $rows   = array();

    while($row = mysql_fetch_assoc($qry)){
        $row['timestamp'] = unix_to_human($row['timestamp']);
        $rows[] = $row;
    }
 
   return ($rows);
}

function get_days_checked($cust_ID=''){

    $qry = mysql_query("
        SELECT cust_id,url_num FROM url WHERE cust_id = '{$_SESSION['cust_id']}'
        AND status = '{$status}' AND timestamp BETWEEN '{$from}' AND '{$to}'
        ");


}

function get_uptodate_report($cust_ID=''){
/*
    $qry = mysql_query("
        SELECT timestamp,COUNT(id) as Type FROM test_result WHERE cust_id_num = '{$cuEID}'
        GROUP BY http
        ");
*/

    $qry = mysql_query("
        SELECT timestamp,http FROM test_result WHERE cust_id_num = '{$cust_ID}'
        AND http = 'Y' ORDER BY id desc");

    $rows   = array();
    $months  = array();
    
    while($row = mysql_fetch_assoc($qry)){
        $rows[]           = $row;
        $row['timestamp'] = unix_to_human($row['timestamp']);        
        $months[] = $row;
    }
    
    //print_r($rows);

    $months = array_unique($months,0);

    foreach($months as $value){
        echo strtotime($value['timestamp']);
        echo '<br>';
    }

    echo '<br>';
    echo '<br>';

    print_r($months);

    echo '<br>';
    echo '<br>';

    print_r($rows);

}

function get_days_between(){
    $cuEID = mysql_query("
        SELECT cust_id,url_num FROM url WHERE cust_id = '{$_SESSION['cust_id']}'
        ");

    $cuEID = mysql_fetch_row($cuEID);
    $cuEID = $_SESSION['cust_id'].'-'.$cuEID[1];
    
    $qry = mysql_query("
        SELECT timestamp,http FROM test_result WHERE cust_id_num = '{$cuEID}'
        AND http = 'Y' ORDER BY id desc");

}



function unix_to_human($timestamp = "", $format = 'M d Y H:i:s') {
    if (empty($timestamp) || ! is_numeric($timestamp)) $timestamp = time();
    return ($timestamp) ? date($format, $timestamp) : date($format, $timestamp);
}

function set_delete_url($chkID='0'){
    $sql = "DELETE FROM url WHERE cust_id='{$_SESSION['cust_id']}' AND id = '{$chkID}'";
    mysql_query($sql);
}

function set_save_url($data,$chk) {
    if($chk == 1){
        $chk = 't';
    }else{
        $chk = 'y';
    }
    
    $sql = "INSERT INTO url (
            url,status,port,check_type,cust_id,url_num,running) VALUES ";
    $sql.= "(";

    foreach($data as $d) {
            $sql.= "'".$d."',";
    }

    $sql.= "'{$chk}')";
    
    mysql_query($sql) or die(mysql_error());
}

function set_update_url($data,$chkID) {
        if($data['checked'] == 1){
            $data['checked'] = 't';
        }else{
            $data['checked'] = 'y';
        }

	$sql = "UPDATE url SET ";
	$sql.= "url='".$data['url']."', ";
	$sql.= "status='".$data['status']."', ";
	$sql.= "port='".$data['port']."', ";
        $sql.= "running='{$data['checked']}', ";
	$sql.= "check_type='".$data['check_type']."' ";
	$sql.= "WHERE cust_id='".$_SESSION['cust_id']."' AND id='".$chkID."'";
	mysql_query($sql) or die(mysql_error());
}

function get_num_checks(){
    $sql = "
            SELECT url_num FROM `url` WHERE cust_id = '{$_SESSION['cust_id']}' ORDER BY id asc limit 1
        ";    
    $qry = mysql_query($sql);
	$limit = mysql_num_rows($qry);
	
	if ($limit > 0)
	{
 	   return intval(mysql_result($qry,0,'url_num'));
	}
}

function get_count_checks(){
    $sql = "SELECT COUNT(*) as urls FROM `url` WHERE cust_id = '{$_SESSION['cust_id']}'";
    $qry = mysql_query($sql);
    $_SESSION['used'] = intval(mysql_result($qry,0,'urls'));
    $stat = $_SESSION['status'];
    
    $_SESSION['available'] = intval($_SESSION[$stat]) - intval($_SESSION['used']);
    if(intval($_SESSION['available']) < 0) $_SESSION['available'] = 0;   
}

function get_latest_url_checked(){
    $sql = "
        SELECT timestamp
        FROM `test_result`
        WHERE `cust_id_num` LIKE '%{$_SESSION['cust_id']}%'
        ORDER BY id desc
        LIMIT 1
    ";
        
    $qry = mysql_query($sql);
        
    if(mysql_num_rows($qry)){
         return unix_to_human(mysql_result($qry,0,'timestamp'));
    }else{
        return  'Not Updated';
    }
}

function get_months_report($details=1){
    $col = "";

    if($details == 0){
            $col = ",http";
    }


    $sql = "
        SELECT timestamp $col
        FROM `test_result`
        WHERE `cust_id_num` LIKE '%{$_SESSION['cust_id']}%'
    ";

    $qry = mysql_query($sql);
    
    $rows = array();

    while($row = mysql_fetch_assoc($qry)){
        if($details==1){
            $row['timestamp'] = unix_to_human($row['timestamp'],'M d Y');
            $rows[] = $row['timestamp'];
        }else{
            $row['timestamp'] = unix_to_human($row['timestamp']);
            $rows[] = $row;
        }
    }
    if($details==1)
    return array_unique($rows);
    else
    return $rows;
    
}

function get_has_available_url(){
   if(intval($_SESSION['available']) > 0) return TRUE;
   return FALSE;
}

function get_cust_url(){
    $sql = "
            SELECT id,url_num,url,status,port,check_type FROM url
            WHERE url.cust_id = '{$_SESSION['cust_id']}'
            ORDER BY id desc
        ";

    $qry = mysql_query($sql);
	$limit = mysql_num_rows($qry);
	
	if ($limit > 0)
	{
	    while($row = mysql_fetch_assoc($qry)){
	        $rows[] = $row;
	    }
    return $rows;
   }
   
}

function get_cust_notify(){
	$sql = "SELECT
                    check_period as cycle,
                    email_summary_period as summary,
                    notify_email,notify_sms,notify_call,
                    notify_by
                FROM notify WHERE cust_id='".$_SESSION['cust_id']."'";
	$qry = mysql_query($sql);

        while($row = mysql_fetch_assoc($qry)){
            $rows[] = $row;
        }

        return $rows;
}

function validate_notify($input) {

	$validation = array(
                        'success'       => TRUE,
                        'error'         => NULL
                        );

        if((!is_numeric($input['check_period']) && $input["check_period"] != NULL) || $input["check_period"] == NULL) {
                $validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                if($input["check_period"] == NULL) {
                	$validation['error'] .= "Check cycle must not be empty";
                } else {
                	$validation['error'] .= "Check cycle only accepts numbers";
                }
        }
        if((!is_numeric($input['summary']) && $input['summary'] != NULL) || $input['summary'] == NULL) {
                $validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                if($input['summary'] == NULL) {
                	$validation['error'] .= "Summary delivery must not be empty";
                } else {
                	$validation['error'] .= "Summary delivery only accepts numbers";
                }
        }
        if(!check_email($input['email']) && $input['email'] != NULL) {
                $validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                $validation['error'] .= "Invalid email format";
        }

  	if($input['notify'] == NULL) {
		$validation['success'] = FALSE;
                if($validation['error'] != NULL)
                        $validation['error'] .= " | ";
                $validation['error'] .= "Must pick atleast one notification process";
	}
        foreach($input['notify'] as $n) {
		if((($input[$n] == NULL && $n != "email") || ($n == "email" && $input["email"] == NULL)) && $n != "web") {
			$validation['success'] = FALSE;
			if($validation['error'] != NULL)
	                        $validation['error'] .= " | ";
	                $validation['error'] .= $n." field must not be blank";
		}
	}

        return $validation;

}

?>


