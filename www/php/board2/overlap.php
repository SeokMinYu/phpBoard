<?php
	include "boardDB.php";
	
	$id = $_POST['newid'];
	$member = mysqli_query($db,"select count(*) AS CNT from member2 where memberId='".$id."'");
	$data = mysqli_fetch_array($member);

	if($data[0] == 0)
	{
		echo 0;
	}
	else
	{
		echo 1;
	}

?>