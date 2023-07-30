<?php

	header('Context-Type : text/html; charset=utf-8');
	define("__CASTLE_PHP_VERSION_BASE_DIR__", $_SERVER['DOCUMENT_ROOT']."/php/gboard/castle-gaya");
	include_once(__CASTLE_PHP_VERSION_BASE_DIR__ . "/castle_referee.php");

	$DB["dbhost"] = "localhost";
	$DB["dbuser"] = "tjrals627";
	$DB["dbpw"] = "goldgoo0627!";
	$DB["dbname"] = "tjrals627";

	$connect = new mysqli($DB["dbhost"],$DB["dbuser"],$DB["dbpw"],$DB["dbname"]);
	$connect->set_charset("utf-8");

?>