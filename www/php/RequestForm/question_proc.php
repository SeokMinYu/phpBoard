<?php

	//ini_set("allow_url_fopen", 1);


	include "userDB.php";

	$userName = $_POST['username'];

	$userEmail = $_POST['useremail'].'@'.$_POST['emailsub'];

	$userPhone = $_POST['phone1'].$_POST['phone2'].$_POST['phone3'];

	$userAdd = $_POST['useradd1'].$_POST['useradd2'].$_POST['useradd3'];

	$userTitle = $_POST['usertitle'];

	$userContent = $_POST['usercontent'];

	$usersql = mysqli_query($db,"insert into questionForm (userName,userEmail,userPhone,userAdd,userTitle,userContent,createTime) values('{$userName}','{$userEmail}','{$userPhone}','{$userAdd}','{$userTitle}','{$userContent}',now())");


?>
<script type="text/javascript">alert("입력되었습니다.");</script>
<meta http-equiv="refresh" content="0 url= index.php"/>