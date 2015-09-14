<?php 

header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	
if($_REQUEST['url']){ ?>

<Response>
	<Say>Hello world error found with the script.</Say>	
</Response>

<?php //<Play><?php //echo $_REQUEST['url']; ?></Play>
	?>
<?php }	?>
