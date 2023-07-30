<?php
	header('Context-Type : text/html: charset=utf-8');

	$DB["dbhost"] = "localhost";
	$DB["dbuser"] = "tjrals627";
	$DB["dbpw"] = "goldgoo0627!";
	$DB["dbname"] = "tjrals627";

	$connect = new mysqli($DB["dbhost"],$DB["dbuser"],$DB["dbpw"],$DB["dbname"]);
	$connect->set_charset("utf-8");

?>