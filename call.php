<?php
	
    /* Include the Twilio PHP library */
    require "twilio.php";
 
    /* Twilio REST API version */
    $ApiVersion = "2010-04-01";
 
    /* Set our AccountSid and AuthToken */
    $AccountSid = "AC11cd766ffb444eb5fd81b60f5ae46e57";
    $AuthToken = "b62991e53413ef83777b8dba95be46e6";
     
    // Outgoing Caller ID you have previously validated with Twilio 
    $CallerID = '3476270989';
     
    /* File Location for use in REST URL */
    $url = 'http://184.106.130.164/serverMonitoring/monitoring/';

    /* Instantiate a new Twilio Rest Client */
    $client = new TwilioRestClient($AccountSid, $AuthToken);

    /* @start snippet */

    /* Start TwiML */
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
   
    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/Calls",
			"POST", array(
			"From" => $CallerID,
			"To" => $_POST["notifyCall"],//'+639286250211',//'+6591759621',//
			"Url" => $url . 'say.php?check_type='.$_POST['check_type'].'&url='.$_POST['url']));//?url=http://184.106.131.46/broadcast/record.mp3'));
    if($response->IsError)
        echo "Error: {$response->ErrorMessage}";
    else
        echo "Started call: {$response->ResponseXml->Call->Sid}";
?>
