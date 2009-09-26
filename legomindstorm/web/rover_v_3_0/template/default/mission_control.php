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

<div id="vidSel2" class="vidButton"
	onclick="clipToMain('<?php echo $clip_id; ?>');">Send to Main</div>
	<?php
}
function main_player($id){
	?>

<object id="Player" width="400" height="400" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param name="movie" value="http://static.livestream.com/grid/PlayerV2.swf?channel=<?php echo $id; ?>&layout=playerEmbedDefault&backgroundColor=0xffffff&backgroundAlpha=1&backgroundGradientStrength=0&chromeColor=0x000000&headerBarGlossEnabled=true&controlBarGlossEnabled=true&chatInputGlossEnabled=false&uiWhite=true&uiAlpha=0.5&uiSelectedAlpha=1&dropShadowEnabled=true&dropShadowHorizontalDistance=10&dropShadowVerticalDistance=10&paddingLeft=10&paddingRight=10&paddingTop=10&paddingBottom=10&cornerRadius=3&backToDirectoryURL=null&bannerURL=null&bannerText=null&bannerWidth=320&bannerHeight=50&showViewers=true&embedEnabled=true&chatEnabled=true&onDemandEnabled=true&programGuideEnabled=false&fullScreenEnabled=true&reportAbuseEnabled=false&gridEnabled=false&initialIsOn=true&initialIsMute=false&initialVolume=10&contentId=null&initThumbUrl=null&playeraspectwidth=4&playeraspectheight=3&mogulusLogoEnabled=true"/><param name="allowFullScreen" value="true"/><param name="bgcolor" value="#ffffff"/><param name="wmode" value="window"/> <embed name="Player" src="http://static.livestream.com/grid/PlayerV2.swf?channel=<?php echo $id; ?>&layout=playerEmbedDefault&backgroundColor=0xffffff&backgroundAlpha=1&backgroundGradientStrength=0&chromeColor=0x000000&headerBarGlossEnabled=true&controlBarGlossEnabled=true&chatInputGlossEnabled=false&uiWhite=true&uiAlpha=0.5&uiSelectedAlpha=1&dropShadowEnabled=true&dropShadowHorizontalDistance=10&dropShadowVerticalDistance=10&paddingLeft=10&paddingRight=10&paddingTop=10&paddingBottom=10&cornerRadius=3&backToDirectoryURL=null&bannerURL=null&bannerText=null&bannerWidth=320&bannerHeight=50&showViewers=true&embedEnabled=true&chatEnabled=true&onDemandEnabled=true&programGuideEnabled=false&fullScreenEnabled=true&reportAbuseEnabled=false&gridEnabled=false&initialIsOn=true&initialIsMute=false&initialVolume=10&contentId=null&initThumbUrl=null&playeraspectwidth=4&playeraspectheight=3&mogulusLogoEnabled=true" allowFullScreen="true" type="application/x-shockwave-flash" bgcolor="#ffffff" width="400" height="400" wmode="window" ></embed></object>
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
<table style="width: 85%">
	<tr>

	<?php
	$count = 0;
	foreach($stream_gid as $waarde){
		$count++;
		if($count == 1){
			$main_stream_gid = $waarde;
		}

		?>
		<td><?php create_player_small($count,$waarde); ?></td>
		<?php
	}
	?>


	</tr>

	<tr>
		<td colspan="2">
		<div id="mainPlayer"><?php echo main_player($main_stream_gid); ?></div>
		</td>
		<td>
		<table heigth="100%">
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
							onclick="document.getElementById('input_chat').value='cmd run rover program right_3_sec';post_chat();"
							src="template/<?php echo $template_name; ?>/img/directdrive_right.gif" />
						</td>
						<td><img
							onclick="document.getElementById('input_chat').value='cmd run rover program forward_3_sec';post_chat();"
							src="template/<?php echo $template_name; ?>/img/directdrive_forward.gif" />
						</td>
						<td rowspan="2"><img
							onclick="document.getElementById('input_chat').value='cmd run rover program left_3_sec';post_chat();"
							src="template/<?php echo $template_name; ?>/img/directdrive_left.gif" />
						</td>
					</tr>
					<tr>
						<td><img
							onclick="document.getElementById('input_chat').value='cmd run rover program back_3_sec';post_chat();"
							src="template/<?php echo $template_name; ?>/img/directdrive_back.gif" />
				
				</table>
				</div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">Start time: <input type="button" value="start"
			id="timing" onclick="start_point();" />
		<div id="score"></div>
		</td>
	</tr>

	<tr>
		<td colspan="3" bgcolor="#CCCCCC" style="border: thin;">
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
