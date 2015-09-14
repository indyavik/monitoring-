#!/usr/bin/php
<?php
session_start();
include ('/var/www/html/serverMonitoring/monitoring/dbconf.php');
include ('/var/www/html/serverMonitoring/monitoring/backend_func.php');

$filename = get_filename();
$_SESSION['filename'] = $filename;

//query url and ports to be tested
$url_sql = "SELECT * FROM url WHERE cust_id='".substr($filename,0,-7)."' AND url_num = '".ltrim(substr($filename,-7,-4))."' LIMIT 1";
$url_qry = mysql_query($url_sql);

$url_result = mysql_fetch_row($url_qry);

//get testing cycle
$notif_sql = "SELECT check_period,notified_sms_num,notified_call_num FROM notify WHERE cust_id='".substr($filename,0,-7)."' LIMIT 1";
$notif_qry = mysql_query($notif_sql);

$notif_result = mysql_fetch_row($notif_qry);

$obj = memcache_connect("localhost", 11211);

for($i=1;$i<=9;$i++) {
	$obj->delete($filename.$i);
}

$obj->add($filename.'1',substr($filename,0,-7));
$obj->add($filename.'2',ltrim(substr($filename,-7,-4),'0'));
$obj->add($filename.'3',remove_http_www($url_result[3]));
$obj->add($filename.'4',$url_result[5]);
$obj->add($filename.'5',NULL);
$obj->add($filename.'6',$notif_result[2]);
$obj->add($filename.'7',$notif_result[1]);
$obj->add($filename.'8',0);
$obj->add($filename.'9',0);
$obj->add($filename.'10',$url_result[17]);

while(TRUE) {
	
	//perform checks
	include("/var/www/html/serverMonitoring/monitoring/checker.php");	
	sleep(60 * intval($notif_result[0]));
	
}
?>
