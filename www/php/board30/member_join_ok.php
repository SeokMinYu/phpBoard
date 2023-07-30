<?php
	header('Content-Type : text/html; charset=utf-8');

	include "DBconnect.php";


	$id = $_REQUEST["newid"];
	$newpw = $_REQUEST["newpw"];
	$pw = hash("sha256",$newpw);

	$name = $_REQUEST['newname'];
	$memberBirth = $_REQUEST['birthNum'];
	$memberArea = $_REQUEST['area'];
	$memberSex = $_REQUEST['gender'];
	$Color = $_REQUEST['color'];
	$memberColor = implode(",",$Color);

	if ($_REQUEST['Phone1'] != "")
	{
		$memberPhone = $_REQUEST['Phone1'].'-'.$_REQUEST['Phone2'].'-'.$_REQUEST['Phone3'];
	}
	else {
		$memberPhone = $_REQUEST['memberPhone'];
	}

	if ($_REQUEST['Email_sub'] != "")
	{
		$memberEmail = $_REQUEST['Email'].'@'.$_REQUEST['Email_sub'];
	}
	else {
		$memberEmail = $_REQUEST['memberEmail'];
	}

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
		$member = mysqli_query($connect,"select count(*) AS CNT from member30 where memberId='".$id."'");
		$data = mysqli_fetch_array($member);

		if($data[0] == 0)
		{
			$sql = mysqli_query($connect,"insert into member30 
							(memberId,memberPw,memberName,level,createtime,memberBirth,memberArea,memberSex,memberColor,memberPhone,memberEmail) 
							values 
							('".$id."','".$pw."','".$name."','".$lv."',now(),'".$memberBirth."','".$memberArea."','".$memberSex."','".$memberColor."','".$memberPhone."','".$memberEmail."')");
			

			echo "<script>alert('가입이 완료되었습니다.');location.href='login.php';</script>";
		}
		else
		{
			echo "<script>alert('이미 가입한 아이디가 있습니다.');location.href='login.php';</script>";
		}
	}
	else
	{
		echo "<script>alert('가입에 실패하였습니다.');history.back(-1);</script>";
	}

?>