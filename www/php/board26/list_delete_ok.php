<?php

	include "DBconnect.php";
	
	$bno = $_POST['idx'];
	$pw = $_POST['upw'];
	$pwhash = hash("sha256",$pw);

	$sql = mysqli_query($connect,"select memberPw from member26 where memberPw='".$pwhash."'");
	$board = mysqli_fetch_array($sql);
	$delok ="del";
	$delqry = "update board26 set listdelete='".$delok."', deletetime=now() where idx='".$bno."'";

	if($pwhash == $board['memberPw'])	
	{
		$delsql = mysqli_query($connect,$delqry);
	}
	else
	{
		echo "<script>
		alert('��й�ȣ�� Ʋ���ϴ�.');
		history.back();</script>";
		exit;
	}
?>
<script type="text/javascript">alert("�����Ǿ����ϴ�.");</script>
<meta http-equiv="refresh" content="0 url=list.php" />