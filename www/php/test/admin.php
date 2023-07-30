<?
	session_start();

	if($_SESSION['userId'] == "" || $_SESSION['userId'] != "admin")
	{
		echo "<script>alert('잘못된 접근입니다.');location.href='login.php'</script>";
	}
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>로그인결과</title>
 </head>
 <body>
<h3>로그인 성공!!</h3>
<br><br>
	<input type="button" onclick="location.href='login.php';" value="로그아웃">
 </body>
</html>