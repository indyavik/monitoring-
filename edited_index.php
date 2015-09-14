<?php
session_start();
$bool = FALSE;
#$_SESSION["base_url"] = "http://184.106.131.46/serverMonitoring/monitoring";
if(isset($_POST["submit"])) {
	
	include("dbconf.php");
	include("frontend_func.php");
	$bool = login($_POST["uname"],$_POST["pword"]);
        if($boo ==false){
            $_SESSION["error"] = true;
        }
}

if($_SESSION['logged_in'] != true) {	
	header("Location: home.php");
} else {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="google-site-verification" content="Ds2Yv-Ju-TZVGRg_PImnjrd7D1nYZ4Nb3lqPXTrDWXE" />
<meta http-equiv="Content-Language" content="en-us" />

<title>CheckWoo | HOME </title>
<meta name="description" content="" />
<meta name="keywords" content="note taking, note taking software, notes, mobile phone snapshots, camera phone, image search, image recognition, handwriting recognition, digital ink, ink notes, memory, remember, life capture, memory capture, productivity software, getting things done, gtd, memory assistant, personal memory assistant" />

<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link href="" rel="Shortcut Icon" type="image/x-icon" />

 </head>
 <body>


<script type="text/javascript">
    $(document).ready(function(){
        $.get('form.php',{country:''},function(data){
            //$("#country").append(data);
        });

        $("#submit").click(function(){
            var chk = false;

            $("input").parent().find('label').remove();
            
            if($("#fname").val() == ''){$("#fname").parent().append('<label style="color:red;font-size:10px;">* required</label>');chk = true;}
            if($("#lname").val() == ''){$("#lname").parent().append('<label style="color:red;font-size:10px;">* required</label>');chk = true;}
            if($("#email").val() == ''){$("#email").parent().append('<label style="color:red;font-size:10px;">* required</label>');chk = true;}
            if($("#pass").val() == ''){$("#pass").parent().append('<label style="color:red;font-size:10px;">* required</label>');chk = true;}

            if(chk){
                $("#fname").focus();
                return false;
            }

            $.post('form.php',{
                email: $("#email").val(),
                email2: $("#email").val(),
                pass: $("#pass").val(),
                pass2: $("#pass").val(),
                fname: $("#fname").val(),
                lname: $("#lname").val(),
                country: $("#country").val(),
                submit: 'submit'
            },function(data){
                if($.trim(data) == 'ok'){
                    $.post('index.php',{submit:'submit',uname:$("#email").val(),pword:$("#pass").val()},function(data){
                        window.location = 'settings.php?n=1';
                    });
                }else{
                    alert(data);
                }
            })
        });

        $("input").click(function(){
            $(this).parent().find('label').remove();
        });

        $("a").click(function(e){
            var href = $(this).attr('href');

           if(href == 'login'){
             e.preventDefault();
             $(".blogin").show();$(".slogin").hide();
             $("input[name='uname']").focus();
             $(".panel").show();
             $(".regis").hide();
           }

           if(href == 'free'){
               e.preventDefault();
               $(".panel").hide();
               $(".regis").show();
               $("#fname").focus();
               $(".blogin").hide();$(".slogin").show();$(".error").html('').hide();
           }

           if(href=='back'){
               e.preventDefault();
               $(".panel").show();
               $(".regis").hide();
               $(".blogin").hide();$(".slogin").show();$(".error").html('').hide();
           }
        });
    });
</script>


   <div class="wrapper">
        <div class="header">
            <div class="header-left"></div>
            <div class="header-right slogin" style="display:block;">
                <div>
                <span><a href="login">Login</a></span>
                <span>|</span>                
                <span><a href="free">Signup</a></span>
                </div>
                <?php if(@$_SESSION["error"]) { unset($_SESSION["error"]); ?>
                    <div class="error">
                        Username and Password doesn't match.
                    </div>
                <?php } ?>

            </div>
            
            <div class="header-right blogin" style="display:none;font-size:12px;">
                <form action="index.php" method="post" style="margin:0px;padding:0px;">
                    <span><a>Email : </a><input type="text" name="uname" style="width:173px;"/></span><br />
                    <span><a>Password : </a> <input type="password" name="pword" style="width:173px;"/></span><br />
                    <span><input type="submit" name="submit" value="Login"/></span>
                </form>
            </div>


            <div class="clear"></div>

            <div class="header-bottom">
                <div class="header-bottom-img">
                    <img alt="Image" src="images/header-img-left.png" />
                </div>
                <div class="header-bottom-text">
                    <span>We will even call you </span> <br />
                    <p>Recieve SMS notification, and have<br />
                        us call you if your site/service<br />
                        continues to be down.
                    </p>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="content">
            <div class="content-left">
                <p>
                    " We May wake <br />
                    you up..sometime. <br /> But you can sleep <br /> well knowing we<br />
                    are awake all the<br />
                    time "
                </p>
            </div>

            <div class="content-panel">
                <div style="float:left;display:none;" class="regis">
                    <div class="content-panel-box" style="height:200px;width:400px;padding-right: 20px;padding-top:10px; ">
                        <table>
                            <tr><td>First Name</td><td> : <input style="width:173px;" type="text" id="fname" /></td></tr>
                            <tr><td>Last Name </td><td> : <input style="width:173px;" type="text" id="lname" /></</td></tr>
                            <tr><td>Email  </td><td> : <input style="width:173px;" type="text" id="email" /></td></tr>
                            <tr><td>Password </td><td> : <input style="width:173px;" type="password" id="pass" /></td></tr>
                            <tr><td>Country </td> <td>: <?php include('html/country.html') ?></select></td></tr>
                            <tr><td><input  type="button" id="submit" value="Submit" /></td></tr>
                        </table>
                    </div>
                    <div class="panel-signup">
                        <img alt="arrow" src="images/back.png" /> <a href="back" style="font-size:14px;">go back</a>
                    </div>
                </div>

                <div style="float:left;" class="panel">
                    <div class="content-panel-box">
                        <div class="panel-head">Basic</div>
                        <div class="panel-content"></div>
                        <div>
                            <p>
                                Up to 5 site or check <br /><br />
                                25 sms / m <br /><br />
                                5 calls / m
                            </p>
                        </div>
                        <div class="panel-bottom">
                            $ 5.99 / m
                        </div>
                    </div>
                    <div class="panel-signup">
                        <img alt="arrow" src="images/arrow.png" /> <a href="">Signup</a>
                    </div>
                </div>

                <div style="float:left;" class="panel">
                    <div class="content-panel-box">
                        <div class="panel-head">Premium</div>
                        <div class="panel-content"></div>
                        <div>
                            <p>
                                Up to 50 site or check <br /><br />
                                100 sms / m <br /><br />
                                30 calls / m
                            </p>
                        </div>
                        <div class="panel-bottom">
                            $ 25.99 / m
                        </div>
                    </div>
                    <div class="panel-signup">
                        <img alt="arrow" src="images/arrow.png" /> <a href="">Signup</a>
                    </div>
                </div>

                <div style="float:left;">
                    <div class="content-panel-box">
                        <div class="panel-head">Free</div>
                        <div class="panel-content"></div>
                        <div>
                            <p>
                                Up to 1 site or check <br /><br />
                                25 sms / m <br /><br />
                                5 calls / m
                            </p>
                        </div>
                        <div class="panel-bottom">
                            FREE
                        </div>
                    </div>
                    <div class="panel-signup">
                        <img alt="arrow" src="images/arrow.png" /> <a href="free">Signup</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="footer">
            <div><a href="">About Us</a> | <a href="">Terms and Condition</a> | <a href="">Services</a>
                <br /> <br />
                <span>Copyright &copy Vestige System</span>
            </div>

        </div>
        <div class="payment">
            <img src="images/paypal.png" alt="payment" />
        </div>
</div>
  </body>
</html>

<?php } ?>
