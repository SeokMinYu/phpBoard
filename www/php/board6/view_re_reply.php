<?php
	include "DBconnect.php";
	include "login_check.php";
	include "levelCheak.php";

	$bno = $_REQUEST['replyidx'];
	$sql = mysqli_query($connect,"select * from reply2 where replyidx='".$bno."'");
	$reply = mysqli_fetch_array($sql);
?>

<!-- 댓글입력 폼 -->
	<div id="rereply">
		<form name="replyWr_<?=$reply['replyidx']?>" method="POST" onsubmit="return rereplyWr(<?=$reply['replyidx']?>);" action="view_re_reply_ok.php">
		<input type="hidden" name="idx" value="<?=$reply['replyidx']?>"/>
		<input type="hidden" name="list_num" value="<?=$reply['list_num']?>"/>
			<div>
				<textarea name="rerecontent" id="rerecontent_<?=$reply['replyidx']?>" style="margin-top:10px; width: 400px; height: 100px;"></textarea>
				<input type="submit" value="등록"/><input type="button" onclick="RReplyNo(<?=$reply['replyidx']?>);" value="취소"/>
			</div>
		</form>
	</div>