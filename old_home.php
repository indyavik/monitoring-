<?php
session_start();

if($_SESSION['logged_in']) {
        include ("dbconf.php");
        include ("frontend_func.php");

        if($_GET['conf']){
            get_confirm_account($_GET['conf']);
        }

        get_count_checks();
}else {
       header('Location: index.php');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-language" content="en" />

<title>CheckWoo | HOME </title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<link href="css/home.css" rel="stylesheet" type="text/css" />
<link href="js/jQueryUI/theme/dark-hive/jquery-ui-1.8.11.custom.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="js/jQueryUI/js/jquery-ui-1.8.11.custom.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".confirmed").click(function(){
            $(".confirmation").dialog("open");
        });

        $(".confirmation").dialog({
            title: 'Confirmation',
            autoOpen: false,
            width: 600,
            modal: true,
            resizable: false,
            draggable: false,
            buttons: {"Cancel": function() {
                    $(".confirm-box").val('');
                    $(".msg-confirmed").val('').hide();
                    $(this).dialog("close")}
            }
        });

        $(".confirm-btn").click(function(){
            $.post("controller/ctrSettings.php",{conf:'resend'},function(data){
                $(".msg-confirmed").html($.trim(data)).show().css('color','green');
            });
        });
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
        <h2><?php if(@$_GET['msg']){echo '<span style="color:green;font-size:12px;">'.@$_GET['msg'].'</span>';}?></h2>
        <h3>HOME</h3>
        <div class="content-header">
            <p>
                This is the overview of your account. <strong>ACCOUNT SUMMARY</strong> is at the left side of home page window that provides a summary of your account status. The information displayed in the Account Summary is continuously updated in real time and is always shown in your home page.
                <strong>REPORTS</strong> is at the right side of home page window that provides a summary of URL check report.
            </p>
        </div>

        <div class="content-left">
            <div class="ui-dialog-titlebar ui-widget-header ui-corner-all"> 
                <h3>Account Summary</h3>
            </div>
            <div class="content-summary">
                <ul>
                    <li>Subscription <span>:<strong> <?php echo strtoupper($_SESSION['status']);?></strong></span></li>
                    <li>Checks Avaiable <span>:<strong><?php echo intval($_SESSION['available']);?> </strong></span></li>
                    <li>Checks Used <span>: <strong><?php echo intval($_SESSION['used']);?></strong></span></li>
                    <li>
                        <?php
                            if(get_has_confirm ()){
                        ?>
                        <span>Email Confirmed: <strong>Yes</strong></span>
                        <?php
                            }else{
                        ?>
                        <div class="confirmed ui-state-error ui-corner-all" style="cursor:pointer;padding:5px;color: #ff0000;font-weight: bold;">
                            Confirmation Requires ! <span style="font-size:11px;color: #FFFFFF;">click here</span>
                        </div>
                        <?php }?>
                    </li>
                </ul>
                <ul>
                    <li>Latest Checks <span>: <strong><i><?php echo get_latest_url_checked()?></i></strong></span></li>
                    <li>Latest Notification <span>: <strong><i>None</i></strong></span></li>
                </ul>
                <div class="content-more"><a href="accounts.php">more details ..</a></div>
                <br /> <br />
            </div>

            <div style="border-top: 5px #FFFFFF solid;display:block;"></div>
            <div class="ui-dialog-titlebar ui-widget-header ui-corner-all">
                <h3>Site Map</h3>
            </div>
            <div class="content-sitemap">
                <a href="home.php"><img title="home" alt="home" src="images/s-home.png" width="64" height="64" /></a>
                <a href="settings.php"><img title="settings" alt="settings" src="images/s-settings.png" width="64" height="64" /></a>
                <a href="accounts.php"><img title="accounts" alt="accounts" src="images/s-accounts.png" width="64" height="64" /></a>
                <a href="reports.php"><img title="reports" alt="reports" src="images/s-reports.png" width="64" height="64" /></a>
            </div>
        </div>

        <div class="content-right">
            <div class="ui-dialog-titlebar ui-widget-header ui-corner-all">
                <a href="reports.php"><h3>Reports</h3></a>
            </div>
            <div class="content-report">
                <?php
                    $rec = get_months_report();
                    echo 'Report of <strong>'.$rec[0].'</strong> to <strong>'.end($rec).'</strong>';
                ?>
            </div>
            <iframe SCROLLING=NO src="charts.php?w=580&h=280" width="590px" height="280px" frameborder="0" style="border:0px;padding:10px;" >
            </iframe>
            <div class="content-more">
                <a href="reports.php">more details ..</a>
            </div>
        </div>

        <div class="clear"></div>

        <div class="divider">

        </div>
    </div>
    <div class="clear"></div>

    <div class="confirmation" style="display:none">
        
        <div class="msg-confirmed ui-state-error ui-corner-all" style="cursor:pointer;padding:9px;display:none;">
                
        </div>

        <p style="color:#FFFFFF;text-align: justify;">
            You need to confirm your account through your email  before you can use full features of the application .
            This helps to prevent problems with bad addresses, such as bounced replies or spam.
            You can login and use the pages as normal without confirming your address
            but we require to confirm your account through your email to prevent problems in the future.
            <br /> <br />
            Confirmation is not in your email? You can resend the confirmation here.
            <br /><br />
            Email: <span style="background-color:red;padding:5px;font-weight: bold;"><?php echo $_SESSION['email']; ?></span> <input type="button" class="confirm-btn" value="Resend" />
            <br /><br />
            <u>Thank you</u>,
            <br /><br />
            CheckWoo Admin.
        </p>
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
