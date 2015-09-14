<?php

$fp = @fsockopen($obj->get($filename.'3'),$obj->get($filename.'4'), $errno, $errstr, 5);

$msg = NULL;
$time = NULL;
 
if(!$fp) {
	
	$downtime = time();
	
	if($obj->get($filename.'5') == NULL || $obj->get($filename.'5') == 'Y') {
		
		$obj->replace($filename.'9',$downtime);
		$obj->replace($filename.'5','N');
		$msg = "Port ".$obj->get($filename.'4')." down @ ".unix_to_human($downtime);
		
	}
	
} else {
	
	$uptime = time();
	
	if($obj->get($filename.'5') == NULL) {
		
		$obj->replace($filename.'5','Y');
		
	} elseif($obj->get($filename.'5') == 'N') {
		
		$obj->replace($filename.'5','Y');
		$obj->replace($filename.'8',$uptime);
		$e_time = $uptime - $obj->get($filename.'9');
		$msg = "Port ".$obj->get($filename.'4')." down for ".$e_time." seconds. Started @ ".unix_to_human($uptime);
		
	}
	
	fclose($fp);
	
}

$time = time();

//save test results
save_result($obj->get($filename.'1'),$obj->get($filename.'2'),$obj->get($filename.'5'),$time);

if($msg != NULL) {
	
	$call = NULL;
	$sms = NULL;
	
	//get customer's notification details
	$qry = query_details($obj->get($filename.'1'));
	$res = mysql_fetch_row($qry);
	
	//notify customer
	process_details($res,$msg,$obj->get($filename.'10'),$obj->get($filename.'3'),$obj->get($filename.'5'));
	
	//update notification (sms && call) timestamp
	$notif = explode("|",$res[5]);
	
	foreach($notif as $row) {
		
		if($row == 'S') {
			
			$sms = $time;
			
			$obj->replace($filename.'7',$obj->get($filename.'7')+1);
			update_notification_count($obj->get($filename.'1'),'notified_sms_num',$obj->get($filename.'7'));
			
		} elseif($row == 'P' && $obj->get($filename.'5') == 'N') {
			
			$call = $time;
			
			$obj->replace($filename.'6',$obj->get($filename.'6')+1);
			update_notification_count($obj->get($filename.'1'),'notified_call_num',$obj->get($filename.'6'));
			
		}
	}
	update_result($obj->get($filename.'1'),$obj->get($filename.'2'),$obj->get($filename.'5'),$time,$call,$sms);
	
}


?>
