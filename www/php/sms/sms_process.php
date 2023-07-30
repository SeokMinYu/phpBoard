<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<?php
	/* 작성자:너나우리 모바일 팀(sms@majunsoft.com)
	* 작성일자:2019년 05월 15일
	* 작성목적: 본 프로그램은 너나우리 sms 문자메세지에서 php 웹프로그램 관련 예제입니다.
	*              본 예제에서는 보내는 사람의 핸드폰번호, 받는사람 핸드폰번호,전송내용 sms_main.php에서
	*              전송 받아 실제 sms 보내는 모듈입니다.
	*              본 프로그램을 실행하려면 웹서버에 본 프로그램과 sms_process.php,nusoap_youiwe.php
	*              파일을 업로드하고 sms_process.php 파일에 너나우리에서 생성하신 sms 문자아이디와 문자패스워드를
	*              입력하면 됩니다.
	*/

	include_once('nusoap_youiwe.php');

	$snd_number=$_POST["snd_number"];		//보내는 사람 번호를 받음
	$rcv_number=$_POST["rcv_number"];			//받는 사람 번호를 받음
	$sms_content=$_POST["sms_content"];			//전송 내용을 받음
	$reserve_date=$_POST["reserve_date"];		//예약 일자를 받음
	$reserve_time=$_POST["reserve_time"];			//예약 시간을 받음
	
	/******고객님 접속 정보************/
	$sms_id="******";           //고객님께서 부여 받으신 sms_id
	$sms_pwd="******";          //고객님께서 부여 받으신 sms_pwd
	/**********************************/
	$callbackURL = "www.youiwe.co.kr";
	$userdefine = $sms_id;							//예약취소를 위해 넣어주는 구분자 정의값, 사용자 임의로 지정해주시면 됩니다. 영문으로 넣어주셔야 합니다. 사용자가 구분할 수 있는 값을 넣어주세요.
	$canclemode = "1";                //예약 취소 모드 1: 사용자정의값에 의한 삭제.  현제는 무조건 1을 넣어주시면 됩니다.

	//구축 테스트 주소와 일반 웹서비스 선택
	if (substr($sms_id,0,3) == "bt_"){
		$webService = "http://webservice.youiwe.co.kr/SMS.v.6.bt/ServiceSMS_bt.asmx?WSDL";
	}
	else{
		$webService = "http://webservice.youiwe.co.kr/SMS.v.6/ServiceSMS.asmx?WSDL";
	}
		
	//+) funcMode는 메소드실행 후 반환값에 따라 다른 메시지를 띄우기 위해서 쓰입니다.

	$sms = new SMS($webService); //SMS 객체 생성
	
	/*즉시 전송으로 구성하실경우*/
	$result=$sms->SendSMS($sms_id,$sms_pwd,$snd_number,$rcv_number,$sms_content);// 5개의 인자로 함수를 호출합니다.

	/*예약 전송으로 구성하실경우*/
	//$result=$sms->SendSMSReserve($sms_id,$sms_pwd,$snd_number,$rcv_number,$sms_content,$reserve_date,$reserve_time,$userdefine);// 8개의 인자로 함수를 호출합니다.

	/*잔여량을 가져올 경우*/
	//$result=$sms->GetRemainCount($sms_id,$sms_pwd);	$funcMode = 1;

	/*예약 취소*/
	//$result=$sms->ReserveCancle($sms_id,$sms_pwd,$userdefine,$canclemode);	$funcMode = 2;

	/*가장 가까운 만료일을 조회할 경우*/
	//$result=$sms->GetRemainDay($sms_id,$sms_pwd);		$funcMode = 3;

	/*주간 제한량/발송량을 조회할 경우*/
	//$result=$sms->GetWeeklyLimit($sms_id,$sms_pwd);							$funcMode = 4;

	/*결과는 알맞게 처리합니다.*/
	/*전송결과 처리
	*1 : 발송성공
	*1~N : 콤마로 연결하여 다중 발송을 하였을 경우에는 성공한 정수 숫자로 리턴됩니다.
	*0 : SMS발송 가능량 부족
	*-1  : 잘못된 sms_id와 패스워드 입력
	*      (sms_id와 패스워드를 다시 한번 확인해주시기 바랍니다.  sms_id,패스워드는 로그인때 id와 password가 아니며,
	*       sms, LMS, MMS등의 서비스 신청시에 생성한 문자아이디와 문자패스워드입니다.)
	*-2  : SMS 아이디 공백
	*-3  : 발송 모두 실패
	*      (수신자번호가 "숫자가 아닌 값"일시, 수신자번호 헨드폰 국번이 잘못된 값일시, 발송제한서버일시 값 반환)
	*-4  : 해쉬공백
	*-5  : 해쉬이상 = 잘못된 sms_id와 패스워드 입력
	*      (sms_id와 패스워드를 다시 한번 확인해주시기 바랍니다.  sms_id,패스워드는 로그인때 id와 password가 아니며,
	*       sms, LMS, MMS등의 서비스 신청시에 생성한 문자아이디와 문자패스워드입니다.)
	*-6  : 수신자 전화번호 공백
	*-8  : 발신자 전화번호 공백
	*-9  : 전송내용 공백
	*-10 : 예약 날짜 이상
	*      (예약발송일자가 YYMMDD 형식이 아닐 경우 반환)
	*-11 : 예약 시간 이상
	*	   (예약시간이 hhmmss 형식이 아닐 경우 반환)
	*-12 : 예약 가능시간 지남
	*      (예약 발송시간이 현재 시간보다 과거인지 확인 부탁드립니다.)
	*-13 : 스팸 동의서가 접수되지 않음
	*-14 : URL/MMS/LMS 서비스를 신청하지 않음
	*-15 : 서버에 이미지 파일 업로드 실패
	*-16 : 지원하지 않는 파일 확장자(MMS인 경우)
	*-21 : 데이터베이스 연결실패(DB Connection Fail), 잘못된 형태의 데이터를 보냈을 때
	*-23 : 허용ip가 아닌 경우 반환
	*      (홈페이지 > 문자메세지 >서비스관리 > 서비스 신청내역 > 발송가능ip목록 내용을 확인해주시기 바랍니다.)
	*-25 : 주간 총 발송량 초과
	*-26 : 주간 URL 발송량 초과
	*-27 : 수/발신자 번호 동일
	*-28 : 메세지에 발송제한 키워드 존재
	*-30 : 등록되지 않은 발신번호 
	*-31 : 잘못된 발신번호
	*-40 : 스팸 발송 차단
	*-50 : 잘못된 전화번호
	*/

	echo "<script language='javascript'>";

	if ($result == "0") {
		if ($funcMode == "2") {
			echo "alert('예약내역이 없음');";
		}
		else {
			echo "alert('잔여량부족');";
		}
	}
	elseif ($result == "-1") {
		echo "alert('잘못된 sms_id와 패스워드 입력.');";
	}
	elseif ($result == "-2") {
		echo "alert('SMS 아이디 공백.');";
	}
	elseif ($result == "-3") {
		echo "alert('발송 모두 실패');";
	}
	elseif ($result == "-5") {
		echo "alert('잘못된 sms_id와 패스워드 입력.');";
	}
	elseif ($result == "-8") {
		echo "alert('발신자 전화번호 공백.');";
	}
	elseif ($result == "-9") {
		echo "alert('전송내용 공백.');";
	}
	elseif ($result == "-10") {
		echo "alert('예약 날짜 이상.');";
	}
	elseif ($result == "-11") {
		echo "alert('예약 시간 이상.');";
	}
	elseif ($result == "-12") {
		echo "alert('예약 가능 시간이 지났습니다.현재 시간 이후로 예약 하십시요.');";
	}
	elseif ($result == "-13") {
		echo "alert('스팸 동의서가 접수되지 않았습니다.');";
	}
	elseif ($result == "-14") {
		echo "alert('URL/ MMS/LMS 서비스를 신청하지 않음.');";
	}
	elseif ($result == "-23") {
		echo "alert('설정하신 허용ip 목록에 없습니다.');";
	}
	elseif ($result == "-25") {
		echo "alert('주간 총 발송량 초과');";
	}
	elseif ($result == "-26") {
		echo "alert('주간 URL 발송량 초과');";
	}
	elseif ($result == "-27") {
		echo "alert('수/발신자 번호 동일');";
	}
	elseif ($result == "-30") {
		echo "alert('등록되지 않은 발신번호');";
	}
	elseif ($result == "-50") {
		echo "alert('전화번호이상');";
	}
	elseif ($funcMode == "3"){
		if ($result == "0|0")
			echo "alert('만료 될 건이 없습니다.');";
		else{
			$res = explode("|",$result);
			echo "alert('".$res[0]."일 후 ".$res[1]."건이 만료됩니다.');";
		}
	}
	elseif ($funcMode == "4"){
		$res = explode("|",$result); // 주간총제한량(0), 주간URL제한량(1), 주간URL발송량(2), 주간URL제한량(3)
		echo "alert('주간총 제한량 : ".$res[0]." 주간 총 발송량 : ".$res[2]."\\n주간 URL 제한량 : ".$res[1]." 주간 URL 발송량 : ".$res[3]."');";
	}
	elseif ($result > 0) {
		if ($funcMode == "1")
			echo "alert('잔여량 : ".$result."건');";
		elseif ($funcMode == "2")
			echo "alert('".$result."건 예약취소 성공');";
		else
			echo "alert('".$result."건 전송 성공');";
	}
	else {
		echo "alert('Error Code ".$result."  ');";
	}
	echo "location.href='sms_main.php';";
	echo "</script>";

?>