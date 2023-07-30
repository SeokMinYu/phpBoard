<?php

	session_start();

	$admin_id = $_SESSION['admin_id'];
	if($admin_id == '') 
	{ //로그인x 
		echo '<script>location.href="index.php";</script>';
		exit;
	}

?>
