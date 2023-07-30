<?php
	header('Content-Type : text/html; charset=utf-8');

	include "boardDB.php";


	$id = $_POST['newid'];
	$pw = $_POST['newpw'];
	$name = $_POST['newname'];

	$lv = 1;

	$sql = mysqli_query($db,"insert into member2 (memberId,memberPw,memberName,level,createtime) values('".$id."','".$pw."','".$name."','".$lv."',now())");

?>
<script type="text/javascript">alert("가입 완료되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=login.php"/>