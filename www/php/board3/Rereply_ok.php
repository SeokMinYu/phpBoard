<?php
	include "boardDB.php";
	include "logindex.php";
	include "levelCheck.php";


	$list_num = $_POST['list_num'];
    $idx = $_POST['idx'];

	$repsql = mysqli_query($db,"select * from reply2 where replyidx='{$idx}'");
	$rep = mysqli_fetch_array($repsql);
	$grporder = $rep['grporder'];
	$replyDepth = $rep['replyDepth'] + 1;
    
	$insertQry = "insert into reply2 (parentsidx,list_num,username,userId,content,replyDepth,grporder,starttime) values('".$idx."','".$list_num."','".$userName."','".$_SESSION['user_id']."','".$_POST['rerecontent']."','".$replyDepth."','".$grporder."',now())";
    $sql = mysqli_query($db,$insertQry);
	echo "<script>location.href='read.php?idx=$list_num';</script>";
?>