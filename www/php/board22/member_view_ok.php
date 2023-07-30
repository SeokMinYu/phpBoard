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


	$sql = mysqli_query($connect,"update member22 set level='".$level."',memberName='".$name."',modifytime=now(),userBrith='".$userBirth."',userArea='".$userArea."',userSex='".$userSex."',userColor='".$userColor."',userPhone='".$userPhone."',userEmail='".$userEmail."' where seqno='".$seq."'");	
		
?>
<script type="text/javascript">alert("변경 완료되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=member_view.php?seqno=<?=$seq?>"/>