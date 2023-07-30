<?php
	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";
	$bno = $_REQUEST['idx'];
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>
</head>
<body>
	<center>
	<br>
	<form action="list_delete_ok.php?idx=<?=$bno ?>" method="post">
		<table width="300" border="0" cellpadding="2" cellspacing="1">
		<tr>
			<td height="20" align="center">
				<B>비 밀 번 호 확 인</B></font>
			</td>
		</tr>
		<tr>
		<td align="center">
			<B>비밀번호 : </B>
			<input type="password" name="upw" id="upw" size="8" required />
			<input type="submit" value="확 인"/>
			<input type="button" value="취 소" onclick="history.back()"/>
		</td>
		</tr>
		</table>
	</form>
</body>
</html>