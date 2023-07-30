<?php

	include "boardDB.php";
	$sqn = $_POST['sqn'];

	$filesql = mq("select * from upload where seqno='$sqn'");
	$row = $filesql->fetch_array();

	if($filesql->num_rows > 0) {
		if($sqn['sqn'] == $row['seqno']) {
			while(file_exists($row['changename'])) {
			unlink($row['changename']);
			}
		}
	$filesql2 = mq("delete from upload where seqno='$sqn'");
	}

	$filesql = mq("select idx from upload where seqno='$sqn'");

		while($row = $filesql->fetch_array()) {
			?>
				<a href='download.php?filepath=<?=$row['changename']?>"&filename="<?=$row['realname']?>"'><?=$row['realname']?></a>
				<input type='button' onclick='del("<?=$row['seqno']?>")' value='삭제' />
			<?php
	}?>
