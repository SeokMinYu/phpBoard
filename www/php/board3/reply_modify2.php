<!doctype html>
<?php
	include "boardDB.php";
	include "logindex.php";
	include "levelCheak.php";

	$bno = $_POST['replyidx'];
	$sql = mysqli_query($db,"select content from reply2 where replyidx='".$bno."'");
	$reply = mysqli_fetch_array($sql);
?>

<?=nl2br($reply[content])?>
