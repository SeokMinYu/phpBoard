<?php
	include "DBconnect.php";

	$idx = $_POST['idx'];

	$title = $_POST['title'];
	
	$listsql = mysqli_query($connect,"SELECT (max(sortorder)+1) AS maxorder FROM sortable");
	$listrow = mysqli_fetch_row($listsql);
	$listorder = $listrow[0];

	$sql=mysqli_query($connect,"insert into sortable (title,createtime) values('".$title."',now())");
	$bno = mysqli_insert_id($connect);
	$repsql = mysqli_query($connect,"update sortable set sortorder='".$listorder."' where idx='".$bno."'");
				
?>
<script type="text/javascript">alert("글쓰기 완료되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=sortable.php"/>