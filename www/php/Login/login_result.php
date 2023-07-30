<!doctype html>
<html lang="ko">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>로그인</title>
 </head>
 <body>
  <?php
	$user_id = $_POST['user_id'];
	$user_pw = $_POST['user_pw'];
	  
	if($user_id == "admin" && $user_pw == "1234") {
		echo "로그인 성공";
		echo "<script>location.href='index.php';</script>";
		setcookie("user_id",$user_id,time()+1200,'/');
		exit;
	}
	else{
		echo "<script>alert('아이디 또는 패스워드가 잘못되었습니다.');history.back();</script>";
		exit;
	}
?>
 </body>
</html>