<?php

	session_start();

	$id = $_POST['id'];

	$_SESSION['sid'] = $id;
	
?>
<meta http-equiv="refresh" content="0; url=sessiontest.php"/>