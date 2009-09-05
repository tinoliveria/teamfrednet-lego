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
	<?php
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
