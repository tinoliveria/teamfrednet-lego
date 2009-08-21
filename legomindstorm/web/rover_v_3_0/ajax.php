<?php
include("config.php");
//ajax.php
if(isset($_GET['message'])){
// Create 100 byte shared memory block with system id of 0xff3
$shm_id = shmop_open($ram_key, "c", 0644, $ram_size);
if (!$shm_id) {
    echo "Couldn't create shared memory segment\n";
}
// Get shared memory block's size
$shm_size = shmop_size($shm_id);
// Now lets read 
$my_string = shmop_read($shm_id, 0, $shm_size);
if (!$my_string) {
    echo "Couldn't read from shared memory block\n";
}
//contert it
if($my_string{0}!= '\0' ){
	
$database = unserialize($my_string);
//echo "From Ram:";
//print_r($database);
}else{
$database = array();
$database['lowcount'] = 1;
$database['count'] = 1;
}
	//check type message
	$message = $_GET['message'];
	if(substr($message,0,3)=="cmd"){
		$type = "cmd";
	}else{
		$type = "chat";
	}
	//set time 1234567890.1232
	$time = date("U") + microtime();
	if(isset($_GET['delay'])){
		$time += $_GET['delay'];
	}
	//who
	if(isset($_SESSION['user_ID'])){
		$who_ID = $_SESSION['user_ID'];
	}else{
		$who_ID  = 0 ;
		$_SESSION['user_ID'] = $who_ID;
	}
	$sql = "SELECT * FROM `users` WHERE `ID`=$who_ID";
	$result = mysql_query($sql);
	$row = mysql_fetch_array( $result );
	if(!($row['start_control'] < date("U") && $row['end_control'] > date("U")) && $type == "cmd")
	{
		echo "Your aren't allow to summit commands.<br />\n";
		die();
	}
	//make sql
	$mes = htmlentities(substr($message,0,150), ENT_QUOTES);
	$sql = "INSERT `log_current_session` SET `type`='$type', `when`='$time', `message`='".$mes."', `who_ID`='$who_ID'";
	mysql_query($sql) or die(mysql_error() . "SQL: $sql");
	//in to RAM
	if($type == "chat"){	
	$database[$database['count']] =  date("H:i:s") . "{$row['nickname']}: $mes<br />\n";
	$database['count']++;
	
	}
	//update last online time
	$sql = "UPDATE `users` SET `last`=" . date("U")." WHERE `ID`='{$_SESSION['user_ID']}'";
	$result = mysql_query($sql);
	//save ram
$data = serialize($database);
//check size
if((strlen($data)/$ram_size) > 0.80){
	//clean out(remove 10 records)
for($i = $database['lowcount'];($i < ($database['lowcount']+10)) && ($i < ($database['count']+10));$i++){
		unset($database[$i]);
	}
	$database['lowcount'] = $i;
}
$data = serialize($database);
// Lets write a test string into shared memory
$shm_bytes_written = shmop_write($shm_id, $data, 0);

if ($shm_bytes_written != strlen($data)) {
    echo "Couldn't write the entire length of data\n";
}
shmop_close($shm_id);
}
if(isset($_GET['update'])){
	//get RAM
	// Create 100 byte shared memory block with system id of 0xff3
$shm_id = shmop_open($ram_key, "c", 0644, $ram_size);
if (!$shm_id) {
    echo "Couldn't create shared memory segment\n";
}
//$now = (date("U") + microtime());
// Get shared memory block's size
$shm_size = shmop_size($shm_id);
// Now lets read 
$my_string = shmop_read($shm_id, 0, $shm_size);
if (!$my_string) {
    echo "Couldn't read from shared memory block\n";
}
//contert it
//echo ord($my_string{0});
if(ord($my_string{0}) != 0){
	
$database = unserialize($my_string);
//print_r($database);
}else{
$database = array();
$database['count'] = 1;
$database['lowcount'] = 1;
$database['last_time_sql'] = time() + microtime();
}

if(isset($_SESSION['last_time_check'])){
	for($i = $_SESSION['last_time_check'];$i < $database['count'];$i++){
		echo $database[$i];
	}
	$_SESSION['last_time_check'] = $database['count'];
}else{
$_SESSION['last_time_check'] = $database['count'];
}
//check max every 1 sec in db
if(($database['last_time_sql'] + 1)<(time()+microtime())){

	
	//update
	$last_id = $database['last_time_sql'];
	$now = time()+microtime();
	$sql = "SELECT * FROM `log_current_session`,`users` WHERE `log_current_session`.`who_ID`=`users`.`ID` AND `log_current_session`.`when`>=$last_id AND `log_current_session`.`when` < $now AND `log_current_session`.`status`!='' AND `log_current_session`.`type`='cmd' ORDER BY `log_current_session`.`when` DESC";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array( $result )){
		//print_r($row);
		$database[$database['count']] =  date("H:i:s ") . $row['nickname'] . ": " . $row['message'] . " <strong>Result: " . $row['status']."</strong><br />\n";
		$database['count']++;
		//echo date("H:i:s ") . $row['nickname'] . ": " . $row['message'];
		//if($row['type'] == 'cmd'){
		//	echo  " <strong>Result: " . $row['status']."</strong>";
		//}
		//echo "<br />\n";
	}
	$database['last_time_sql'] = $now;
	
   
}
   //save ram
$data = serialize($database);
// Lets write a test string into shared memory
$shm_bytes_written = shmop_write($shm_id, $data, 0);
//echo "used: " . round(strlen($data)/$ram_size*100,2) . "%<br />\n";
if ($shm_bytes_written != strlen($data)) {
  echo "Couldn't write the entire length of data\n";
}
shmop_close($shm_id);
//$last_id = (date("U") + microtime());
   //echo ($now-$last_id). "s <br />\n";
}
if(isset($_GET['online'])){
//check if someone has acces
	//check if now else does this
	if(!file_exists("check.txt")){
		//write down he doning it
		$data = rand();
		$fh = fopen("check.txt", 'w') or die("can't open file");

		fwrite($fh, $data);

		fclose($fh);
		$sql = "SELECT * FROM `users` WHERE `last`>".(time()-$user_offline_time) . " AND `start_control` < ".time()." AND `end_control` > ".time()."";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)==0){
			//select new controler(
			//to do: add check for online
			$sql = "SELECT * FROM `users` WHERE `last`>".(time()-$user_offline_time) . " ORDER BY `end_control` ASC LIMIT 0,15";
			$result = mysql_query($sql);
			$row = mysql_fetch_array( $result );
			$wait = mysql_num_rows($result);
			if(count($row)>1){
			 //to do: based on the number waiting set control time
			 $sql = "UPDATE `users` SET `start_control`=".time().", `end_control`=".(time()+60*5)." WHERE `ID`={$row['ID']}";
			 mysql_query($sql);
			 echo date("H:i:s ") . "system: {$row['nickname']} is now in control.<br />\n";
			}
		}
		//give it free
		unlink("check.txt");
	}

	?>
<table>
	<tr>
		<td colspan="2"><strong>Who is online?</strong></td>
	</tr>
	<?php
	$sql = "SELECT * FROM `users` WHERE `last`>".(time()-$user_offline_time) . " ORDER BY `last` DESC";
	$result = mysql_query($sql);
	$count = 0;
	while($row = mysql_fetch_array( $result )){
		$count++;
		?>
	<tr>
		<td><img src="200px-User_icon_2.svg.png" width="50" height="50" /></td>
		<td><span class="style1"><?php echo $row['nickname']; ?></span><br />
		<?php
		if($row['start_control'] < date("U") && $row['end_control'] > date("U"))
		{
			echo "This user is in control.";
		}
		if((date("U") - $row['end_control']) < 1000 && (date("U") - $row['end_control']) > 0)
		{
			echo "This user was in control.";
		}
		if($row['start_control'] > date("U"))
		{
			echo "This user will be in control in ".(($row['start_control']-date("U"))/60)." minute.";
		}
		?></td>
	</tr>
	<?php
	}
	if($count == 0){
			
		?>
	<tr>
		<td colspan="2">No one is online.</td>
	</tr>
	<?php
	}
	?>
</table>
	<?php
}
if(isset($_GET['nickname'])){
	$sql = "INSERT `users` SET `nickname`='{$_GET['nickname']}', `last`=".time()."";
	mysql_query($sql);
	$_SESSION['user_ID'] = mysql_insert_id();
	$user_info['name'] = $_GET['nickname'];
	$_SESSION['nickname'] = $user_info['name'];
	?>
Hello
	<?php echo $user_info['name']; ?>
	<?php
}
if(isset($_GET['rank'])){
$sql = "SELECT * FROM `result` ORDER BY `result`.`point` DESC LIMIT 0, 5 ";
$result = mysql_query($sql) or die(mysql_error() . "SQL: $sql");
while($row = mysql_fetch_array( $result )){
	echo "<li>{$row['nickname']} {$row['point']}</li>\n";
}
}
if(isset($_GET['rank_day'])){
$sql = "SELECT * FROM `result` WHERE time > ".(time()-3600*24)." ORDER BY `result`.`point` DESC LIMIT 0, 5 ";
$result = mysql_query($sql) or die(mysql_error() . "SQL: $sql");
while($row = mysql_fetch_array( $result )){
	echo "<li>{$row['nickname']} {$row['point']}</li>\n";
}
}
if(isset($_GET['score'])){
// Create 100 byte shared memory block with system id of 0xff3
$shm_id = shmop_open($ram_key, "c", 0644, $ram_size);
if (!$shm_id) {
    echo "Couldn't create shared memory segment\n";
}
// Get shared memory block's size
$shm_size = shmop_size($shm_id);
// Now lets read 
$my_string = shmop_read($shm_id, 0, $shm_size);
if (!$my_string) {
    echo "Couldn't read from shared memory block\n";
}
//contert it
if($my_string{0}!= '\0' ){
	
$database = unserialize($my_string);
//echo "From Ram:";
//print_r($database);
}else{
$database = array();
$database['lowcount'] = 1;
$database['count'] = 1;
}
	$sql2 = "INSERT `result` SET
	`nickname`='{$_SESSION['nickname']}', 
	`point`={$_GET['score']}, 
	`ID_user`={$_SESSION['user_ID']},
	`time`=" . time();
	

	$time = time();
	$message = "{$_SESSION['nickname']} has made a score of {$_GET['score']}.";
	$who_ID = "{$_SESSION['user_ID']}";
	// TODO check high score
	// check over all
	$sql = "SELECT * FROM `result` ORDER BY `result`.`point` DESC LIMIT 0, 1 ";
	$result = mysql_query($sql) or die(mysql_error() . "SQL: $sql");
	$row = mysql_fetch_array( $result );
	if($row['point'] < $_GET['score']){
		$message .= ' This is new high score!';
	}else{
		$sql = "SELECT * FROM `result` WHERE time > ".(time()-3600*24)." ORDER BY `result`.`point` DESC LIMIT 0, 1 ";
		$result = mysql_query($sql) or die(mysql_error() . "SQL: $sql");
		$row = mysql_fetch_array( $result );
		if($row['point'] < $_GET['score']){
			$message .= ' This is new high score for today!';
		}

	}
	//dump it
	mysql_query($sql2) or die(mysql_error() . "SQL: $sql");
	$mes = htmlentities(substr($message,0,150), ENT_QUOTES);
	$sql = "INSERT `log_current_session` SET `type`='chat', `when`='$time', `message`='".$mes."', `who_ID`='$who_ID'";
	mysql_query($sql) or die(mysql_error() . "SQL: $sql");
	$database[$database['count']] =  date("H:i:s ") . "{$row['nickname']}: $mes<br />\n";
	$database['count']++;
//save ram
$data = serialize($database);
// Lets write a test string into shared memory
$shm_bytes_written = shmop_write($shm_id, $data, 0);
//echo "used: " . round(strlen($data)/$ram_size*100,2) . "%<br />\n";
if ($shm_bytes_written != strlen($data)) {
  echo "Couldn't write the entire length of data\n";
}
shmop_close($shm_id);
}
// TODO this!
if(isset($_GET['logout'])){
	if(logout()){
		$sql = "INSERT `users` SET `nickname`='{$_GET['nickname']}', `last`=".time()."";
		mysql_query($sql);
		$_SESSION['user_ID'] = mysql_insert_id();
		?>
Hello
		<?php echo $user_info['name']; ?>
,
<a onclick="logout">Logout</a>
		<?php
	}else{
		echo "Your password/nickname is not good!!!<br />\n";
	}
}
if(isset($_GET['clip'])){
	?>
<div id="mainPlayer"><object width="425" height="344" id="mainPlayer"
	style="margin-left: 1em;">
	<param name="movie"
		value="http://www.youtube.com/v/<?php echo $_GET['clip']; ?>&hl=en&fs=1&"></param>
	<param name="allowFullScreen" value="true"></param>
	<param name="allowscriptaccess" value="always"></param>
	<param name="autoplay" value="true"></param>
	<embed name="mainPlayer"
		src="http://www.youtube.com/v/<?php echo $_GET['clip']; ?>&hl=en&fs=1&"
		type="application/x-shockwave-flash" allowscriptaccess="always"
		allowfullscreen="true" width="425" height="344"></embed> </object> <?php
}
if(isset($_GET['sensor'])){
	$sql = "SELECT * FROM `sensors` WHERE 1 ORDER BY `when` DESC LIMIT 0,1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array( $result );
	echo nl2br($row['result']) . "<br />\n";
	echo "Random: " . rand();
}
if(isset($_GET['pre_program'])){
	if($_GET['pre_program'] == "new"){
		$sql = "SELECT * FROM `pre_program` WHERE 1 GROUP BY `masterID` ORDER BY `masterID` DESC";
		$result = mysql_query($sql);
		$row = mysql_fetch_array( $result );
		$id = $row['masterID']+1;
		$sql = "INSERT `pre_program` SET `masterID`=$id, `innerID`=0";
		mysql_query($sql);
		$_SESSION['id_pre_program'] = $id;
	}elseif($_GET['pre_program'] == $_GET['pre_program']*1 && $_GET['pre_program'] != ""){

		$id = $_GET['pre_program'];
		$_SESSION['id_pre_program'] = $id;
	}else{
		if(isset($_SESSION['id_pre_program'])){
			if($_SESSION['id_pre_program'] != ""){
				$id = $_SESSION['id_pre_program'];
			}else{
				die();
			}
		}else{
			die();
		}

	}
	$sql = "SELECT * FROM `pre_program` WHERE `masterID`=$id";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array( $result )){
		$commands[$row['innerID']] = $row['cmd'];
		$delays[$row['innerID']] = $row['delay'];
	}
	echo "Your Program ID: $id<br />\n";
	?>
<form action="ajax.php?pre_program_commands=<?php echo $id; ?>"
	target="hiddenframe" method="post">
<table width="400" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="49"><a title="in seconde" alt="in seconde">Delay</a></td>
		<td width="351">Command</td>
	</tr>
	<?php
	for($i = 0;$i < 10;$i++){
		$command = "";
		$delay = 0;
		if(isset($commands[$i])){
			$command = $commands[$i];
			$delay = $delays[$i];
		}
		echo "<tr>
            <td><input name=\"delay$i\" type=\"text\" id=\"delay$i\" value=\"$delay\" size=\"4\" maxlength=\"4\" /></td>
            <td><input name=\"command$i\" type=\"text\" id=\"command$i\" value=\"$command\" size=\"50\" maxlength=\"250\" /></td>
          </tr>";
	}
	?>
</table>
<p><label> <input type="submit" name="button" id="button" value="save" />
</label></p>
</form>
</td>
</tr>

</table>
	<?php


}
if(isset($_GET['pre_program_commands'])){
	if(!isset($_SESSION['user_ID'])){
		$_SESSION['user_ID'] = 0;
	}
	$id = $_GET['pre_program_commands'];
	for($i = 0;$i < 10;$i++){
		//check
		$sql = "SELECT * FROM `pre_program` WHERE `masterID`=$id AND `innerID`=$i";
		$result = mysql_query($sql);
		$row = mysql_fetch_array( $result );
		if(count($row)>2){
			//update
			$sql = "UPDATE `pre_program` SET `cmd`='".$_POST['command'.$i]."', `delay`='".$_POST['delay'.$i]."',`who`='{$_SESSION['user_ID']}' WHERE `masterID`=$id AND `innerID`=$i";
		}else{
			//new
			$sql = "INSERT `pre_program` SET `masterID`=$id, `innerID`=$i, `cmd`='".$_POST['command'.$i]."',`who`='{$_SESSION['user_ID']}', `delay`='".$_POST['delay'.$i]."'";
		}
		//save
		$result = mysql_query($sql);
	}
}
?>