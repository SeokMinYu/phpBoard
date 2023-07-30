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
	$sql = mysqli_query($connect,"insert into member22 
						(memberId,memberPw,memberName,level,createtime,memberBrith,memberArea,memberSex,memberColor,memberPhone,memberEmail) 
						values
						('".$id."','".$pw."','".$name."','".$lv."',now(),'".$memberBirth."','".$memberArea."','".$memberSex."','".$memberColor."','".$memberPhone."','".$memberEmail."')");

?>
<script type="text/javascript">alert("가입 완료되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=login.php"/>