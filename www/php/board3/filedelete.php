<?php

	include "boardDB.php";
	$sqn = $_POST['sqn'];
	$bno = $_POST['idx'];

	$filesql = mysqli_query($db,"select * from upload2 where seqno='".$sqn."'");
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
		$filesql2 = mysqli_query($db,"delete from upload2 where seqno='".$sqn."'");
	}

	$filesql3 = mysqli_query($db,"select * from upload2 where idx='".$bno."'");

		while($row = mysqli_fetch_array($filesql3)) 
		{
			?>
				<a href='download.php?filepath=<?=$row['changename']?>"&filename="<?=$row['realname']?>"'><?=$row['realname']?></a>
				<input type='button' onclick='del("<?=$row['seqno']?>")' value='삭제' />
			<?php
		}
?>