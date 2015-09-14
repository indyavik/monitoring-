<?php
    session_start();

    if($_SESSION['logged_in']) {
            include ("dbconf.php");
            include ("frontend_func.php");
    }else {
            header('Location: index.php');
    }

    if(isset($_POST['set_update'])){
        switch($_POST['set_update']){
            case 'profile':
                @$data['firstname'] = mysql_escape_string($_POST['fname']);
                @$data['lastname'] = mysql_escape_string($_POST['lname']);
                @$data['email'] = mysql_escape_string($_POST['email']);
                @$data['p_area'] = $_POST['p_area'];
                @$data['p_num'] = $_POST['p_num'];
                @$data['m_area'] = $_POST['m_area'];
                @$data['m_num'] = $_POST['m_num'];
                @$data['add1'] = mysql_escape_string($_POST['add1']);
                @$data['add2'] = mysql_escape_string($_POST['add2']);
                @$data['zip'] = mysql_escape_string($_POST['zip']);
                @$data['city'] = mysql_escape_string($_POST['city']);
                @$data['state'] = mysql_escape_string($_POST['state']);
                @$data['country'] = $_POST['country'];
                
                update_profile($data);

                $_SESSION['email'] = $data['email'];
                
                exit('ok');
            break;
            case 'password':
                if(!is_old_password($_POST['oldPass'])){
                    exit('Old password is invalid');
                }

                if(!update_password ($_POST['newPass'])){
                    exit('Unable to update password.');
                }

                exit('ok');
            break;

            default:
                exit('Problem: No data to update.');
            break;
        }
    }

    $data = fetch_data_info();
    $firstname = $data[0];
    $lastname = $data[1];

    $p_area = $data[2];
    $phone = $data[3];
    $m_area = $data[4];
    $cell = $data[5];
    
    $add1 = $data[6];
    $add2 = $data[7];
    $zip = $data[8];
    $city = $data[9];
    $state = $data[10];
    $email = $data[11];
    $country = $data[12];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-language" content="en" />

<title>CheckWoo | ACCOUNTS </title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<link href="css/accounts.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){

        $(".account-menu li").click(function(){
           var mod = $(this).attr('class');
           $(".a-panel").hide();
           $(".a-"+mod).show();
        });

        $("#p_area").val('<?php echo $p_area; ?>');
        $("#m_area").val('<?php echo $m_area; ?>');
        $("#country").val('<?php echo $country; ?>')

        $("#a-profile").click(function(){
            $.post("accounts.php",{
                fname: $("#firstname").val(),
                lname: $("#lastname").val(),
                email: $("#email").val(),
                p_area: $("#p_area").val(),
                p_num: $("#p_num").val(),
                m_area: $("#m_area").val(),
                m_num: $("#m_num").val(),
                add1: $("#add1").val(),
                add2: $("#add2").val(),
                zip: $("#zip").val(),
                city: $("#city").val(),
                state: $("#state").val(),
                country: $("#country").val(),
                set_update: 'profile'
            },function(data){
                if($.trim(data) == 'ok'){
                    msg('Successfully Change.','green');
                }else{
                    msg(data,'red');
                }
            })
        });

        $("#a-pass").click(function(){
            if($("#newpass").val() != $("#confpass").val()){
                   msg('Confirmation and New password didn\'t match.','red');
                    return false;
            }

            $.post("accounts.php",{
              oldPass: $("#oldpass").val(),
              newPass: $("#newpass").val(),
              set_update: 'password'
            },function(data){
                if($.trim(data) == 'ok'){
                    msg('Successfully Change','green')
                }else{
                    msg(data,'red')
                }
            });
        });

        function msg(data,color){
            $(".cls-msg").html(data);
            $(".cls-msg").css('color',color);
            $(".cls-msg").css('cursor','pointer');
            $(".cls-msg").show();
            setTimeout(function(){
                $(".cls-msg").fadeOut(1000, function(){
                    $(this).hide();
                });
            },3000);
        }
    });

</script>

</head>
<body>

    <div class="header-bg">
        <div class="header">
            <div class="header-left"></div>
            <div class="header-right">
                <div>
                    <div class="logout">
                        <a href="logout.php">Logout</a>
                    </div>

                    <div class="menu">
                        <ul>
                            <li><a href="home.php">HOME</a> </li>
                            <li><a href="settings.php">SETTINGS</a></li>
                            <li><a href="accounts.php">ACCOUNTS</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="content">
        <h2><?php if($_SESSION['logged_in']) {echo "Welcome ".$_SESSION["firstname"]."<br />";}?></h2>
        <h3>ACCOUNTS</h3>
       <div class="form-accounts">
        <div class="account-menu">
            <ul>
                <li class="settings">PROFILE </li>
                <li class="subscription"> SUBSCRIPTION</li>
                <li class="invoices">INVOICES</li>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="a-settings a-panel">
            <h3>PROFILE</h3>
                
            <p>
                These are your account settings. This is where you can update your contact information, set your time zone and local settings, change your password, or change which credit card you want to use(for paid services). Don’t forget to save your changes by clicking on the “Save Settings” button below the form.
            </p>

            <div class="reg-msg cls-msg" style="font-size:12px; display:none;border:1px #5D080A solid;padding:10px;width:500px;background-color:#D4CAC8"></div>
            <div class="clear"></div>
            
            <div style="float:left;">
            <h4>Account Details</h4>
            <table>
                <tr><td>First Name</td><td>: <input type="text" id="firstname" value="<?php echo $firstname;?>"/></td></tr>
                <tr><td>Last Name</td><td>: <input type="text" id="lastname" value="<?php echo $lastname;?>"/></td></tr>
                <tr><td>Email</td><td>: <input type="text" id="email" value="<?php echo $email; ?>"/></td></tr>
                <tr><td>Phone</td><td>: <?php include('html/phone.html')?> <input type="text" id="p_num" value="<?php echo $phone;?>"/></td></tr>
                <tr><td>Cell phone</td><td>: <?php include('html/mobile.html')?> <input type="text" id="m_num" value="<?php echo $cell;?>"/></td></tr>
                <tr><td>Address</td><td>: <input type="text" id="add1" value="<?php echo $add1;?>"/></td></tr>
                <tr><td>Address 2</td><td>: <input type="text" id="add2" value="<?php echo $add2;?>"/></td></tr>
                <tr><td>ZIP/Postal code</td><td>: <input type="text" id="zip" value="<?php echo $zip;?>"/></td></tr>
                <tr><td>City/Location</td><td>: <input type="text" id="city" value="<?php echo $city;?>"/></td></tr>
                <tr><td>State</td><td>: <input type="text" id="state" value="<?php echo $state;?>"/></td></tr>
                <tr><td>Country</td><td>: <?php include('html/country.html')?></td></tr>
            </table>
            <input type="button" id="a-profile" value="Save Settings" />
            </div>


            <div style="float:right;">
            <h4>Change Password</h4>
            <table>
                <tr><td>Old Password</td><td>: <input type="password" id="oldpass" /></td></tr>
                <tr><td>New Password</td><td>: <input type="password" id="newpass" />(min. 6 characters)</td></tr>
                <tr><td>Confirm Password</td><td>: <input type="password" id="confpass" /></td></tr>
            </table>
            <input type="button" id="a-pass" value="Save Settings" />
            </div>

            <div class="clear"></div>
        </div>

        <div class="a-subscription a-panel">
            <h3>SUBSCRIPTIONS</h3>
            <p>
                On this page you can see the services you are currently subscribed to. You can upgrade or downgrade your account to another package type, or cancel your existing account.
            </p>
            <div>
                <table>
                    <tr><td>Package</td><td>URL</td><td>SMS discount</td></tr>
                    <tr><td>PREMIUM</td><td>10</td><td>Unlimited</td></tr>
                    <tr><td>BASIC</td><td>5</td><td>5</td></tr>
                    <tr><td>FREE</td><td>1</td><td>1</td></tr>
                </table>
            </div>
        </div>

        <div class="a-invoices a-panel">
            <h3>INVOICES</h3>
            <p>
                 On this page you can see all invoices and their status (paid or unpaid).
            </p>
            <div>

            </div>
        </div>
    </div>
    </div>
    <div style="background-color:#000000;">
    <div  class="footer">
        <div><a href="">About Us</a> | <a href="">Terms and Condition</a> | <a href="">Services</a>
            <br /> <br />
            <span>Copyright &copy Vestige System</span>
        </div>
        <div class="clear"></div>
    </div>
    </div>

    <div class="clear"></div>
</body>
</html>





