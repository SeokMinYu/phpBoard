<?php
	session_start();

	header('Content-Type : text/html; charset=utf-8');
	
	session_destroy(); 
	
	echo "<script>alert('로그아웃 되었습니다.'); location.href='login.php';</script>";
?>