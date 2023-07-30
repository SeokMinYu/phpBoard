<?php
	include "DBconnect.php";
	include "login_check.php";
	

	$bno = $_POST['replyidx'];
	$sql = mysqli_query($connect,"select * from reply20 where replyidx='".$bno."'");
	$reply = mysqli_fetch_array($sql);
?>

<!-- 댓글입력 폼 -->
	<div id="rereply">
		<form name="replyWr_<?=$reply['replyidx']?>" id="rereply" method="POST" onsubmit="return rereplyWr(<?=$reply['replyidx']?>);" action="view_re_reply_ok.php">
		<input type="hidden" name="idx" value="<?=$reply['replyidx']?>"/>
		<input type="hidden" name="list_num" value="<?=$reply['list_num']?>"/>

			<td align="left" style="margin-top:10px; width: 430px; height: 100px;">
				<textarea name="rerecontent" id="rerecontent_<?=$reply['replyidx']?>" style="width: 420px;"></textarea>

				<input type="submit" value="등록"/>

				<input type="button" onclick="RReplyNo(<?=$reply['replyidx']?>);" value="취소"/>
			</td>
		</form>
	</div>