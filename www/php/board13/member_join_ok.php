<?php
	header('Content-Type : text/html; charset=utf-8');

	include "DBconnect.php";


	$id = $_POST["newid"];
	$newpw = $_POST["newpw"];
	$pw = hash("sha256",$newpw);

	$name = $_POST['newname'];

	if($id == "admin")	
	{
		$lv = 10;
	}
	else
	{
		$lv = 1;
	}
	$sql = mysqli_query($connect,"insert into member13 (memberId,memberPw,memberName,level,createtime) values('".$id."','".$pw."','".$name."','".$lv."',now())");

?>
<script type="text/javascript">alert("가입 완료되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=login.php"/>