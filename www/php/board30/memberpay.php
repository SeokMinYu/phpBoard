<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php
	include "DBconnect.php";
	include "login_check.php";

	$sql = mysqli_query($connect,"select * from member30 where seqno = '".$_SESSION['seq']."'");
	$member = mysqli_fetch_array($sql);
	
	if ($member['memberPay'] == 1){
		echo "<script>alert('이미 유료회원입니다.');history.back(-1);</script>";
		exit;
	}

	$mid = "INIpayTest";  // 가맹점 ID(가맹점 수정후 고정)
	$price = "2000";        // 상품가격(특수기호 제외, 가맹점에서 직접 설정)

	$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";

	if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT']))
	{
?>
		<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
		<html lang="ko">
		<head>
			<meta http-equiv="Expires" content="0"/> 
			<meta name="Author" content="yw0399"/> 
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
			<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
			<title>회원유료결제</title>
			<!-- 이니시스 표준결제 js -->
			<script type="text/javascript">
				window.name = "BTPG_CLIENT";

				var width = 330;
				var height = 480;
				var xpos = (screen.width - width) / 2;
				var ypos = (screen.width - height) / 2;
				var position = "top=" + ypos + ",left=" + xpos;
				var features = position + ", width=320, height=440";

				function on_load() { 
					myform = document.mobileweb_form; 
				/**************************************************************************** 
				OID(상점주문번호)를 랜덤하게 생성시키는 루틴
				상점에서 각 거래건마다 부여하는 고유의 주문번호가 있다면 이 루틴은 필요없고, 
				해당 값을 P_OID에 세팅해서 사용하면 된다.
				****************************************************************************/ 
					curr_date = new Date(); 
					year = curr_date.getYear(); 
					month = curr_date.getMonth(); 
					day = curr_date.getDay(); 
					hours = curr_date.getHours(); 
					mins = curr_date.getMinutes(); 
					secs = curr_date.getSeconds(); 
					myform.P_OID.value = year.toString() + month.toString() + day.toString() + hours.toString() + mins.toString() + secs.toString();
				} 


				function on_pay() { 
					myform = document.mobileweb_form; 
					
				/**************************************************************************** 
				결제수단 action url을 아래와 같이 설정한다
				URL끝에 /를 삭제하면 다음과 같은 오류가 발생한다.
				"일시적인 오류로 결제시도가 정상적으로 처리되지 않았습니다.(MX1002) 자세한 사항은 이니시스(1588-4954)로 문의해주세요."
				****************************************************************************/ 
					if(myform.P_GOPAYMETHOD.value == "CARD") {
						myform.action = "https://mobile.inicis.com/smart/wcard/"; //신용카드
						}
					else if(myform.P_GOPAYMETHOD.value == "VBANK") {
						myform.action = "https://mobile.inicis.com/smart/vbank/"; //가상계좌
						}
					else if(myform.P_GOPAYMETHOD.value == "BANK") {
						myform.action = "https://mobile.inicis.com/smart/bank/"; //계좌이체
					}
					else if(myform.P_GOPAYMETHOD.value == "HPP") {
						myform.action = "https://mobile.inicis.com/smart/mobile/"; //휴대폰
						}
					else if(myform.P_GOPAYMETHOD.value == "CULTURE") {
						myform.action = "https://mobile.inicis.com/smart/culture/"; //문화 상품권
						}
					else if(myform.P_GOPAYMETHOD.value == "HPMN") {
						myform.action = "https://mobile.inicis.com/smart/hpmn/"; //해피머니 상품권
						}
					else {
						myform.action = "https://mobile.inicis.com/smart/wcard/"; // 엉뚱한 값이 들어오면 카드가 기본이 되게 함
						}
					
					myform.P_RETURN_URL.value = myform.P_RETURN_URL.value + "?P_OID=" + myform.P_OID.value; // 계좌이체 결제시 P_RETURN_URL로 P_OID값 전송(GET방식 호출)
					// myform.target = "_self"; // 주석 혹은 제거 시 self 로 지정됨
					myform.submit(); 
					} 
					function goHome()
					{
						alert("결제를 취소합니다.");
						location.href="https://tjrals627.cafe24.com/php/board30/list.php";
					}
			</script>
		</head>
		<body onload="on_load()" bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0>
			<table width="650" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
				<tr>
					<td style="text-align:left;">
						<form id="mobileweb_form" name="mobileweb_form" method="POST" >
							
							<input type="hidden" name="P_NEXT_URL" value="https://tjrals627.cafe24.com/php/board30/mpay/mx_rnext.php"> 
							<input type="hidden" name="P_NOTI_URL" value="https://tjrals627.cafe24.com/php/board30/mpay/mx_rnoti.php"> 
							<input type="hidden" name="P_RETURN_URL" value="https://tjrals627.cafe24.com/php/board30/mpay/mx_rreturn.php"> 
							<input type="hidden" name="P_GOPAYMETHOD" value="CARD">
							<input type="hidden" name="P_MID" value="<?php echo $mid ?>">
							<input type="hidden" name="P_GOODS" value="Mobile WEB">
							<input type="hidden" name="P_NOTI" value="">
							<!-- 필수 -->
							<br/><b>유료회원 결제</b>
							<div style="border:0px #dddddd double;padding:10px;">

								<br/><b>아이디</b> : <?=$user_id?><br/>

								<br/><b>이름</b> : <?=$member["memberName"]?>
								<br/><input type="hidden" style="width:100%;" name="P_UNAME" value="<?=$member["memberName"]?>" >

								<br/><b>전화번호</b> : <?=$member['memberPhone']?>
								<br/><input type="hidden" style="width:100%;" name="P_MOBILE" value="<?=$member['memberPhone']?>" >

								<br/><b>이메일</b> : <?=$member["memberEmail"]?>

								<br/><input type="hidden" style="width:100%;" name="P_EMAIL" value="<?=$member["memberEmail"]?>" >
				
								<br/><b>금액</b> : <?php echo $price ?> 원
								<br/><input type="hidden" style="width:100%;" name="P_AMT" value="<?php echo $price ?>" >
									

								<!-- <br/><b>timestamp</b> : -->
								<input type="hidden" style="width:100%;" name="timestamp" value="<?php echo $timestamp ?>" >
								<br/>

								<!-- <br/><b>signature</b> : -->
								<input type="hidden" style="width:100%;" name="signature" value="<?php echo $sign ?>" >
								<br/><input type="hidden" style="width:100%;" name="returnUrl" value="<?php echo $siteDomain ?>/INIStdPayReturn.php" >

							</div>	
							<input type="hidden" name="P_RESERVED" value="twotrs_isp=Y&block_isp=Y&twotrs_isp_noti=N">
							<input type="hidden" name="P_OID" value="test_oid_1234">
							<input type="hidden" name="P_HPP_METHOD" value="1">
						</td>
					</tr>
					 <tr>
						<td>
							<button onclick="on_pay()" name="btn_card_pay" style="padding:10px">결제요청</button>
							<input type="button" onclick="location.href='./list.php'" style="padding:10px" value="취소">
						</td>
					</tr>
				</table>
			</form>
<?	}
	else
	{
		require_once('./stdpay/libs/INIStdPayUtil.php');
		$SignatureUtil = new INIStdPayUtil();
		/*
		  //*** 위변조 방지체크를 signature 생성 ***

		  oid, price, timestamp 3개의 키와 값을

		  key=value 형식으로 하여 '&'로 연결한 하여 SHA-256 Hash로 생성 된값

		  ex) oid=INIpayTest_1432813606995&price=819000&timestamp=2012-02-01 09:19:04.004


		 * key기준 알파벳 정렬

		 * timestamp는 반드시 signature생성에 사용한 timestamp 값을 timestamp input에 그대로 사용하여야함
		 */

		//############################################
		// 1.전문 필드 값 설정(***가맹점 개발수정***)
		//############################################
		// 여기에 설정된 값은 Form 필드에 동일한 값으로 설정			
		//인증
		$signKey = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS"; // 가맹점에 제공된 웹 표준 사인키(가맹점 수정후 고정)
		$timestamp = $SignatureUtil->getTimestamp();   // util에 의해서 자동생성

		$orderNumber = $mid . "_" . $SignatureUtil->getTimestamp(); // 가맹점 주문번호(가맹점에서 직접 설정)

		$cardNoInterestQuota = "11-2:3:,34-5:12,14-6:12:24,12-12:36,06-9:12,01-3:4";  // 카드 무이자 여부 설정(가맹점에서 직접 설정)
		$cardQuotaBase = "2:3:4:5:6:11:12:24:36";  // 가맹점에서 사용할 할부 개월수 설정
		//###################################
		// 2. 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
		//###################################
		$mKey = $SignatureUtil->makeHash($signKey, "sha256");

		$params = array(
			"oid" => $orderNumber,
			"price" => $price,
			"timestamp" => $timestamp
		);
		$sign = $SignatureUtil->makeSignature($params, "sha256");
		/* 기타 */
		$siteDomain = "https://tjrals627.cafe24.com/php/board30/stdpay/INIStdPaySample"; //가맹점 도메인 입력
	?>
		<!doctype html PUBLIC "-//W3C//DTD HTML Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html lang="ko">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<title>회원유료결제</title>
			<!-- 이니시스 표준결제 js -->
			<script language="javascript" type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>

			<script type="text/javascript">
				function paybtn() {
					INIStdPay.pay('SendPayForm_id');
				}

			</script>
		</head>
		<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0>
			<table width="650" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
				<tr>
					<td style="text-align:left;">
						<form id="SendPayForm_id" name="" method="POST" >
							<!-- 필수 -->
							<br/><b>유료회원 결제</b>
							<div style="border:0px #dddddd double;padding:10px;">
								<input type="hidden" style="width:100%;" name="version" value="1.0" >

								<input type="hidden" style="width:100%;" name="mid" value="<?php echo $mid ?>" >

								<input type="hidden" style="width:100%;" name="goodname" value="테스트" >

								<input type="hidden" style="width:100%;" name="oid" value="<?php echo $orderNumber ?>" >

								<br/><b>아이디</b> : <?=$user_id?><br/>

								<br/><b>이름</b> : <?=$member["memberName"]?>
								<br/><input type="hidden" style="width:100%;" name="buyername" value="<?=$member["memberName"]?>" >

								<br/><b>전화번호</b> : <?=$member['memberPhone']?>
								<br/><input type="hidden" style="width:100%;" name="buyertel" value="<?=$member['memberPhone']?>" >

								<br/><b>이메일</b> : <?=$member["memberEmail"]?>

								<br/><input type="hidden" style="width:100%;" name="buyeremail" value="<?=$member["memberEmail"]?>" >
				
								<br/><b>금액</b> : <?php echo $price ?> 원
								<br/><input type="hidden" style="width:100%;" name="price" value="<?php echo $price ?>" >
									<input type="hidden" style="width:100%;" name="currency" value="WON" >

								<!-- <br/><b>timestamp</b> : -->
								<input type="hidden" style="width:100%;" name="timestamp" value="<?php echo $timestamp ?>" >
								<br/>

								<!-- <br/><b>signature</b> : -->
								<input type="hidden" style="width:100%;" name="signature" value="<?php echo $sign ?>" >
								<br/><input type="hidden" style="width:100%;" name="returnUrl" value="<?php echo $siteDomain ?>/INIStdPayReturn.php" >

								<input type="hidden" name="mKey" value="<?php echo $mKey ?>" >

								<input type="hidden" style="width:100%;" name="gopaymethod" value="Card" >
								<input type="hidden" style="width:100%;" name="offerPeriod" value="2015010120150331" >
								<input type="hidden" style="width:100%;" name="acceptmethod" value="HPP(1):no_receipt:va_receipt:vbanknoreg(0):below1000" >
								<input type="hidden" style="width:100%;" name="nointerest" value="<?php echo $cardNoInterestQuota ?>" >
								<input type="hidden" style="width:100%;" name="quotabase" value="<?php echo $cardQuotaBase ?>" >
							</div>	
							<input type="hidden" style="width:100%;" name="closeUrl" value="<?php echo $siteDomain ?>/close.php" >
							<input type="hidden" style="width:100%;" name="popupUrl" value="<?php echo $siteDomain ?>/popup.php" >
							<input type="hidden" style="width:100%;" name="merchantData" value="" >
						</form>
					</td>
				</tr>
				 <tr>
					<td>
						<button onclick="paybtn()" style="padding:10px">결제요청</button>
						<input type="button" onclick="location.href='list.php'" style="padding:10px" value="취소">
					</td>
				</tr>
			</table>
<?	}
?>
</body>
</html>