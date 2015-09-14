<?php 

header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	
?>

<Response>
	<Pause length="1"/>
	<Say> Hello,,This is, RockCheck.com, alert service. Calling you to alert regarding </Say>
	<Pause length="1"/> 
	<Say>your, check, </Say> 
	<Pause length="1"/>
	<Say><?php echo $_GET['check_type']; ?> for the URL </Say>
	<Pause length="1"/>
	<Say><?php echo $_GET['url']; ?>. The service, is, down. Thank you for listening to this alert.</Say>	
</Response>


