<?php

	include "DBconnect.php";
	include "login_check.php";
	
	$idx = $_POST['idx'];
	$likeOk = $_POST['like_Ok'];

	$likesql = mysqli_query($connect,"select * from likeboard where list_idx = '".$idx."'");	
	$like = mysqli_fetch_array($likesql);


	if($likeOk == "1" && $like['memberId'] == "" || $like['memberId'] != $userId)
	{
		$likesql = mysqli_query($connect,"insert into likeboard (list_idx,memberId,liketime) values ('".$idx."','".$userId."',now())");
		echo "<script>location.href='list_view.php?idx=".$idx."';</script>";
	}

	if($likeOk == "0" || $like['memberId'] == $userId)
	{
		$likesql = mysqli_query($connect,"delete from likeboard where list_idx = '".$idx."' and memberId='".$userId."'");
		echo "<script>location.href='list_view.php?idx=".$idx."';</script>";
	}

?>