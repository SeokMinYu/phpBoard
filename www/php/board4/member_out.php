<?php
	include "login_check.php";
	
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
	<form action="member_out_ok.php" method="post">
	<input type="hidden" name="del" value="<?=$_SESSION['seq']?>">
	<table width="300" border="0" cellpadding="2" cellspacing="1">
	<tr>
		<td height="20" align="center">
			<B>비 밀 번 호 확 인</B></font>
		</td>
	</tr>
	<tr>
    <td align="center">
        <B>비밀번호 : </B>
        <input type="password" name="pw" id="pw" size="8" required />
        <input type="submit" value="확 인"/>
        <input type="button" value="취 소" onclick="history.back()"/>
    </td>
</tr>
</table>
</form>
</body>
</html>