<?php
	include "../userDB.php";
	session_start();
	header('Content-Type : text/html; charset=utf-8');

	$id = $_POST['id'];
	$pw = $_POST['pw'];
	$adminsql = mysqli_query($db,"select * from admin");
	$admin = mysqli_fetch_array($adminsql);

	if($id == $admin['adminID'] && $pw == $admin['adminPW'])
	{
		$_SESSION['admin_id'] = $id;
		$_SESSION['admin_pw'] = $pw;
		echo "<script>location.href='adminPage.php';</script>";

	}
	else
	{
		echo "<script>alert('관리자만 접근 가능합니다.'); location.href='../index.php';</script>";
	}


?>