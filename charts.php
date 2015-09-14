<?php
session_start();
if($_SESSION['logged_in']) {
    include ("dbconf.php");
    include ("frontend_func.php");
}else{
    header('Location: index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="en-us" />

<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>

<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/home.css" rel="stylesheet" type="text/css" />
<link href="js/highcharts/brown.css" rel="stylesheet" type="text/css" />
<link href="" rel="Shortcut Icon" type="image/x-icon" />

<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="js/highcharts/jquery.js"></script>
<script type="text/javascript" src="js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="js/highcharts/gray.js"></script>
<script type="text/javascript" src="js/charts.php.js"></script>

<script type="text/javascript">

<?php

	$month = '';
	$uptime = '';
	$downtime= '';
	$allTotal = 0;
	$allY = 0;
	$allN = 0;
	
	/*Getting the Date*/

	$sqlM = "SELECT http, timestamp, FROM_UNIXTIME( timestamp, '%b %d %Y' ) , cust_id_num
	FROM test_result
	WHERE `cust_id_num` LIKE '".$_SESSION['cust_id']."%'
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
<?php 

//echo "up: ".$uptime; 
/*
echo "<br/>";
echo "down: ".$downtime; 
echo "<br/>";
print_r($rec);
*/
?>
        <div style="width:965px;margin-bottom:7px;margin-top:10px;">
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
        
 </body>

    
</html>

<?php

	/*
	$sqlU = "SELECT http, timestamp, cust_id_num
	FROM test_result WHERE `cust_id_num` LIKE '%{$_SESSION['cust_id']}%'";
	$resultU = mysql_query($sqlU);
	$limitU = mysql_num_rows($resultU);
	
	for ($u = 0; $u < $limitU; $u++)
	{
		if ($u == 0)
		{
			if ( mysql_result($resultU, $u, 'http') == 'Y' )
			{
				$uptime = "100";
			}
			else 
			{
				$uptime = "0";
			}
		}
		else
		{
			if ( mysql_result($resultU, $u, 'http') == 'Y' )
			{
				$uptime .= ", 100";
			}
			else
			{
				$uptime .= ", 0";
			}
		}
	}


$rec = get_months_report();

$month = '';

    foreach( $rec as $idx => $val)
    {
        if($idx == 0)
        {
            $month = "'".$val."'";
        }
        else
        {
            $month .= ",'".$val."'";
        }
    }

$uptime = '';

    foreach($rec as $idx => $val)
    {
        if($idx == 0)
        {
            $uptime = '100';
        }
        else
        {
            $uptime .= ",".'100'."";
        }
    }

$downtime= '';

    foreach($rec as $idx => $val)
    {
        if($idx == 0)
        {
            $downtime = '0';
        }
        else
        {
            $downtime .= ",".'0'."";
        }
    }
*/
?>