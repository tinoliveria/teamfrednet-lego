You have to claim control time in advance.
<br />
Current list of waiting persons:
<ul>
<?php
$sql = "SELECT * FROM `users` WHERE `last`>".(time()-$user_offline_time) . " AND `start_control` > ".(time()-$user_control_time)." AND `start_control` < ".time()." ORDER BY `last` DESC";
	$result = mysql_query($sql) or die(mysql_error() . "SQL: $sql");;
	$count = 0;
	$row = mysql_fetch_array( $result );
	//print_r($row);	
	if(count($row)>2){
$sql = "SELECT * FROM `users` WHERE `last`>".(time()-$user_offline_time) . " AND `end_control` > {$row['end_control']} ORDER BY `end_control` ASC";
$result = mysql_query($sql) or die(mysql_error() . "SQL: $sql");
$count = 0;
while($row = mysql_fetch_array( $result )){
	$count++;
	echo "<li>$count:{$row['nickname']}</li>";
}
	}
?>
</ul>
<input
	type="button" value="Claim your time"
	onclick="window.location.href='ajax.php?gettime';" />
