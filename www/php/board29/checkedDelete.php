<?php
	include "DBconnect.php";
	include "login_check.php";

	$ck = $_POST['ck'];
	$memberchk = $_POST['memberchk'];


	if($ck != "")
	{
		for($i=0 ; $i < count($ck) ; $i++)
		{
			
			$listdel = mysqli_query($connect,"delete from board29 where idx and parentsidx='".$ck[$i]."'");
			$filedel = mysqli_query($connect,"delete from upload29 where idx='".$ck[$i]."'");
		}
		echo "<script>alert('삭제되었습니다.');location.href='list.php';</script>";
	}

	if($memberchk != "")
	{

		for($m=0 ; $m < count($memberchk) ; $m++)
		{
			$sql = mysqli_query($connect,"select memberId from member29 where seqno='".$memberchk[$m]."'");
			$row = mysqli_fetch_array($sql);
			$listdel = mysqli_query($connect,"update board29 set listdelete='del' where userId='".$row['memberId']."'");
			$replydel = mysqli_query($connect,"update reply29 set replydelete='del' where userId='".$row['memberId']."'");
			$memberdel = mysqli_query($connect,"delete from member29 where seqno='".$memberchk[$m]."'");
		}
		echo "<script>alert('삭제되었습니다.');location.href='member_list.php';</script>";
	}
?>>