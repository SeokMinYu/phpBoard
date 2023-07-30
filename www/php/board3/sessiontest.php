<?php
	session_start();
	
	$_SESSION['test'] =  "세션사용하기";
	$_SESSION['test2'] =  "";

	echo $_SESSION['test'];

	print_r($_SESSION);
?>

<!doctype html>
<html lang="ko">
 <head>
  <meta charset="UTF-8">
  <title>SessionTest</title>
 </head>
 <body>
 <?php
	if(!isset($_SESSION['sid']))
	{ ?>
	<form action="session_test.php" method="POST">
		<input type="text" name="id"/>
		<input type="submit" value="제출"/>
	</form>
	<?}
		else
		{ ?>
			<?=$_SESSION['sid']?> 로그인되었습니다.
			<input type="button" onclick="location.href='sessionOut.php';" value="로그아웃"/>
		<?} ?>
 </body>
</html>
