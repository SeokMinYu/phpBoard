<?php

	header('Content-Type : text/html; charset=utf-8');

	$db = new mysqli("localhost","work7","rkdidlsxjspt1999!#","work7");
	$db->set_charset("utf-8");

	function mq($sql){
		global $db;
		return $db->query($sql);
	}
?>