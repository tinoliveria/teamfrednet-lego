You can clain in advance some time to have control over the rover.
<br />
Current server time:
<?php echo date("r"); ?>
<br />
Current list of waiting persons:
<ul>
<?php
$sql = "SELECT * FROM `users` WHERE `end_control`>".time() ." ORDER BY `users`.`start_control` ASC";
$result = mysql_query($sql) or die(mysql_error() . "SQL: $sql");
while($row = mysql_fetch_array( $result )){
	echo "<li>{$row['nickname']} is in control from ". date("H:i:s",$row['start_control'])." to ". date("H:i:s",$row['end_control'])."</li>";
}
?>
</ul>
<input
	type="button" value="Claim your time"
	onclick="window.location.href='ajax.php?gettime';" />
