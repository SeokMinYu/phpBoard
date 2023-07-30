<?php
	header('Content-Type : text/html; charset=utf-8');

	include "DBconnect.php";


	$id = $_POST["newid"];
	$newpw = $_POST["newpw"];
	$pw = hash("sha256",$newpw);

	$name = $_POST['newname'];
	$userBirth = $_POST['birthNum'];
	$userArea = $_POST['area'];
	$userSex = $_POST['gender'];
	$Color = $_POST['color'];
	$userColor = implode(",",$Color);
	$userPhone = $_POST['Phone1'].'-'.$_POST['Phone2'].'-'.$_POST['Phone3'];
	$userEmail = $_POST['Email'].'@'.$_POST['Email_sub'];

	if($id == "admin")	
	{
		$lv = 10;
	}
	else
	{
		$lv = 1;
	}
	$sql = mysqli_query($connect,"insert into member20 
						(memberId,memberPw,memberName,level,createtime,userBrith,userArea,userSex,userColor,userPhone,userEmail) 
						values
						('".$id."','".$pw."','".$name."','".$lv."',now(),'".$userBirth."','".$userArea."','".$userSex."','".$userColor."','".$userPhone."','".$userEmail."')");

?>
<script type="text/javascript">alert("가입 완료되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=login.php"/>