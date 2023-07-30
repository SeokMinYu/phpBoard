<?php
	include "DBconnect.php";
	include "login_check.php";

	$bno = $_POST['replyidx'];
	$mode = $_POST['mode'];

	$sql = mysqli_query($connect,"select * from reply15 where replyidx='".$bno."'");
	$reply = mysqli_fetch_array($sql);


?>
<!-- 댓글입력 폼 -->
	<div>
		<form name="replyWr_<?=$bno?>" method="POST" onsubmit="return rewrtie(<?=$bno?>);" action="view_reply_modify_ok.php">
		<input type="hidden" name="idx" value="<?=$bno?>"/>
			<div  id="replymodi">
				<textarea name="rpmodi" id="rpmodi_<?=$bno?>" style="margin-top:10px; width: 400px; height: 100px;"><?=nl2br($reply[content]);?></textarea>
				<input type="submit" value="등록"/><input type="button" onclick="replych(<?=$bno?>);" value="취소"/>
			</div>
		</form>
	</div>