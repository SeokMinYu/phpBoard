<?php

	include "../userDB.php";
	include "logincheck.php";

	$idx = $_REQUEST['idx'];
	$replysql = mysqli_query($db,"delete from questionForm where idx='{$idx}'");
	echo "<script>alert('삭제되었습니다.'); location.href='adminPage.php';</script>";

?>