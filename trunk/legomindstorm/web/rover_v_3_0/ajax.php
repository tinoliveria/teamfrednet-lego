<?php
include("config.php");
//ajax.php
if(isset($_GET['message'])){
	
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
	//update last online time
	$sql = "UPDATE `users` SET `last`=" . date("U")." WHERE `ID`='{$_SESSION['user_ID']}'";
	$result = mysql_query($sql);
	//Check user
	$sql = "SELECT * FROM `users` WHERE `ID`=$who_ID";
	$result = mysql_query($sql);
	$row = mysql_fetch_array( $result );
	if(!($row['start_control'] > (time()-$user_offline_time) && $row['start_control'] < time()) && $type == "cmd")
	{
		echo "Your aren't allow to summit commands.<br />To get control time goto: <a onclick=\"top.frames['Iframe_con'].location.href='ajax.php?gettime';\" href=\"\">Claim your time</a><br />\n";
		die();
		//echo "Work around, you are allow to summit commands<br />\n";
	}
	//make sql
	$mes = htmlentities(substr($message,0,150), ENT_QUOTES);
	$sql = "INSERT `log_current_session` SET `type`='$type', `when`='$time', `message`='".$mes."', `who_ID`='$who_ID'";
	mysql_query($sql) or die(mysql_error() . "SQL: $sql");
	//in to RAM
	}
if(isset($_GET['update'])){
	
	//echo ".";

	
	//check max every 1 sec in db
	


		//update
		if(isset($_SESSION['last_time_check'])){
		$last_id = $_SESSION['last_time_check'];
		if($last_id > 10000000){
		$sql = "SELECT * FROM `log_current_session` ORDER BY `log_current_session`.`ID`  DESC LIMIT 0,1";
			$result = mysql_query($sql);
		$row = mysql_fetch_array( $result );
		
		$last_id = $row[0];
		$_SESSION['last_time_check'] = $last_id;
		}
		}else{
			$sql = "SELECT * FROM `log_current_session` ORDER BY `log_current_session`.`ID`  DESC LIMIT 0,1";
			$result = mysql_query($sql);
		$row = mysql_fetch_array( $result );
		
		$last_id = $row[0];
		$_SESSION['last_time_check'] = $last_id;
		}
		//$last_id = 5000;//remove this
		$now = time()+microtime()-0.5;
		//fixe this(link with user
		$sql = "SELECT * FROM `log_current_session`,`users` WHERE `log_current_session`.`who_ID`=`users`.`ID` AND `log_current_session`.`ID` > $last_id AND `when` < $now ORDER BY `log_current_session`.`ID` DESC";
		//echo $sql;
		//echo $sql;
		//echo mysql_error();
		//die();
		$result = mysql_query($sql);
		while($row = mysql_fetch_array( $result )){
			if((($row['status'] == "") && ($row['when']> $now-10) && ($row['type'] == "cmd"))) {
				break;
			}
			//print_r($row);
			//$database[$database['count']] =  date("H:i:s ") . $row['nickname'] . ": " . $row['message'] . " <strong>Result: " . $row['status']."</strong><br />\n";
			//$database['count']++;
			echo date("H:i:s ",$row['when']) . $row['nickname'] . ": " . $row['message'];
			if($row['type'] == 'cmd'){
				echo  " <strong>Result: " . $row['status']."</strong>";
			}
			
			if($_SESSION['last_time_check'] < $row[0]){
			$_SESSION['last_time_check'] =  $row[0];
			}
			
			echo "<br />\n";
		}
		

		 
	}
if(isset($_GET['online'])){
	//check if someone has acces
	
	
		//write down he doning it
		
		$sql = "SELECT * FROM `users` WHERE `last`>".(time()-$user_offline_time) . " AND `start_control` > ". (time()-$user_control_time) ."";
		//echo $sql;
		$result = mysql_query($sql);
			
		if(mysql_num_rows($result)==0){
		
			//select new controler
			//get id
			
			//to do: add check for online
			//$sql = "SELECT * FROM `users` WHERE `last`>".(time()-$user_offline_time) . " AND `end_control` != 0 ORDER BY `end_control` ASC LIMIT 0,15";
			$sql = "SELECT * FROM `users` WHERE `last`>".(time()-$user_offline_time) . " AND `start_control` = 0 ORDER BY `end_control` ASC LIMIT 0,1";
			//echo $sql;
			//die();
			$result = mysql_query($sql) or die($sql ."<br />\n". mysql_error());
			$row = mysql_fetch_array( $result );
			$wait = mysql_num_rows($result);
			//print_r($row);
			if(count($row)>0){
			 //to do: based on the number waiting set control time
			 $sql = "UPDATE `users` SET `start_control`=".time()." WHERE `ID`={$row['ID']}";
			 mysql_query($sql);
			 //echo date("H:i:s ") . "system: {$row['nickname']} is now in control.<br />\n";
			}
		}
		

	?>
<table>
	<tr>
		<td colspan="2"><strong>Who is online?</strong></td>
	</tr>
	<?php
	//build wait list
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
	$wait[$row['ID']]=$count;
}
	}
	$sql = "SELECT * FROM `users` WHERE `last`>".(time()-$user_offline_time) . " ORDER BY `ID` DESC";
	$result = mysql_query($sql);
	$count = 0;
	while($row = mysql_fetch_array( $result )){
		$count++;
		?>
	<tr>
		<td><img src="200px-User_icon_2.svg.png" width="50" height="50" /></td>
		<td><span class="style1"><strong><?php echo $row['nickname']; ?></strong></span><br />
		<?php
		if($row['start_control'] > (time()-$user_control_time) && $row['start_control'] < time())
		{
			$seconds = ($row['start_control']-time()+$user_control_time);
		$mins = floor ($seconds / 60);
        $secs = $seconds % 60;
       	if(strlen($secs) == 1){
       		$secs = "0" . $secs;
       	}
		if(strlen($mins) == 1){
       		$mins = "0" . $mins;
       	}
		
        
			echo "This user is in control(remaing: $mins:$secs)";
		}
		if(isset($wait[$row['ID']])){
			echo "Wait for {$wait[$row['ID']]} persone before control.";
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
	<?php echo $user_info['name']; ?><br />
	<input type="button" value="logout" onclick="logout();"/>
	<?php
}
if(isset($_GET['logout'])){
	$sql = "UPDATE `users` SET `last`=".(time()-$user_offline_time)." WHERE `ID`='{$_SESSION['user_ID']}'";
	mysql_query($sql);
	unset($_SESSION['user_ID']);
	unset($_SESSION['nickname']);
	?>
Your are now log out.
	
	<?php
}
if(isset($_GET['rank'])){
	//update last online time
	if(isset($_SESSION['user_ID'])){
	$sql = "UPDATE `users` SET `last`=" . date("U")." WHERE `ID`='{$_SESSION['user_ID']}'";
	$result = mysql_query($sql);
	}
	$sql = "SELECT * FROM `result` ORDER BY `result`.`point` DESC LIMIT 0, 5 ";
	$result = mysql_query($sql) or die(mysql_error() . "SQL: $sql");
	while($row = mysql_fetch_array( $result )){
		echo "<li>{$row['nickname']} {$row['point']}</li>\n";
	}
}
if(isset($_GET['gettime'])){
	$sql = "SELECT * FROM `users` ORDER BY  `users`.`end_control` DESC LIMIT 0, 1";
	$result = mysql_query($sql) or die(mysql_error() . "SQL: $sql");
	$row = mysql_fetch_array( $result );
	//print_r($row);
	
	//if(count($row)<2){
		$end_control = $row['end_control']+1;
	//}else{
		//$end_control = 10;
	//}
	
	//who
	if(isset($_SESSION['user_ID'])){
		if($_SESSION['user_ID'] == 0){
			$who_ID  = 0 ;
		$_SESSION['user_ID'] = $who_ID;
		echo "<script>alert('As guest you aren\'t allowed to claim your control. Please give up a nickname');</script>";
		die();
		}else{
		$who_ID = $_SESSION['user_ID'];
		}
	}else{
		$who_ID  = 0 ;
		$_SESSION['user_ID'] = $who_ID;
		echo "<script>alert('As guest you aren\'t allowed to claim your control. Please give up a nickname');</script>";
		die();
	}
	$sql = "UPDATE `users` SET `start_control`=0, `end_control`=$end_control WHERE `ID`=$who_ID";
	mysql_query($sql);
	echo "<script>alert('Your reqeust is add to queqe.');</script>";

}
if(isset($_GET['givetime'])){
	
	//who
	if(isset($_SESSION['user_ID'])){
		$who_ID = $_SESSION['user_ID'];
	}else{
		$who_ID  = 0 ;
		$_SESSION['user_ID'] = $who_ID;
	}
	$sql = "UPDATE `users` SET `start_control`=10, `end_control`=0 WHERE `ID`=$who_ID";
	mysql_query($sql);
	echo "<script>alert('Your reqeust is remove from queqe.');window.location.href='index.php#file_mission_control';</script>";

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

if(isset($_GET['clip'])){
	function main_player($id){

		?>

<object id="Player" width="400" height="400" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param name="movie" value="http://static.livestream.com/grid/PlayerV2.swf?channel=<?php echo $id; ?>&layout=playerEmbedDefault&backgroundColor=0xffffff&backgroundAlpha=1&backgroundGradientStrength=0&chromeColor=0x000000&headerBarGlossEnabled=true&controlBarGlossEnabled=true&chatInputGlossEnabled=false&uiWhite=true&uiAlpha=0.5&uiSelectedAlpha=1&dropShadowEnabled=true&dropShadowHorizontalDistance=10&dropShadowVerticalDistance=10&paddingLeft=10&paddingRight=10&paddingTop=10&paddingBottom=10&cornerRadius=3&backToDirectoryURL=null&bannerURL=null&bannerText=null&bannerWidth=320&bannerHeight=50&showViewers=true&embedEnabled=true&chatEnabled=true&onDemandEnabled=true&programGuideEnabled=false&fullScreenEnabled=true&reportAbuseEnabled=false&gridEnabled=false&initialIsOn=true&initialIsMute=false&initialVolume=10&contentId=null&initThumbUrl=null&playeraspectwidth=4&playeraspectheight=3&mogulusLogoEnabled=true"/><param name="allowFullScreen" value="true"/><param name="bgcolor" value="#ffffff"/><param name="wmode" value="window"/> <embed name="Player" src="http://static.livestream.com/grid/PlayerV2.swf?channel=<?php echo $id; ?>&layout=playerEmbedDefault&backgroundColor=0xffffff&backgroundAlpha=1&backgroundGradientStrength=0&chromeColor=0x000000&headerBarGlossEnabled=true&controlBarGlossEnabled=true&chatInputGlossEnabled=false&uiWhite=true&uiAlpha=0.5&uiSelectedAlpha=1&dropShadowEnabled=true&dropShadowHorizontalDistance=10&dropShadowVerticalDistance=10&paddingLeft=10&paddingRight=10&paddingTop=10&paddingBottom=10&cornerRadius=3&backToDirectoryURL=null&bannerURL=null&bannerText=null&bannerWidth=320&bannerHeight=50&showViewers=true&embedEnabled=true&chatEnabled=true&onDemandEnabled=true&programGuideEnabled=false&fullScreenEnabled=true&reportAbuseEnabled=false&gridEnabled=false&initialIsOn=true&initialIsMute=false&initialVolume=10&contentId=null&initThumbUrl=null&playeraspectwidth=4&playeraspectheight=3&mogulusLogoEnabled=true" allowFullScreen="true" type="application/x-shockwave-flash" bgcolor="#ffffff" width="400" height="400" wmode="window" ></embed></object>
		<?php
	}
	main_player($_GET['clip']);
}
if(isset($_GET['sensor'])){
	$sql = "SELECT * FROM `sensors` WHERE 1 ORDER BY `when` DESC LIMIT 0,1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array( $result );
	echo nl2br($row['result']) . "<br />\n";
	echo "Server time: " . date("H:i:s");
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

//rover program
if(isset($_GET['rover_program'])){
	if($_GET['rover_program'] == "new"){
		$sql = "SELECT * FROM `rover_program` WHERE 1 ORDER BY `ID` DESC";
		$result = mysql_query($sql);
		$row = mysql_fetch_array( $result );
		$id = $row['ID']+1;
		$sql = "INSERT `rover_program` SET `ID`=$id";
		mysql_query($sql);
		$_SESSION['id_rover_program'] = $id;
	}elseif($_GET['rover_program'] == $_GET['rover_program']*1 && $_GET['rover_program'] != ""){

		$id = $_GET['rover_program'];
		$_SESSION['id_rover_program'] = $id;
	}else{
		if(isset($_SESSION['id_rover_program'])){
			if($_SESSION['id_rover_program'] != ""){
				$id = $_SESSION['id_rover_program'];
			}else{
				die();
			}
		}else{
			die();
		}

	}
	$sql = "SELECT * FROM `rover_program` WHERE `ID`=$id";
	$result = mysql_query($sql);
	$row = mysql_fetch_array( $result );
	echo "Your Program ID: $id<br />\n";
	?>
<form action="ajax.php?rover_program_commands=<?php echo $id; ?>"
	target="hiddenframe" method="post">
<table width="400" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="400"><textarea name="code" cols="70" rows="25"><?php echo $row['code']; ?></textarea></td>
		</tr>
		<tr><td><input type="submit" name="button" id="button" value="save" /></td></tr>
</table>
</form>

	<?php


}
if(isset($_GET['rover_program_commands'])){
	if(!isset($_SESSION['user_ID'])){
		$_SESSION['user_ID'] = 0;
	}
	$id = $_GET['rover_program_commands'];
	
		//check
		$sql = "SELECT * FROM `rover_program` WHERE `ID`=$id";
		$result = mysql_query($sql);
		$row = mysql_fetch_array( $result );
		if(count($row)>0){
			//update
			$sql = "UPDATE `rover_program` SET `code`='".$_POST['code']."' WHERE `ID`=$id";
		}else{
			//new
			$sql = "INSERT `rover_program` SET `ID`=$id, `code`='".$_POST['code']."'";
		}
		//save
		$result = mysql_query($sql);
		//save to file
		$myFile = $nbc_path . "$id.nbc";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $_POST['code']);
fclose($fh);
	
}
?>