<?php
	session_start();
	session_destroy();
	session_unset();
?>

<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>세션로그인</title>
 </head>
 <body>
	<form name="loginform" method="post" onsubmit="return loginchk();" action="login_ok.php">
	아이디 : <input type="text" name="userId" value=""><br>
	비밀번호 : <input type="password" name="userPw" value=""><br>
	<input type="submit" value="로그인">
	</form>
 </body>
 <script>

	function loginchk()
	{
		var f = document.loginform;

		if(f.userId.value == "")
		{
			alert("아이디를 입력하세요");
			f.userId.focus();
			return false;
		}
		if(f.userPw.value == "")
		{
			alert("비밀번호를 입력하세요");
			f.userPw.focus();
			return false;
		}
		return true;
	}
 </script>
</html>
