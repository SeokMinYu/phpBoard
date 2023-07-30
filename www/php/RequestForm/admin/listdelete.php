<?php

	include "../userDB.php";
	include "logincheck.php";
	
	$ck = $_POST['ck'];

	for($i=0 ; $i < count($ck) ; $i++){
		
		$listdel = mysqli_query($db,"delete from questionForm where idx='{$ck[$i]}'");
	}
?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=adminPage.php" />