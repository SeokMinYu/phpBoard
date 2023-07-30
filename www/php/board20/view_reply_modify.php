<?php
	include "DBconnect.php";
	include "login_check.php";
	

	$bno = $_POST['replyidx'];

	$sql = mysqli_query($connect,"select * from reply20 where replyidx='".$bno."'");
	$reply = mysqli_fetch_array($sql);

?>
<!-- 댓글입력 폼 -->
	<div style="width: 430px;">
		<form name="replyWr_<?=$bno?>" method="POST" onsubmit="return rewrtie(<?=$bno?>);" action="view_reply_modify_ok.php">
		<input type="hidden" name="idx" value="<?=$bno?>"/>
			<td id="replymodi" align="left" style="margin-top:10px; width: 430px; height: 100px;">
				<textarea name="rpmodi" id="rpmodi_<?=$bno?>" align="left" style="width: 420px;"><?=nl2br($reply[content]);?></textarea> 
			</td>
			<td>
				<input type="submit" value="등록"/>
			</td>
			<td>
				<input type="button" onclick="replych(<?=$bno?>);" value="취소"/>	
			</td>
		</form>
	</div>