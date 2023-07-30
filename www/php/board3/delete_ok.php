<?php
	include "boardDB.php";
	
	$bno = $_REQUEST['idx'];
	$pw = $_POST['upw'];
	$pwhash = hash("sha256",$pw);

/*	$sql = mysqli_query($db,"select pw from board2 where idx='".$bno."'");
	$filesql = mysqli_query($db,"select pw from upload2 where idx ='".$bno."'");
	$board = mysqli_fetch_array($sql);
	$file = $_REQUEST['filepatch'];

	if($pwhash == $board['pw']){
		$sql = mysqli_query($db,"delete from board2 where idx='".$bno."'");
		$filesql = mysqli_query($db,"delete from upload2 where idx='".$bno."'");

		unlink($file);
	}
	else{
		echo "<script>
		alert('비밀번호가 틀립니다.');
		history.back();</script>";
    exit;
	} */

	$sql = mysqli_query($db,"select pw from board2 where idx='".$bno."'");
	$board = mysqli_fetch_array($sql);
	$delok ="del";
	$delqry = "update board2 set listdelete='{$delok}', deletetime=now() where idx='{$bno}'";
	if($pwhash == $board['pw'])	
	{
		$delsql = mysqli_query($db,$delqry);
	}
	else{
		echo "<script>
		alert('비밀번호가 틀립니다.');
		history.back();</script>";
    exit;
	}



?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=list.php" />