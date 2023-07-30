<?
	include "DBconnect.php";

	$seqno = $_POST['seqno'];

	$sql = mysqli_query($connect,"update T_CURL set result=result+1,createDate=now() where seqno='".$seqno."'");

	$sql = mysqli_query($connect,"SELECT * FROM T_CURL WHERE seqno = '".$seqno."'");
	$row = mysqli_fetch_array($sql);

	echo $row['result'];
?>