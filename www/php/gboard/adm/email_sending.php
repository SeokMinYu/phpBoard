<?php

	include_once('./_common.php');
	include_once("../lib/naver_mailer.lib.php");

	//받는사람
	$tomail = $_POST['toEmail'].'@'.$_POST['toEmail2'];

	//보내는사람
	$frommail = $_POST['fromEmail'];

	//제목
	$title = $_POST['title']; 

	//내용
	$content = $_POST['content'];

	if ($frommail && preg_match("/([a-zA-Z0-9,_]{2,15})@([a-zA-Z0-9]{2,15}).([a-zA-Z0-9]{2,15})/", $frommail))
	{
		mailer("tjrals627", $frommail, $tomail, $title, $content, 1);
		goThere("메일이 정상적으로 전송되었습니다.", "email_test.php");
	}
	else 
	{
		goThere("에러가 발생하였습니다. 다시 한번 시도해주세요", "email_test.php");
	}
?>