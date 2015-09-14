<?php
session_start();
include ('dbconf.php');
include ('backend_func.php');

//get url status
$sql = "SELECT status FROM url WHERE cust_id='".$_SESSION['cust_id']."' AND url_num='".$_SESSION['url_num']."' LIMIT 1";
$qry = mysql_query($sql);

$result = mysql_fetch_row($qry);

if($result[0] == "active") {
	
	exec_file($_SESSION['url_num']);
	
}
header('Location: settings.php');
?>
