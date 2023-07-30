<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>로그인</title>
</head>
<body>
<div align="center">
<h2>관리자 로그인</h2></div>
	<form method='post' action='login_ok.php'>
		<table align="center">
			<tr>
				<td>아이디</td>
				<td><input type="text" name="id" /></td>
			</tr>
			<tr>
				<td>비밀번호</td>
				<td><input type="password" name="pw"/></td>
			</tr>
			<tr>
				<td></td><td><input type="submit" value="로그인"/></td>
			</tr>
		</table>
	</form>
</body>
</html>