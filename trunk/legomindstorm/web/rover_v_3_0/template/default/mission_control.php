<?php
function create_player_small($id,$clip_id){
	/*
	 ?>
	 <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="120"
	 height="100" id="smPlayer_<?php echo $id; ?>">
	 <param name="flashvars"
		value="autoplay=false&amp;brand=embed&amp;cid=<?php echo $clip_id; ?>" />
		<param name="allowfullscreen" value="true" />
		<param name="allowscriptaccess" value="always" />
		<param name="movie" value="http://www.ustream.tv/flash/live/1/1327508" />
		<embed flashvars="autoplay=false&amp;brand=embed&amp;cid=<?php echo $clip_id; ?>"
		width="120" height="100" allowfullscreen="true"
		allowscriptaccess="always" id="smPlayer_<?php echo $id; ?>" name="utv_n_683154"
		src="http://www.ustream.tv/flash/live/1/<?php echo $clip_id; ?>"
		type="application/x-shockwave-flash" /></object>
		*/
	?>
	<div onclick="clipToMain('<?php echo $clip_id; ?>');">
<img name="img<?php echo $id; ?>" src="http://thumbnail.api.livestream.com/thumbnail?name=<?php echo $clip_id; ?>" width="120"/>
<div id="vidSel2" class="vidButton">Send to Main</div>
	</div>
	
	<?php
}
function main_player($id){
	?>

<object id="Player_<?php echo $id; ?>" width="400" height="400" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param name="movie" value="http://static.livestream.com/grid/PlayerV2.swf?channel=<?php echo $id; ?>&layout=playerEmbedDefault&backgroundColor=0x000000&backgroundAlpha=1&backgroundGradientStrength=0&chromeColor=0x000000&headerBarGlossEnabled=true&controlBarGlossEnabled=true&chatInputGlossEnabled=false&uiWhite=true&uiAlpha=0.5&uiSelectedAlpha=1&dropShadowEnabled=true&dropShadowHorizontalDistance=10&dropShadowVerticalDistance=10&paddingLeft=10&paddingRight=10&paddingTop=10&paddingBottom=10&cornerRadius=3&backToDirectoryURL=null&bannerURL=null&bannerText=null&bannerWidth=320&bannerHeight=50&showViewers=true&embedEnabled=true&chatEnabled=true&onDemandEnabled=true&programGuideEnabled=false&fullScreenEnabled=true&reportAbuseEnabled=false&gridEnabled=false&initialIsOn=true&initialIsMute=false&initialVolume=10&contentId=null&initThumbUrl=null&playeraspectwidth=4&playeraspectheight=3&mogulusLogoEnabled=true"/><param name="allowFullScreen" value="true"/><param name="bgcolor" value="#ffffff"/><param name="wmode" value="window"/> <embed name="Player_<?php echo $id; ?>" src="http://static.livestream.com/grid/PlayerV2.swf?channel=<?php echo $id; ?>&layout=playerEmbedDefault&backgroundColor=0xffffff&backgroundAlpha=1&backgroundGradientStrength=0&chromeColor=0x000000&headerBarGlossEnabled=true&controlBarGlossEnabled=true&chatInputGlossEnabled=false&uiWhite=true&uiAlpha=0.5&uiSelectedAlpha=1&dropShadowEnabled=true&dropShadowHorizontalDistance=10&dropShadowVerticalDistance=10&paddingLeft=10&paddingRight=10&paddingTop=10&paddingBottom=10&cornerRadius=3&backToDirectoryURL=null&bannerURL=null&bannerText=null&bannerWidth=320&bannerHeight=50&showViewers=true&embedEnabled=true&chatEnabled=true&onDemandEnabled=true&programGuideEnabled=false&fullScreenEnabled=true&reportAbuseEnabled=false&gridEnabled=false&initialIsOn=true&initialIsMute=false&initialVolume=10&contentId=null&initThumbUrl=null&playeraspectwidth=4&playeraspectheight=3&mogulusLogoEnabled=true" allowFullScreen="true" type="application/x-shockwave-flash" bgcolor="#ffffff" width="400" height="400" wmode="window" ></embed></object>
<?php 
/*
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="400"
	height="320" id="ID_object">
	<param name="flashvars"
		value="autoplay=true&amp;brand=embed&amp;cid=<?php echo $id; ?>" />
	<param name="allowfullscreen" value="true" />
	<param name="allowscriptaccess" value="always" />
	<param name="movie" value="http://www.ustream.tv/flash/live/1/1327508" />
	<embed
		flashvars="autoplay=true&amp;brand=embed&amp;cid=<?php echo $id; ?>"
		width="400" height="320" allowfullscreen="true"
		allowscriptaccess="always" id="utv864871" name="utv_n_683154"
		src="http://www.ustream.tv/flash/live/1/<?php echo $id; ?>"
		type="application/x-shockwave-flash" /></object>
<a href="http://www.ustream.tv/"
	style="padding: 2px 0px 4px; width: 400px; background: #ffffff; display: block; color: #000000; font-weight: normal; font-size: 10px; text-decoration: underline; text-align: center;"
	target="_blank">Free live streaming by Ustream</a>
	
	*/
}
?>
<table style="width: 750px">
<!-- 
	<tr>

	<?php
	$count = 0;
	foreach($stream_gid as $waarde){
		$count++;
		if($count == 1){
			$main_stream_gid = $waarde;
		}

		?>
		<td><?php //create_player_small($count,$waarde); ?></td>
		<?php
	}
	//include("config.php");
	?>


	</tr> -->

	<tr>
		<td colspan="1">
		<div id="mainPlayer" style="width: 420px;
	overflow: auto;
	height: 410px;"><?php
		foreach($stream_gid as $id){
		echo main_player($id). "<br />";
		} ?>
		
		</div>
		</td>
		<td>
		<table height="100%" width="300">
			<tr>
				<td valign="top"><strong>sensor data:</strong>
				<div id="sensors">Please wait</div>
				</td>
			</tr>
			<tr>
				<td valign="bottom">
				<div id="control_img" style="display: ;">
				<table>
					<tr>
						<td rowspan="2"><img
							onclick="<?php global $command_to_go_left;$x=0; foreach($command_to_go_left as $waarde){ $waarde=str_replace("{degrees}","' + Math.round(distance) + '",$waarde); ?>document.getElementById('input_chat').value='<?php echo $waarde; ?>';post_chat();pausecomp(200);<?php }?>"
							src="template/<?php echo $template_name; ?>/img/directdrive_left.gif" />
						</td>
						<td><img
							onclick="<?php global $command_to_go_forward; foreach($command_to_go_forward as $waarde){ $waarde=str_replace("{degrees}","'+ Math.round(distance) +'",$waarde); ?>document.getElementById('input_chat').value='<?php echo $waarde; ?>';post_chat();pausecomp(200);<?php }?>"
							src="template/<?php echo $template_name; ?>/img/directdrive_forward.gif" />
						</td>
						<td rowspan="2"><img
							onclick="<?php global $command_to_go_right; foreach($command_to_go_right as $waarde){ $waarde=str_replace("{degrees}","'+ Math.round(distance) +'",$waarde); ?>document.getElementById('input_chat').value='<?php echo $waarde; ?>';post_chat();pausecomp(200);<?php }?>"
							src="template/<?php echo $template_name; ?>/img/directdrive_right.gif" />
						</td>
						
					</tr>
					<tr>
						<td><img
							onclick="<?php global $command_to_go_back; foreach($command_to_go_back as $waarde){ $waarde=str_replace("{degrees}","'+ Math.round(distance) +'",$waarde); ?>document.getElementById('input_chat').value='<?php echo $waarde; ?>';post_chat();pausecomp(200);<?php }?>"
							src="template/<?php echo $template_name; ?>/img/directdrive_back.gif" />
				</td>
				
				</tr>
				</table>
				</div>
				</td>
				</tr>
				
				<tr>
				<td colspan="1">
				<input type="button" id="give" onclick="top.frames['Iframe_con'].location.href='ajax.php?givetime';" value="Give up my control time" /><br />
				<input type="button" id="give" onclick="top.frames['Iframe_con'].location.href='ajax.php?gettime';" value="Claim you control time" /><br />
				<input type="text" id="programID" size="1" /><input type="button" value="run pre program" onclick="document.getElementById('input_chat').value='cmd run program ' + document.getElementById('programID').value ;post_chat();" />
				
				<br />
				<input type="text" id="programID2" size="1" /><input type="button" value="download program to slot " onclick="document.getElementById('input_chat').value='cmd download rover program ' + document.getElementById('programID2').value + ' ' + document.getElementById('slot').value ;post_chat();" />
				<select id="slot">
				<option selected value="user_program_1">1</option>
				<?php 
				for($i = 2;$i <  10; $i++){
				?>
<option value="user_program_<?php echo $i; ?>"><?php echo $i; ?></option>
<?php }
?>
</select>
				<input type="button" value="run rover program" onclick="document.getElementById('input_chat').value='cmd run rover program ' + document.getElementById('slot').value ;post_chat();" />
				
				</td>
				
				
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<!-- 
	<tr>
		<td colspan="3">Start time: <input type="button" value="start"
			id="timing" onclick="start_point();" />
		<div id="score"></div>
		</td>
	</tr>
 -->
	<tr>
		<td colspan="3" bgcolor="#CCCCCC" style="border: thin; width: 500px;">
		<div style="overflow: auto; height: 200px; color: #FFFFFF;"
			id="logbook"></div>
		</td>
	</tr>
	<td colspan="3" bgcolor="#AAAAAA" style="border: thin;"><input
		name="input_chat" type="text" id="input_chat" size="50"
		maxlength="240" onKeyDown="if(event.keyCode==13){post_chat();}" /> <input
		type="button" name="button" id="button" value="post"
		onclick="post_chat();" /> <a href="#static_command" target="_blank">How
	to enter commands</a></td>
	</tr>
</table>
<iframe width="0" src="" id="Iframe_con" frameborder="0" heigth="0" name="Iframe_con"></iframe>