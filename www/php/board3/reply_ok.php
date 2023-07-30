<?php
	include "boardDB.php";
	include "logindex.php";
	include "levelCheck.php";

    $bno = $_POST['idx'];
	$replyDepth = 0;
	$grsql = mysqli_query($db,"SELECT (max(grporder)+1) AS grpmax FROM reply2");
	$grrow = mysqli_fetch_row($grsql);
	$grporder = $grrow[0];
	
    $sql = mysqli_query($db,"insert into reply2 (list_num,username,userId,content,replyDepth,starttime) values('".$bno."','".$userName."','".$_SESSION['user_id']."','".$_POST['rpcontent']."','".$replyDepth."',now())");
	
	$repidx = mysqli_insert_id($db);
	$repsql = mysqli_query($db,"update reply2 set parentsidx='{$repidx}', grporder='{$grporder}' where replyidx='{$repidx}'");

	echo "<script>location.href='read.php?idx=$bno';</script>";
?>