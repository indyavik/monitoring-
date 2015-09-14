<?php
//notifications
$bool = TRUE;
$notify = explode("|",$_POST["notifyBy"]);
foreach($notify as $n) {
	if($n == "E") {
		
		//send email notification
		$email = $_POST["notifyEmail"];
		$subject = "Notification!";
		$message = urlencode($_POST["msg"]);
		mail($email, "Subject: $subject",$message, "From: notifier@domain.com" );
		
	} elseif($n == "S" || $n == "P") {
		
		$fields = array(
			"cust_id" =>urlencode($_POST["cust_id"]),
			"msg" =>urlencode($msg)		
			);
		
		if($n == "S") {
			
			//send SMS notification.. if country is US use twilio.. if not use clickatel
			if($_POST["country"] == "US" || $_POST["country"] == "UM") {
				
				$url = 'http://184.106.130.164/serverMonitoring/monitoring/sendSMS.php';
				$fields["notifySMS"] = urlencode($_POST["notifySMS"]);
				
			} else {
				
				//use clickatel
				include("/var/www/html/serverMonitoring/monitoring/backend_func.php");
				clickatell(urlencode($_POST["notifySMS"]),$_POST["msg"]);
				$bool = FALSE;
				
			}
			
		} elseif($n == "P" && $_POST['status'] == "N") {
			
			//send call notification using twilio
			$url = 'http://184.106.130.164/serverMonitoring/monitoring/call.php';
			$fields["notifyCall"] = urlencode($_POST["notifyCall"]);
			$fields["check_type"] = urlencode($_POST["check_type"]);
			$fields["url"] = urlencode($_POST["url"]);
			$bool = TRUE;
			
		} else {
			
			$bool = FALSE;
			
		}
			
		
		
		if($bool) {
			
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
	} else {
		echo "Web only";
	}
}
?>
