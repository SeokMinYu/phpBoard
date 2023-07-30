<?php
	include "DBconnect.php";
	include "login_check.php";


    $bno = $_POST['idx'];
	$replyDepth = 0;
	$grsql = mysqli_query($connect,"SELECT (max(grporder)+1) AS grpmax FROM reply24");
	$grrow = mysqli_fetch_row($grsql);
	$grporder = $grrow[0];
	
    $sql = mysqli_query($connect,"insert into reply24 (list_num,username,userId,content,replyDepth,starttime) values('".$bno."','".$userName."','".$_SESSION['user_id']."','".$_POST['rpcontent']."','".$replyDepth."',now())");
	
	$repidx = mysqli_insert_id($connect);
	$repsql = mysqli_query($connect,"update reply24 set parentsidx='".$repidx."', grporder='".$grporder."' where replyidx='".$repidx."'");

	echo "<script>location.href='list_view.php?idx=$bno';</script>";
?>