<?php	
	include "DBconnect.php";
	include "login_check.php";

	$seq = $_POST['seqno'];

	$level = $_POST['level'];
	$name = $_POST['newname'];
	$userBirth = $_POST['birthNum'];
	$userArea = $_POST['area'];
	$userSex = $_POST['gender'];
	$Color = $_POST['color'];
	$userColor = implode(",",$Color);
	$userPhone = $_POST['Phone1'].'-'.$_POST['Phone2'].'-'.$_POST['Phone3'];
	$userEmail = $_POST['Email'].'@'.$_POST['Email_sub'];

	if($seq != "")
	{
		$sql = mysqli_query($connect,"update member30 set memberName='".$name."',level='".$level."',modifytime=now(),memberBirth='".$userBirth."',memberArea='".$userArea."',memberSex='".$userSex."',memberColor='".$userColor."',memberPhone='".$userPhone."',memberEmail='".$userEmail."' where seqno='".$seq."'");	

		echo "<script charset='utf-8'>alert('변경 완료되었습니다.');location.href='member_view.php?seqno=$seq';</script>";
	}
		
?>
