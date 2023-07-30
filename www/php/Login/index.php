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
	 $user_id = $_COOKIE["user_id"];
	if($user_id == "admin") {
	   
	    echo "<p>안녕하세요. 관리자님님</p>";
		echo "<p><a href='logout.php'>로그아웃</a></p>";
	}
	else{
		echo "관리자만 접근가능";
		exit;
}
?>
 </body>
</html>