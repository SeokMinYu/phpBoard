<?php
	header('Content-Type : text/html; charset=utf-8');

	include "DBconnect.php";


	$id = $_POST["newid"];
	$newpw = $_POST["newpw"];
	$pw = hash("sha256",$newpw);

	$name = $_POST['newname'];
	$memberBirth = $_POST['birthNum'];
	$memberArea = $_POST['area'];
	$memberSex = $_POST['gender'];
	$Color = $_POST['color'];
	$memberColor = implode(",",$Color);
	$memberPhone = $_POST['Phone1'].'-'.$_POST['Phone2'].'-'.$_POST['Phone3'];
	$memberEmail = $_POST['Email'].'@'.$_POST['Email_sub'];

	if($id == "admin")	
	{
		$lv = 10;
	}
	else
	{
		$lv = 1;
	}
	if($id != "" && $newpw != "")
	{
		$sql = mysqli_query($connect,"insert into member29 
							(memberId,memberPw,memberName,level,createtime,memberBirth,memberArea,memberSex,memberColor,memberPhone,memberEmail) 
							values 
							('".$id."','".$pw."','".$name."','".$lv."',now(),'".$memberBirth."','".$memberArea."','".$memberSex."','".$memberColor."','".$memberPhone."','".$memberEmail."')");

		echo "<script>alert('가입이 완료되었습니다.');location.href='login.php';</script>";
	}
	else
	{
		echo "<script>alert('가입에 실패하였습니다.');history.back(-1);</script>";
	}

?>