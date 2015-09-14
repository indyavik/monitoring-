<?php
session_start();

if(!isset($_SESSION["cust_id"])) {
include ("dbconf.php");

$qry = "SELECT country FROM country_code";
$qry_res = mysql_query($qry) or die(mysql_error());
$bool = FALSE;

// country
if(isset($_GET['country'])){
    while($res_arr = mysql_fetch_array($qry_res)) {

            if($_POST["country"] == $res_arr["country"] && isset($_POST["submit"]))
                    $selected = "selected";
            else
                    $selected = NULL;
            echo "<option value='".$res_arr["country"]."' ".$selected." >".$res_arr['country']."</option>";
    }
    exit();
}

if(isset($_POST["submit"])) {

        # Validate Empty fields
        if(!isset($_POST["fname"])){exit('First name is required');}
        if(!isset($_POST["lname"])){exit('Last name is required');}
        if(!isset($_POST["email"])){exit('Email is required');}
        if(!isset($_POST["pass"])){exit('Password is required');}

        if($_POST["fname"] == ''){exit('First name is required');}
        if($_POST["lname"] == ''){exit('Last name is required');}
        if($_POST["email"] == ''){exit('Email is required');}
        if($_POST["pass"] == ''){exit('Password is required');}

	include ("frontend_func.php");
	$input = array(
			"email" => $_POST["email"],
			"email2"=> $_POST["email2"],
			"pass"	=> $_POST["pass"],
			"pass2"	=> $_POST["pass2"]

		);
	
	$validation = validate_reg($input);
	
	if($validation["success"]) {
		/*$qry_code = "SELECT code from country_code where country = '".$_POST["country"]."'";
		$code_res = mysql_query($qry_code) or die(mysql_error());
		$code =  mysql_fetch_row($code_res);*/
		$cust_id = $_POST["country"].generate_code($code[0]);
		
		$data = array(
				"email"		=> $_POST["email"],
				"password"	=> md5($_POST["pass"]),
				"cust_id"	=> $cust_id,
				"country"	=> $_POST["country"],
                                "firstname"	=> $_POST["fname"],
                                "lastname"	=> $_POST["lname"]
			);
		
		save_customer($data);
		save_notify($cust_id,$_POST["email"]);
		save_url($cust_id,1);

                set_confirm_account($cust_id);
                send_email($_POST["email"],$_POST["pass"],md5($cust_id));
                exit('ok');
		//echo "added successfully!";
		
		//header("Location: accounts.php");
	} else {
		$error = explode("|",$validation["error"]);
		foreach($error as $err)
			echo $err."<br />";
		$email_val = $_POST["email"];
		$email2_val = $_POST["email2"];
		$pass_val = $_POST["pass"];
	}
} else {
	$email_val = NULL;
	$email2_val = NULL;
	$pass_val = NULL;
	$pass2_val = NULL;
    }
}
?>