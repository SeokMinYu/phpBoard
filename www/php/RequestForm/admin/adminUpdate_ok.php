<?php
	header('Content-Type : text/html; charset=utf-8');
	include "../userDB.php";
	session_start();

	$oldpw = $_POST['oldpw'];
	$newpw = $_POST['newpw'];

	if($oldpw != $_SESSION['admin_pw'])
	{
			echo '<script>alert("비밀번호가 다릅니다."); history.back();</script>';
			exit;
	}

	if($newpw == $oldpw)
	{
			echo '<script>alert("변경된 정보가 없습니다.");</script>';
	}
	if($newpw == "")
	{

			echo '<script>alert("변경된 정보가 없습니다.");</script>';
	}
	else
		{	
			$adminsql = mysqli_query($db,"update admin set adminPW='{$newpw}'");
			$_SESSION['admin_pw'] = $newpw;
			echo '<script>alert("변경 완료되었습니다.");</script>';
		}


?>
<meta http-equiv="refresh" content="0 url=adminPage.php"/>