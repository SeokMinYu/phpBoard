<?php
	include "DBconnect.php";
	include "login_check.php";


	$list_num = $_POST['list_num'];
    $idx = $_POST['idx'];

	$repsql = mysqli_query($connect,"select * from reply15 where replyidx='".$idx."'");
	$rep = mysqli_fetch_array($repsql);
	$grporder = $rep['grporder'];
	$replyDepth = $rep['replyDepth'] + 1;
    
	$insertQry = "insert into reply15 (parentsidx,list_num,username,userId,content,replyDepth,grporder,starttime) values('".$idx."','".$list_num."','".$userName."','".$_SESSION['user_id']."','".$_POST['rerecontent']."','".$replyDepth."','".$grporder."',now())";

    $sql = mysqli_query($connect,$insertQry);

	echo "<script>location.href='list_view.php?idx=$list_num';</script>";
?>