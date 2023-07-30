<meta charset="UTF-8">
<?php	
	include "DBconnect.php";
	session_start(); 
	// KAKAO LOGIN 
	define('KAKAO_CLIENT_ID', '5878a9c120240be2d4dfef9970105d9c');
	define('KAKAO_CALLBACK_URL', 'http://tjrals627.cafe24.com/php/board30/kakao_Callback.php');
	
		if ($_SESSION['kakao_state'] != $_GET['state']) 
		{ 
			echo "<script>alert('잘못 접근하였습니다.');location.href='login.php';</script>";
		} 
		if ($_GET["code"]) 
		{ 
			//사용자 토큰 받기 
			$code = $_GET["code"]; 
			$params = sprintf( 'grant_type=authorization_code&client_id=%s&redirect_uri=%s&code=%s', KAKAO_CLIENT_ID, KAKAO_CALLBACK_URL, $code); 
			$TOKEN_API_URL = "https://kauth.kakao.com/oauth/token"; 
			$opts = array( 
							CURLOPT_URL => $TOKEN_API_URL, 
							CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSLVERSION => 1, // TLS 
							CURLOPT_POST => true, CURLOPT_POSTFIELDS => $params, 
							CURLOPT_RETURNTRANSFER => true, 
							CURLOPT_HEADER => false 
						); 
			$curlSession = curl_init(); 
			curl_setopt_array($curlSession, $opts); 
			$accessTokenJson = curl_exec($curlSession);
			curl_close($curlSession); 
			$responseArr = json_decode($accessTokenJson, true); 
			$_SESSION['kakao_access_token'] = $responseArr['access_token']; 
			$_SESSION['kakao_refresh_token'] = $responseArr['refresh_token']; 
			$_SESSION['kakao_refresh_token_expires_in'] = $responseArr['refresh_token_expires_in']; 
			//사용자 정보 가저오기 
			$USER_API_URL= "https://kapi.kakao.com/v2/user/me"; 
			$opts = array( 
							CURLOPT_URL => $USER_API_URL, 
							CURLOPT_SSL_VERIFYPEER => false, 
							CURLOPT_SSLVERSION => 1, 
							CURLOPT_POST => true, 
							CURLOPT_POSTFIELDS => false, 
							CURLOPT_RETURNTRANSFER => true, 
							CURLOPT_HTTPHEADER => array( "Authorization: Bearer " . $responseArr['access_token'] ) 
						); 
			$curlSession = curl_init(); 
			curl_setopt_array($curlSession, $opts); 
			$accessUserJson = curl_exec($curlSession); 
			curl_close($curlSession); 
			$me_responseArr = json_decode($accessUserJson, true); 

			if ($me_responseArr['id']) 
			{ 
				$member = mysqli_query($connect,"select count(*) AS CNT from memberSocial where socialkey='".$me_responseArr['id']."'");
				$data = mysqli_fetch_array($member);

				$member2 = mysqli_query($connect,"select * from memberSocial where socialkey='".$me_responseArr['id']."'");
				$data2 = mysqli_fetch_array($member2);
				
				$mb_uid = "KA_".$me_responseArr['id'];
				$member3 = mysqli_query($connect,"select * from member30 where memberId='".$mb_uid."'");
				$data3 = mysqli_fetch_array($member3);

				if ($data[0] != 0) 
				{
					if($data3['memberdelete'] != "")
					{
						echo '<script>alert("탈퇴한 회원입니다.");location.href="login.php";</script>';
					}

					$_SESSION['user_id'] = "KA_".$me_responseArr['id'];
					$_SESSION['seq'] = $data2["member_idx"];

					$sql = mysqli_query($connect,"update member30 set logincnt=logincnt+1 where memberId='".$_SESSION['user_id']."'");

					echo "<script>alert('카카오 계정으로 로그인합니다.');location.href='list.php';</script>";
					
					// 로그인 
				} // 회원정보가 없다면 회원가입
				else 
				{ 
					$mb_uid = "KA_".$me_responseArr['id']; 
					// 이름
					$mb_name = $me_responseArr['properties']['nickname'];
					$mb_email = $me_responseArr['kakao_account']['email']; // 이메일 
					$mb_gender = $me_responseArr['kakao_account']['gender'];

					$mb_birthday = $me_responseArr['kakao_account']['birthday'];

					$lv = 1;
					// 멤버 DB에 토큰과 회원정보를 넣고 로그인 

					$sql = mysqli_query($connect,"insert into member30 
							(memberId,memberName,level,createtime,memberBirth,memberSex,memberEmail) 
							values 
							('".$mb_uid."','".$mb_name."','".$lv."',now(),'".$mb_birthday."','".$mb_gender."','".$mb_email."')");

					$bno = mysqli_insert_id($connect);

					$sql2 = mysqli_query($connect,"insert into memberSocial (socialCode,socialKey,member_idx) values ('KA','".$me_responseArr['id']."','".$bno."')");

					echo "<script>alert('가입이 완료되었습니다. 카카오 로그인으로 로그인하세요.');location.href='login.php';</script>";
				} 
			} 
			else 
			{ 
				echo "<script>alert('회원정보를 가져오지 못했습니다.');location.href='login.php';</script>";
				// 회원정보를 가져오지 못했습니다. 
			} 
		}
?>