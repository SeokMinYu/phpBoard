<meta charset="UTF-8">
<?php	
	include "DBconnect.php";
	session_start(); 
	// NAVER LOGIN 
	define('NAVER_CLIENT_ID', 'Wjga03g4pTVj7V7xhvcV');
	define('NAVER_CLIENT_SECRET', 'EFtJt4C8N2');
	define('NAVER_CALLBACK_URL', 'https://tjrals627.cafe24.com/php/board30/naver_Callback.php');
	
		if ($_SESSION['naver_state'] != $_GET['state']) 
		{ 
			echo "<script>alert('잘못 접근하였습니다.');location.href='login.php';</script>";
		} 
		$naver_curl = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".NAVER_CLIENT_ID."&client_secret=".NAVER_CLIENT_SECRET."&redirect_uri=".urlencode(NAVER_CALLBACK_URL)."&code=".$_GET['code']."&state=".$_GET['state']; 
		// 토큰값 가져오기 
		$is_post = false; 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $naver_curl); 
		curl_setopt($ch, CURLOPT_POST, $is_post); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$response = curl_exec ($ch); 
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close ($ch); 
		if($status_code == 200) 
		{ 
			$responseArr = json_decode($response, true); 
			$_SESSION['naver_access_token'] = $responseArr['access_token']; 
			$_SESSION['naver_refresh_token'] = $responseArr['refresh_token']; 
			// 토큰값으로 네이버 회원정보 가져오기 
			$me_headers = array( 'Content-Type: application/json', sprintf('Authorization: Bearer %s', $responseArr['access_token']) ); 
			$me_is_post = false; 
			$me_ch = curl_init(); 
			curl_setopt($me_ch, CURLOPT_URL, "https://openapi.naver.com/v1/nid/me"); 
			curl_setopt($me_ch, CURLOPT_POST, $me_is_post); 
			curl_setopt($me_ch, CURLOPT_HTTPHEADER, $me_headers); 
			curl_setopt($me_ch, CURLOPT_RETURNTRANSFER, true); 
			$me_response = curl_exec ($me_ch); 
			$me_status_code = curl_getinfo($me_ch, CURLINFO_HTTP_CODE); 
			curl_close ($me_ch); 
			$me_responseArr = json_decode($me_response, true);

			if ($me_responseArr['response']['id']) 
			{ 
				$member = mysqli_query($connect,"select count(*) AS CNT from memberSocial where socialkey='".$me_responseArr['response']['id']."'");
				$data = mysqli_fetch_array($member);

				$member2 = mysqli_query($connect,"select * from memberSocial where socialkey='".$me_responseArr['response']['id']."'");
				$data2 = mysqli_fetch_array($member2);
				
				$mb_uid = "NV_".$me_responseArr['response']['id'];
				$member3 = mysqli_query($connect,"select * from member30 where memberId='".$mb_uid."'");
				$data3 = mysqli_fetch_array($member3);
				// 회원가입 DB에서 회원이 있으면(이미 가입되어 있다면) 토큰을 업데이트 하고 로그인함 
				if ($data[0] != 0) 
				{
					if($data3['memberdelete'] != "")
					{
						echo '<script>alert("탈퇴한 회원입니다.");location.href="login.php";</script>';
					}

					$_SESSION['user_id'] = "NV_".$me_responseArr['response']['id'];
					$_SESSION['seq'] = $data2["member_idx"];

					$sql = mysqli_query($connect,"update member30 set logincnt=logincnt+1 where memberId='".$_SESSION['user_id']."'");
					echo '<script>alert("네이버 계정으로 로그인합니다.");location.href="list.php";</script>';
					
					// 로그인 
				} // 회원정보가 없다면 회원가입 
				else 
				{	// 회원아이디
					$mb_uid = "NV_".$me_responseArr['response']['id']; 
					// 이름
					$mb_name = $me_responseArr['response']['name'];
					$mb_phone = $me_responseArr['response']['mobile'];
					$mb_email = $me_responseArr['response']['email']; // 이메일 
					$gender = $me_responseArr['response']['gender']; // 성별 F: 여성, M: 남성, U: 확인불가 
					if ($gender == "M")
					{
						$mb_gender = "male"; 
					}else{
						$mb_gender = "female"; 
					}	

					$birthday = explode("-", $me_responseArr['response']['birthday']);
					$mb_birthday = $birthday[0].$birthday[1];



					$lv = 1;

					// 멤버 DB에 회원정보를 넣고 로그인 
					$sql = mysqli_query($connect,"insert into member30 
							(memberId,memberName,level,createtime,memberBirth,memberSex,memberPhone,memberEmail) 
							values 
							('".$mb_uid."','".$mb_name."','".$lv."',now(),'".$mb_birthday."','".$mb_gender."','".$mb_phone."','".$mb_email."')");

					$bno = mysqli_insert_id($connect);

					$sql2 = mysqli_query($connect,"insert into memberSocial (socialCode,socialKey,member_idx) values ('NV','".$me_responseArr['response']['id']."','".$bno."')");

					echo "<script>alert('가입이 완료되었습니다. 네이버 로그인으로 로그인하세요.');location.href='login.php';</script>";

				} 
			} 
			else 
			{ 
				echo "<script>alert('회원정보를 가져오지 못했습니다.');location.href='login.php';</script>";
				// 회원정보를 가져오지 못했습니다. 
			} 
		} 
	else 
	{ 
		echo "<script>alert('토큰값을 가져오지 못했습니다.');location.href='login.php';</script>";
		// 토큰값을 가져오지 못했습니다. 
	}
?>