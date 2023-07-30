<?php
	header('Content-Type: text/html; charset=utf-8');
	session_start();

	if($_POST['userId'] == "admin" && $_POST['userPw'] == "1111")
	{
		$_SESSION['userId'] = $_POST['userId'];
		$_SESSION['userPw'] = $_POST['userPw'];


		echo "<script>alert('로그인성공!');location.href='admin.php'</script>";
	}else {
		echo "<script>alert('아이디 또는 비밀번호가 다릅니다.');location.href='login.php';</script>";
	}

?>
