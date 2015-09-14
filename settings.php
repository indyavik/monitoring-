<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-language" content="en" />

<title>CheckWoo | SETTINGS </title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<link href="css/settings.css" rel="stylesheet" type="text/css" />
<link href="js/jQueryUI/theme/dark-hive/jquery-ui-1.8.11.custom.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="js/jQueryUI/js/jquery-ui-1.8.11.custom.min.js"></script>
<script type="text/javascript" src="js/settings.php.js"></script>

</head>

<body>
<?php
    session_start();
    if(!$_SESSION['logged_in']) { header('Location: index.php');}
    else{
        include ("dbconf.php");
        include ("frontend_func.php");
        get_count_checks();
    }
?>
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
        <h3>SETTINGS</h3>
        <?php if(isset($_GET['n'])){ ?>
                <div class="ui-widget">
			<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
				<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>
                                    <label style="padding-left:5px;">
                                         Please setup your URL check settings.
                                    </label></p>
			</div>
		</div>
         <?php } ?>

        <div class="content-box">
            <div class="box-title">
                <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" style="padding:10px;"> <h3>Check Settings</h3> </div>
                <p>
                    You can setup your URL by clicking <img src="images/add.jpg" alt="add" width="20" height="18"/> <strong>ADD</strong> button at the upper left corner of table.
                    If you want to modify/update your URL just click the  <img src="images/edit.jpg" alt="add" width="20" height="18"/> <strong>EDIT </strong>button.
                    If you want to delete one of your URL just click the  <img src="images/delete.jpg" alt="add" width="20" height="18"/> <strong>DELETE</strong> button.
                </p>
            </div>

            <div class="url-checklist">
                <div>
                    <img class="add_new_url" title="ADD" src="images/add.jpg" alt="add" width="28" height="24" style="cursor:pointer;"/>
                    <span style="padding-left:100px;font-weight: bold;">Used: <label class="used_check"><?php echo @$_SESSION['used']; ?></label></span>
                    <span style="padding-left:50px;font-weight: bold;">Available:<label class="available_check"><?php echo @$_SESSION['available']; ?></label></span>
                </div>
                <table class="tbl-checkurl" >
                    <thead>
                        <tr>
                            <td class="ui-dialog-titlebar ui-widget-header ui-corner-left">URL</td>
                            <td class="ui-dialog-titlebar ui-widget-header ">Port</td>
                            <td class="ui-dialog-titlebar ui-widget-header ">Check Type</td>
                            <td class="ui-dialog-titlebar ui-widget-header ">Check Status</td>
                            <td class="ui-dialog-titlebar ui-widget-header ui-corner-right" style="text-align: center;"> - </td>
                        </tr>
                    </thead>

                    <tbody>
                        <?php

                            $chkUrl = get_cust_url();
                            if(count($chkUrl)){
                                foreach($chkUrl as $data){
                                    echo "
                                        <tr>
                                        <td>{$data['url']}</td>
                                        <td>{$data['port']}</td>
                                        <td>{$data['check_type']}</td>
                                        <td>{$data['status']}</td>".
                                        '<td><img title="EDIT" class="edit_url" alt="'.$data['id'].'" src="images/edit.jpg" width="24" height="20"  style="cursor:pointer;"/>
                                        <img title="DELETE" class="delete_url" alt="'.$data['id'].'" src="images/delete.jpg" width="24" height="20" style="cursor:pointer;margin-left:2px;"/></td>'.
                                        "</tr>";
                                }
                            }else{
                        ?>
                            <tr><td colspan="5" class="no-data">No Data</td></tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>

            <div class="frmCheckURL">
                <input type="hidden" name="uid" />
                <div class="ui-widget err-cont" style="display:none;">
			<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
				<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>
                                    <strong>Error:</strong><label class="err-msg" style="padding-left:5px;"></label></p>
			</div>
		</div>
                
                <ul>
                    <li>
                        <label style="padding-right:30px;">Check URL</label>
                        <input type="text" name="check_url" size="40" />
                        <span style="color: #ff0000;font-size:11px;"><i>ex.(http://www.google.com)</i></span>
                    </li>
                    <li>
                        <label style="padding-right:25px;">Check Type</label>
                        <select name="check_type" id="check_type">
                            <option value="http">HTTP</option>
                            <option value="smtp">SMTP</option>
                            <option value="imap">IMAP</option>
                            <option value="pop3">POP3</option>
                            <option value="ssmtp">SSMTP</option>
                            <option value="ftp">FTP</option>
                            <option value="sftp">SFTP</option>
                        </select>
                    </li>
                    <li>
                        <label style="padding-right:30px;">Check Port</label>
                        <input type="text" name="check_port" size="20" value="80" />
                    </li>
                    <li>
                        <label style="padding-right:20px;">Check Status</label>
                        <input type="radio" name="check_status" checked="checked" value="active" />Active
                        <input type="radio" name="check_status" value="deactivated" />Deactivate
                    </li>
                    <li>
                        <br /><br />
                        Perform check after the changes ?
                        <input type="checkbox" name="check_now" checked="checked" value="check_now"/>
                    </li>
                </ul>
            </div>
        </div>

        <div class="content-box">
            <div class="box-title">
                <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" style="padding:10px;"> <h3>Notifications Settings</h3> </div>
                <p>
                    Setup this settings to notify you the report of your URL. We can notify you through
                    SMS , EMAIL or even CALL. 
                </p>
            </div>
            <div style="margin-left:50px; margin-top: 20px; padding: 0pt 0.7em;width:500px;display:none;" class="show_msg ui-state-highlight ui-corner-all">
				<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
				<strong class="msg-notify">....</strong> </p>
            </div>

            <?php
                $data = fetch_data_notify();

                $check_period   = $data[1];
                $summary        = $data[2];
                $email          = $data[3];
                $sms            = $data[4];
                $call           = $data[5];
                
                $chk = explode("|",$data[6]);
                foreach($chk as $c) {
                        if($c == "E")
                                $checked["email"] = "checked";
                        elseif($c == "P")
                                $checked["call"] = "checked";
                        elseif($c == "S")
                                $checked["sms"] = "checked";
                        elseif($c == "N")
                                $checked["web"] = "checked";
                }
            ?>
            <div class="notify-settings ">
                <div class="notify-cycle">
                    <ul>
                        <li >
                            <label style="padding-right:38px;">Check Cycle</label>
                            <input type="input" name="check_period" value="<?php echo @$check_period; ?>" /><label> minutes</label>
                        </li>
                        <li>
                            <label>Summary Delivery</label>
                            <input type="input" name="summary" value="<?php echo @$summary; ?>" /><label> days</label>
                        </li>
                    </ul>
                </div>
                    
                <div class="notify-config">
                    <ul class="n-cat">
                        <li><input type="checkbox" name="notify[]" value="email" <?php echo @$checked["email"]; ?> /> Email
                            <ul class="n-sec">
                                <li>
                                    <label>Email Address</label>
                                    <input type="input" name="email" value="<?php echo @$email; ?>" />
                                </li>
                            </ul>
                        </li>
                        <li><input type="checkbox" name="notify[]" value="sms" <?php echo @$checked["sms"]; ?> /> SMS
                            <ul class="n-sec">
                                <li>
                                    <label>SMS Number</label>
                                    <input type="input" name="sms" value="<?php echo @$sms; ?>" />
                                </li>
                            </ul>
                        </li>

                        <li><input type="checkbox" name="notify[]" value="call" <?php echo @$checked["call"]; ?> />CALL
                            <ul class="n-sec" style="width:700px;">
                                <li style="float:left;padding-left:40px;">
                                    <label>Phone Number</label>                                    
                                    +<input type="input" name="phonenum" value="<?php echo @$call; ?>" />                                    
                                </li>
                                <li style="float:left;">
                                    <i style="font-size:11px;"> phone number should include country code, i.e.,+12037896654</i>
                                </li>
                            </ul>
                        </li>
                        <li style="clear:both;"><input type="checkbox" name="notify[]" value="web" <?php echo @$checked["web"]; ?> />Web
                        </li>
                   </ul>
                </div>

            </div>

            <div style="padding-left:15px;">
                <button type="button" class="save_changes ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    <span class="ui-button-text">Save Changes</span>
                </button>
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