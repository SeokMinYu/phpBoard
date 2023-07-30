<?php
	include "DBconnect.php";
	include "login_check.php";

	$ck = $_POST['ck'];

	for($i=0 ; $i < count($ck) ; $i++){
		
		$listdel = mysqli_query($connect,"delete from board17 where idx and parentsidx='".$ck[$i]."'");
		$filedel = mysqli_query($connect,"delete from upload17 where idx='".$ck[$i]."'");
	}
?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=list.php" />