<?php
session_start();

if($_SESSION['logged_in']) {
        include ("dbconf.php");
        include ("frontend_func.php");
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
<!--<link href="css/reports.css" rel="stylesheet" type="text/css" />-->
<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>

<!--HERE!-->

<script type="text/javascript" src="js/highcharts/jquery.js"></script>
<script type="text/javascript" src="js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="js/highcharts/gray.js"></script>
<script type="text/javascript" src="js/charts.php.js"></script>


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
        <h3>REPORTS</h3>
		
		<?php 
			
				$monthd = 0;
				$dayd = 0;
				$yeard =-0;
			
			
		?>
		<form action="reports.php" method="get">
					<table border=0 >
					<tr>
						<td>Date Start</td>	
						<td width=20 ></td>	
						<td>Date End</td>	
						<td></td>
					</tr>
		
					<?php
						if (isset($_GET['dstart_month']))
						{
						?>
							
					<tr>
						<td><?php echo createDatePickerDefined("dstart", $_GET['dstart_month'], $_GET['dstart_day'], $_GET['dstart_year'], '100,45,60'); ?></td>	
						<td width=50 ></td>	
						<td><?php echo createDatePickerDefined("dend", $_GET['dend_month'],$_GET['dend_day'],$_GET['dend_year'], '100,45,60'); ?></td>
						<td><input type="submit" value="Display" /></td>	
					</tr>
							
						<?php
						}
						else
						{
						?>
					<tr>
						<td><?php echo createDatePickerDefined("dstart", $monthd, $dayd, $yeard, '100,45,60'); ?></td>	
						<td width=50 ></td>	
						<td><?php echo createDatePickerDefined("dend", $monthd, $dayd, $yeard, '100,45,60'); ?></td>
						<td><input type="submit" value="View" /></td>	
					</tr>
						
						<?php
							
						}
					?>

				</table>
		</form>
		
        <!--<iframe src="charts.php" width="100%" height="700px" frameborder="0" style="border:0px;" ></iframe>-->
      
     <script type="text/javascript">

<?php

	$month = '';
	$uptime = '';
	$downtime= '';
	$allTotal = 0;
	$allY = 0;
	$allN = 0;
	
	/*Getting the Date*/
	
	if (isset($_GET['dstart_month']))
	{
		
	$dtstart = mktime(0, 0, 0, $_GET['dstart_month'], $_GET['dstart_day'], $_GET['dstart_year']);
	$dtend = mktime(0, 0, 0,$_GET['dend_month'],$_GET['dend_day'],$_GET['dend_year']);
	

	$sqlM = "SELECT http, timestamp, FROM_UNIXTIME( timestamp, '%b %d %Y' ) , cust_id_num
	FROM test_result
	WHERE `cust_id_num` LIKE '".$_SESSION['cust_id']."%' AND timestamp between ".$dtstart." AND ".$dtend."
	GROUP BY FROM_UNIXTIME( timestamp, '%b %d %Y' )";
	$resultM = mysql_query($sqlM);
	$limitM = mysql_num_rows($resultM);

	for ($i = 0; $i < $limitM; $i++)
	{
		if ($i == 0)
		{
			$month = "'".date("M-d-Y", mysql_result($resultM, $i, "timestamp"))."'";
		}
		else
		{
			$month .= ",  '".date("M-d-Y", mysql_result($resultM, $i, "timestamp"))."'";
		}
		
		$sqlY = "SELECT http, timestamp
		FROM test_result
		WHERE http='Y' AND FROM_UNIXTIME( timestamp, '%b %d %Y' ) = '".date("M d Y", mysql_result($resultM, $i, "timestamp"))."' AND timestamp between ".$dtstart." AND ".$dtend."";	
		$resultY = mysql_query($sqlY);
		$limitY = mysql_num_rows($resultY);

		$sqlN = "SELECT http, timestamp
		FROM test_result
		WHERE http='N' AND FROM_UNIXTIME( timestamp, '%b %d %Y' ) = '".date("M d Y", mysql_result($resultM, $i, "timestamp"))."' AND timestamp between ".$dtstart." AND ".$dtend."";	
		$resultN = mysql_query($sqlN);
		$limitN = mysql_num_rows($resultN);
		
		$total = $limitY + $limitN;
		$allTotal = $allTotal +  $total;
		$allY = $allY + $limitY;
		$allN = $allN + $limitN; 
		
		if ($i == 0)
		{
			$uptime .= ($limitY / $total) * 100;
		}
		else
		{
			$uptime .= ", ".($limitY / $total) * 100;
		}
		
	}
	
	$allTotal = number_format(($allY / $allTotal) * 100, 2);
	$allTotalN = 100 - $allTotal;
}
else
{
	
	$sqlM = "SELECT http, timestamp, FROM_UNIXTIME( timestamp, '%b %d %Y' ) , cust_id_num
	FROM test_result
	WHERE `cust_id_num` LIKE '".$_SESSION['cust_id']."%' GROUP BY FROM_UNIXTIME( timestamp, '%b %d %Y' )";
	$resultM = mysql_query($sqlM);
	$limitM = mysql_num_rows($resultM);

	for ($i = 0; $i < $limitM; $i++)
	{
		if ($i == 0)
		{
			$month = "'".date("M-d-Y", mysql_result($resultM, $i, "timestamp"))."'";
		}
		else
		{
			$month .= ",  '".date("M-d-Y", mysql_result($resultM, $i, "timestamp"))."'";
		}
		
		$sqlY = "SELECT http, timestamp
		FROM test_result
		WHERE http='Y' AND FROM_UNIXTIME( timestamp, '%b %d %Y' ) = '".date("M d Y", mysql_result($resultM, $i, "timestamp"))."'";	
		$resultY = mysql_query($sqlY);
		$limitY = mysql_num_rows($resultY);

		$sqlN = "SELECT http, timestamp
		FROM test_result
		WHERE http='N' AND FROM_UNIXTIME( timestamp, '%b %d %Y' ) = '".date("M d Y", mysql_result($resultM, $i, "timestamp"))."'";	
		$resultN = mysql_query($sqlN);
		$limitN = mysql_num_rows($resultN);
		
		$total = $limitY + $limitN;
		$allTotal = $allTotal +  $total;
		$allY = $allY + $limitY;
		$allN = $allN + $limitN; 
		
		if ($i == 0)
		{
			$uptime .= ($limitY / $total) * 100;
		}
		else
		{
			$uptime .= ", ".($limitY / $total) * 100;
		}
		
	}
	
	$allTotal = number_format(($allY / $allTotal) * 100, 2);
	$allTotalN = 100 - $allTotal;
	
}

?>

    var example     = 'area-stacked-percent',theme = 'gray';
    var categ       = [<?php  echo $month; ?>];
    var uptime      = [<?php  echo $uptime; ?>];
   // var downtime    = [<?php  echo $downtime; ?>];
    var chart;
    var url         = '';
    
</script>

</head>
<body>
<div class="clear"></div>
		
        <div style="width:965px;margin-bottom:7px;margin-top:10px;">
        	
        	<?php
        		
        		if($limitM == 0)
        		{
        			echo "<br/><b>No data loaded in the date range!</b>";	
        		}
        	
        	?>
        	
            <div>
                <div id="container" class="highcharts-container" style="width:<?php if(isset($_GET['w'])){echo $_GET['w'];}else{echo '972';}?>px;height:<?php if(isset($_GET['h'])){echo $_GET['h'];}else{echo '500';}?>px;"></div>
            </div>
        </div>
        
        <table border=0>
        	<tr>
        		<td>uptime: </td>
        		<td> <?php echo $allTotal.' &#37;'; ?></td>
        		<td width=50></td>
        		<td>Number of downtimes: </td>
        		<td> <?php echo $allN.' ('.$allTotalN.' &#37;)'; ?></td>
        	</tr>
        </table>
      
        
        <div class="clear"></div>

        <div class="detials">

            <table>
                <thead>
                    <tr><td> Month/Time </td><td> Response</td></tr>
                </thead>
                <tbody>
                    <?php
                        $reports = get_months_report(0);
                        foreach($reports as $idx => $val){
                            echo "<tr><td>{$val['timestamp']}</td><td style='text-align:center;'>{$val['http']}</td>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="clear"></div>

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