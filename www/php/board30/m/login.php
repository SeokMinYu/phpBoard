<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>로그인</title>
</head>
<body>
<script type="text/javascript" src="https://static.nid.naver.com/js/naverLogin_implicit-1.0.3.js" charset="UTF-8"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<h1 align="center">PHP 게시판</h1>
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
				<td></td><td><input type="submit" value="로그인"/><input type="button" onclick="location.href='member_join.php'" value="회원가입"/></td>
			</tr>
			<tr>
				<td colspan="2">
				<?php
					session_start();
					// NAVER LOGIN
					define('NAVER_CLIENT_ID', 'Wjga03g4pTVj7V7xhvcV');
					define('NAVER_CLIENT_SECRET', 'EFtJt4C8N2');
					define('NAVER_CALLBACK_URL', 'https://tjrals627.cafe24.com/php/board30/naver_Callback.php');

					// 네이버 로그인 접근토큰 요청 예제
					$naver_state = md5(microtime() . mt_rand());
					$_SESSION['naver_state'] = $naver_state;
					$naver_apiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".NAVER_CLIENT_ID."&redirect_uri=".urlencode(NAVER_CALLBACK_URL)."&state=".$naver_state;
				?>
					<a href="<?=$naver_apiURL;?>"><img src="./icon/naver_login.png" style="width:280px;height:35px;""></a>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<?php
					session_start();
					// kakao LOGIN
					define('KAKAO_CLIENT_ID', '5878a9c120240be2d4dfef9970105d9c');
					define('KAKAO_CALLBACK_URL', 'https://tjrals627.cafe24.com/php/board30/kakao_Callback.php');

					// 네이버 로그인 접근토큰 요청 예제
					$kakao_state = md5(microtime() . mt_rand());
					$_SESSION['kakao_state'] = $kakao_state;
					$kakao_apiURL ="https://kauth.kakao.com/oauth/authorize?client_id=".KAKAO_CLIENT_ID."&redirect_uri=".urlencode(KAKAO_CALLBACK_URL)."&response_type=code&state=".$kakao_state;
				?>
					<a href="<?=$kakao_apiURL;?>"><img src="./icon/kakao_login.png" style="width:280px;height:35px;"></a>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
