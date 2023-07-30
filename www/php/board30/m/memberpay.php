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
	</script>
</head>
<body onload="on_load()" bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0>
	<table width="650" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
		<tr>
			<td style="text-align:left;">
				<form id="mobileweb_form" name="mobileweb_form" method="POST" >
					
	
					<input type="hidden" name="P_NEXT_URL" value="https://tjrals627.cafe24.com/php/board30/m/stdpay/mx_rnext.php"> 
					<input type="hidden" name="P_NOTI_URL" value="https://tjrals627.cafe24.com/php/board30/m/stdpay/mx_rnoti.php"> 
					<input type="hidden" name="P_RETURN_URL" value="https://tjrals627.cafe24.com/php/board30/m/stdpay/mx_rreturn.php"> 
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
					<button onclick="location.href='list.php'" style="padding:10px">취소</button>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>