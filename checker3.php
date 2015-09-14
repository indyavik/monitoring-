<?php
//check ports if running
foreach($_SESSION[$filename] as $r) {
	
	if(is_array($r)) {
		
		if($r['status'] == "active") {
			
			//get port status
			$fp = @fsockopen($_SESSION[$filename]['url'], $r['port'], $errno, $errstr, 5);
			
			if (!$fp) {
				
				//server port is not responding
				$downtime = time();
				
				if(!$r['error'] || $r['error'] == NULL) {
					
					//if status is from up to down
					$_SESSION[$filename][$r['name']]['error'] = TRUE;
					$_SESSION[$filename][$r['name']]['downstamp'] = $downtime;
					$_SESSION[$filename][$r['name']]['msg'] = "Port ".$r['port']." down @ ".unix_to_human($downtime);
					
				} else {
					
					//if status is still down
					$_SESSION[$filename][$r['name']]['msg'] = NULL;
				}
				
			} else { 
		
				$uptime = time();
					
				if($r['error'] == NULL) {
					
					//first time running the script and server is responding set error to false
					$_SESSION[$filename][$r['name']]['error'] = FALSE;
						
				}
					
				if($r['error']) {
					
					//if status from down to up				
					$_SESSION[$filename][$r['name']]['error'] = FALSE;
					$_SESSION[$filename][$r['name']]['upstamp'] = $uptime;
					$e_time = $uptime - $r['downstamp'];
					$_SESSION[$filename][$r['name']]['msg'] = "Port ".$r['port']." down for ".$e_time." seconds. Started @ ".unix_to_human($uptime);
						
				} else {
					
					//if status is still up
					$_SESSION[$filename][$r['name']]['msg'] = NULL;
				}
				
				//close connection
				fclose($fp);
					
			} 
				
			
		}
	
	}
	
}

//create data array
$data = array(
		"msg" => array(),
		"status" => array()
	);

//populate data
foreach($_SESSION[$filename] as $r) {

	if(is_array($r)) {

		if($r['status'] == "active") {			

			$data['msg'][] = $r['msg'];
		
			if($r['error']) {

				$data['status'][$r['name']] = "N";

			} else {

				$data['status'][$r['name']] = "Y";

			}

		} else {

			$data['status'][$r['name']] = NULL;

		}

	}

}

$msg = implode('\n',$data['msg']);

//check port messages if not null.. send notification to customer 
if($_SESSION[$filename]['http']['msg'] != NULL || $_SESSION[$filename]['smtp']['msg'] != NULL  || $_SESSION[$filename]['pop3']['msg'] != NULL  || $_SESSION[$filename]['imap']['msg'] != NULL  || $_SESSION[$filename]['ssmtp']['msg'] != NULL) {
		
	$_SESSION[$filename]['err_status'] = TRUE;
	
	//get customer's notification details
	$qry = query_details($_SESSION['cust_id']);
	$res = mysql_fetch_row($qry);
	//notify customer
	process_details($res,$msg);
	
} else {
		
	$_SESSION[$filename]['err_status'] = FALSE;
	
}

//save test results
save_result($data['status'],$_SESSION['url_num']);
?>
