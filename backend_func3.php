<?php
//script management
function exec_file($data) {
	
	$path = "./files/";
	$raw_fname = $_SESSION['cust_id'].str_pad($data,3,"0",STR_PAD_LEFT).".php";
	$comp_fname = $path.$raw_fname;
		
	if(file_exists($comp_fname)) {
		
		//update file status to 't'.. tell cron to terminate the this script
		$sql = "UPDATE url SET running='t' WHERE cust_id='".$_SESSION['cust_id']."' AND url_num='".$data."'";
		mysql_query($sql);
				
	} else {
		
		//create task file
		create_taskfile($raw_fname);
		//make php file executable
		$task = "chmod +x ".$comp_fname;
		shell_exec($task);
		
	}
		
}

function get_filename() {
	
	$user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
	$current_file = $_SERVER["SCRIPT_NAME"];
	$parts = explode('/', $current_file);
	$current_file = $parts[count($parts) - 1];
	
	return $current_file;
	
}

//create executable file
function create_taskfile($fname) {
	
	$file = 'task_file.php';
	$newfile = './files/'.$fname;
	
	if (!copy($file, $newfile)) {
	    echo "failed to copy $file... \n";
	}
}

//convert machine time to human time
function unix_to_human($timestamp = "", $format = 'D d M Y - H:i:s') {
    if (empty($timestamp) || ! is_numeric($timestamp)) $timestamp = time();
    return ($timestamp) ? date($format, $timestamp) : date($format, $timestamp);
}

//get notification details
function query_details($cust_id) {
	
	$sql = "SELECT n.cust_id, c.country, n.notify_email, n.notify_sms, n.notify_call, n.notify_by FROM notify as n ";
	$sql .= "JOIN customer_info AS c ON c.cust_id = n.cust_id ";
	$sql .= "WHERE n.cust_id = '".$cust_id."'";
	$qry = mysql_query($sql) or die(mysql_error());
	return $qry;
}

//send notifications
function process_details($data,$msg) {
	$url = 'http://184.106.130.164/serverMonitoring/monitoring/notify.php';
	$fields = array(
		"cust_id" =>urlencode($data[0]),
		"country" =>urlencode($data[1]),
		"notifyEmail" =>urlencode($data[2]),
		"notifySMS" =>urlencode($data[3]),
		"notifyCall" =>urlencode($data[4]),
		"notifyBy" =>urlencode($data[5]),
		"msg" =>urlencode($msg)		
		);
	
	
	//url-ify the data for the POST
	$fields_string = NULL;
	foreach($fields as $key=>$value)
		{ $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string,'&');
		
	//open connection
	$ch1 = curl_init();
	
	//set the url, number of POST vars, POST data
	curl_setopt($ch1,CURLOPT_URL,$url);
	curl_setopt($ch1,CURLOPT_POST,count($fields));
	curl_setopt($ch1,CURLOPT_POSTFIELDS,$fields_string);
	
	//execute post
	$result = curl_exec($ch1);
	
	//close connection
	curl_close($ch1);
}

//save test results to database
function save_result($data,$url_num) {

	$sql = "INSERT INTO test_result (cust_id_num,http,smtp,pop3,imap,ssmtp,timestamp,sms_timestamp,call_timestamp) VALUES ";
	
	$value = implode("','",$data);	
	
	$sql.= "('".$_SESSION['cust_id']."-".$url_num."', '".$value."',UNIX_TIMESTAMP(NOW()), NULL, NULL)";
	
	mysql_query($sql) or die(mysql_error());
	
}

function clickatell($sender,$msg) {
/*************************************** CLICKA-TEL Gateway ************************************************************************/
	if($sender!=null && $msg!= null) {
		
		//$funName="sendsms2";
		$user = "P2Gaccount";
		$password = "kallist1";
		$api_id = "3296433";
		$baseurl = "http://api.clickatell.com";
		$text=urlencode($msg);
		//die($text)
		$to = $sender;
		//$from= "447624812274";
		//$cretime = date("dmY",time());
		//$validtime = date("dmY",(time()*24*60*60));

		$url="$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";
		
		$ret=file($url);
		//die(var_dump($ret));
		$sess=split(":",$ret[0]);

		if($sess[0]=="OK") {
			//$stime=date("m/d/y h:i:S A", time());
			//$cliMsgId=generatePasswd(8,8);
			$sess_id=trim($sess[1]);
			$url="http://api.clickatell.com/http/sendmsg?session_id=$sess_id&to=$to&text=$text";
			$ret=file($url);
			$res=split(":",$ret[0]);
			//var_dump($ret);

			if($res[0]=="ID") {
				$text = "Message Sent Sucessfully";
			} else {
				$text =  "send message failed";
			}
		} else {
			$text="Not able to connect to gateway";
		}
	} else {
		$text="Required parametes are null";
	}
	//echo $text;
}

function remove_http($url) {
	
	return ereg_replace("(https?)://", "", $url);
  
}
?>
