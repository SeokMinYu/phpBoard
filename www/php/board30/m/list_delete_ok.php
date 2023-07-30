<?php

	include "DBconnect.php";
	include "login_check.php";
	
	$bno = $_POST['idx'];
	$pw = $_POST['upw'];
	$pwhash = hash("sha256",$pw);

	$delok ="del";
	$delqry = "update board30 set listdelete='".$delok."', deletetime=now() where idx='".$bno."'";

	if($pwhash == $userPw)	
	{
		$delsql = mysqli_query($connect,$delqry);
	}
	else
	{
		echo "<script>
		alert('비밀번호가 틀립니다.');
		history.back();</script>";
		exit;
	}
?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=list.php" />