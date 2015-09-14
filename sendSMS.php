<?php
 
    // Include the PHP TwilioRest library
    require "twilio.php";
     
    // Twilio REST API version
    $ApiVersion = "2010-04-01";
 
    // Set our AccountSid and AuthToken
    $AccountSid = "AC11cd766ffb444eb5fd81b60f5ae46e57";
    $AuthToken = "b62991e53413ef83777b8dba95be46e6";
 
    // Instantiate a new Twilio Rest Client
    $client = new TwilioRestClient($AccountSid, $AuthToken);
 
    // make an associative array of server admins
    $people = array(
        $_POST["notifySMS"]=>$_POST["cust_id"]
    );
 
    // Iterate over all our server admins
    foreach ($people as $number => $name) {
 
        // Send a new outgoinging SMS by POST'ing to the SMS resource */
        // YYY-YYY-YYYY must be a Twilio validated phone number
        $response = $client->request("/$ApiVersion/Accounts/$AccountSid/SMS/Messages", 
            "POST", array(
            "To" => $number,
            "From" => "347-627-0989",
            "Body" => $_POST["msg"]
        ));
        if($response->IsError)
            echo "Error: {$response->ErrorMessage}";
        else
            echo "Sent message to $name";
    }
 
?>
