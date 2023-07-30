<!doctype html>
<?php
	include "DBconnect.php";
	include "login_check.php";

	$bno = $_POST['replyidx'];
	$sql = mysqli_query($connect,"select content from reply14 where replyidx='".$bno."'");
	$reply = mysqli_fetch_array($sql);
?>
<?=nl2br($reply[content])?>