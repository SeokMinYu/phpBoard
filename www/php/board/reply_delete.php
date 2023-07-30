<?php

	include "boardDB.php"

	$rno = $_POST['rno'];
	$sql = mq("select * from reply where idx='".$rno."'");

	$reply = $sql->fetch_array();

	$bno = $_POST['bno'];
	$sql2 = mq("select * from board where idx='".$bno."'");
	$board = $sql2->fetch_array();

	$pwk = $_POST['pw'];
	$bpw = $reply['pw'];

	if($pwk == $reply['pw']) 
		{
			$sql = mq("delete from reply where idx='".$rno."'"); ?>
			<script type="text/javascript">alert('댓글이 삭제되었습니다.'); location.replace("read.php?idx=<?php echo $board["idx"]; ?>");</script>
	<?php 
	}else{ ?>
		<script type="text/javascript">alert('비밀번호가 틀립니다');history.back();</script>
	<?php } ?>

