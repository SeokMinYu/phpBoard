<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>로그인</title>
</head>
<body>
	<form method='post' action='login_ok.php'>
		<table align="center">
			<tr>
				<td>아이디</td>
				<td><input type="text" name="user_id" /></td>
			</tr>
			<tr>
				<td>비밀번호</td>
				<td><input type="password" name="user_pw"/></td>
			</tr>
			<tr>
				<td></td><td><input type="submit" value="로그인"/><input type="button" onclick="location.href='member.php'" value="회원가입"/></td>
			</tr>
		</table>
	</form>
</body>
</html>
