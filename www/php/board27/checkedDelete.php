<?php
	include "DBconnect.php";
	include "login_check.php";

	$ck = $_POST['ck'];
	$memberchk = $_POST['memberchk'];


	if($ck != "")
	{
		for($i=0 ; $i < count($ck) ; $i++)
		{
			
			$listdel = mysqli_query($connect,"delete from board27 where idx and parentsidx='".$ck[$i]."'");
			$filedel = mysqli_query($connect,"delete from upload27 where idx='".$ck[$i]."'");
		}
		echo "<script>alert('삭제되었습니다.');location.href='list.php';</script>";
	}

	if($memberchk != "")
	{

		for($m=0 ; $m < count($memberchk) ; $m++)
		{
			$sql = mysqli_query($connect,"select memberId from member27 where seqno='".$memberchk[$m]."'");
			$row = mysqli_fetch_array($sql);
			$listdel = mysqli_query($connect,"update board27 set listdelete='del' where userId='".$row['memberId']."'");
			$replydel = mysqli_query($connect,"update reply27 set replydelete='del' where userId='".$row['memberId']."'");
			$memberdel = mysqli_query($connect,"delete from member27 where seqno='".$memberchk[$m]."'");
		}
		echo "<script>alert('삭제되었습니다.');location.href='member_list.php';</script>";
	}
?>>