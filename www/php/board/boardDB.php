<?php

	header('Content-Type : text/html; charset=utf-8');

	$db = new mysqli("localhost","tjrals627","goldgoo0627!","tjrals627");
	$db->set_charset("utf-8");

	function mq($sql){
		global $db;
		return $db->query($sql);
	}
?>