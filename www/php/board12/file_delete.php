<?php

	include "DBconnect.php";

	$sqn = $_POST['sqn'];
	$bno = $_POST['idx'];

	$filesql = mysqli_query($connect,"select * from upload12 where seqno='".$sqn."'");
	$row = mysqli_fetch_array($filesql);
	$num_row = mysqli_num_rows($filesql);

	if($num_row > 0)
	{
		if($sqn['sqn'] == $row['seqno']) 
		{
				while(file_exists($row['changename'])) 
				{
				unlink($row['changename']);
				}
		}
		$filesql2 = mysqli_query($connect,"delete from upload12 where seqno='".$sqn."'");
	}

	$filesql3 = mysqli_query($connect,"select * from upload12 where idx='".$bno."'");

	while($row = mysqli_fetch_array($filesql3)) 
	{
		?>
			<?=$row['realname']?>
			<input type='button' onclick='del("<?=$row['seqno']?>")' value='삭제' />
		<?php
	}
?>