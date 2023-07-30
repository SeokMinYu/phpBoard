<?php
	include "boardDB.php";

	$bno = $_REQUSET['idx'];
	$pw = $_REQUSET['pw'];
	$del = mq("select pw from board where idx=$bno;");

	if($pw == $del){
		$sql = mq("delete from board where idx='$bno';");
	}
	else{
		echo "<script>
    alert('비밀번호가 틀립니다.');
    history.back();
    </script>";
    exit;
	}

?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv='refreh' content='1; url=list.php' />