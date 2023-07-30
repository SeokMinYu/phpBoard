<meta charset="UTF-8">
<?
	include "DBconnect.php";

	$domain = $_POST['D_Link'];
	$domainSet = explode('://',$domain);
	
	function curl_check($domain)
	{

		$ch = curl_init();									//curl 초기화

		curl_setopt($ch, CURLOPT_URL, $domain);             //URL 지정하기	 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);		//connection timeout 10초 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	//원격 서버의 인증서가 유효한지 검사 안함
		curl_setopt($ch, CURLOPT_POST, true);				//true시 post 전송 
		curl_setopt($ch, CURLOPT_SSLVERSION,1);				//ssl 셋팅
		curl_setopt($ch, CURLOPT_HEADER, 1 );				// 호출의 헤더값
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		//요청 결과를 문자열로 반환
		//일부 사이트의 경우 referer을 검증 값으로 사용할 수 있음
		curl_setopt($ch, CURLOPT_REFERER, $domain);		// https, http 구분

		$response = curl_exec($ch); //cual 실행

		curl_close($ch);

		return $response;
	}

	 //입력받은 도메인주소를 판단 올바르면 값이 있고 아니면 빈값
	if ( curl_check($domain) == "" ) //입력받은 도메인주소가 올바르지 않을때 
	{	
		if ($domainSet[0] == "https") // https일때 http 로 변환
		{
			$domainResult = "http://".$domainSet[1];
		}
		else 
		{
			$domainResult = $domain;  // http이면 바로저장
		}

		$domainsql = "update T_CURL set domain='".$domain."', domainResult='".$domainResult."' where seqno = 1";
		$domainres = mysqli_query($connect, $domainsql);
		echo "<script>alert('등록완료');location.href='domainwrite.php';</script>";
		exit;

	}
	else // 도메인주소가 올바를 때
	{
		$domainResult = $domain;  // 결과에 값저장
		if ($domainSet[0] == "http") // 우선 http 일경우
		{
			$domainResult = "https://".$domainSet[1]; // https로 변환
		}
		if (curl_check($domainResult) == "" ) // https가 아니면
		{ 
			$domainResult = "http://".$domainSet[1]; //기존의 http로 저장
		}

		$domainsql = "update T_CURL set domain='".$domain."', domainResult='".$domainResult."' where seqno = 1";
		$domainres = mysqli_query($connect, $domainsql);
		echo "<script>alert('등록완료');location.href='domainwrite.php';</script>";
		exit;
	}
?>