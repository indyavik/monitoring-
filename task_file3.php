#!/usr/bin/php
<?php
session_start();
include ('/var/www/html/serverMonitoring/monitoring/dbconf.php');
include ('/var/www/html/serverMonitoring/monitoring/backend_func.php');

$filename = get_filename();
$_SESSION['cust_id'] = substr($filename,0,-7);
$_SESSION['url_num'] = ltrim(substr($filename,-7,-4),'0');

//query url and ports to be tested
$sql = "SELECT * FROM url WHERE cust_id='".substr($filename,0,-7)."' AND url_num = '".ltrim(substr($filename,-7,-4))."' LIMIT 1";
$qry = mysql_query($sql);

$result = mysql_fetch_row($qry);

//set session variables
$_SESSION[$filename] = array(
				"err_status" 	=> FALSE,
				"url"		=> remove_http($result[3]),
				"http"		=> array(
								"name"		=> "http",
								"port"		=> $result[5],
								"status"	=> $result[6],
								"error"		=> NULL,
								"msg"		=> NULL,
								"upstamp"	=> 0,
								"downstamp"	=> 0
							),
				"smtp"		=> array(
								"name"		=> "smtp",
								"port"		=> $result[7],
								"status"	=> $result[8],
								"error"		=> NULL,
								"msg"		=> NULL,
								"upstamp"	=> 0,
								"downstamp"	=> 0
							),
				"pop3"		=> array(
								"name"		=> "pop3",
								"port"		=> $result[9],
								"status"	=> $result[10],
								"error"		=> NULL,
								"msg"		=> NULL,
								"upstamp"	=> 0,
								"downstamp"	=> 0
							),
				"imap"		=> array(
								"name"		=> "imap",
								"port"		=> $result[11],
								"status"	=> $result[12],
								"error"		=> NULL,
								"msg"		=> NULL,
								"upstamp"	=> 0,
								"downstamp"	=> 0
							),
				"ssmtp"		=> array(
								"name"		=> "ssmtp",
								"port"		=> $result[13],
								"status"	=> $result[14],
								"error"		=> NULL,
								"msg"		=> NULL,
								"upstamp"	=> 0,
								"downstamp"	=> 0
							),
			);

//get testing cycle
$sql = "SELECT check_period FROM notify WHERE cust_id='".substr($filename,0,-7)."' LIMIT 1";
$qry = mysql_query($sql);

$result = mysql_fetch_row($qry);
var_dump($result);

while(TRUE) {
	
	//perform checks
	include("/var/www/html/serverMonitoring/monitoring/checker.php");	
	sleep(60 * intval($result[0]));
	
}
?>
