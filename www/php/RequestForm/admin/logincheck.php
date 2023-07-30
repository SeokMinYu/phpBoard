<?php
	session_start();
	
	$id = $_SESSION['admin_id'];
	if($id == '') { //로그인x 
		echo '<script>location.href="../index.php";</script>';
		exit;
	}
?>