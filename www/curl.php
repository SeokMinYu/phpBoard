<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
	<?
		include "DBconnect.php";

		$sql = mysqli_query($connect,"SELECT * FROM T_CURL WHERE seqno = 1");
		$row = mysqli_fetch_array($sql);

		//$headers = array(
		//	'Content-Type:application/x-www-form-urlencoded;charset=euc_kr',
		//	'Referer:http://www.gayainternet.com'
		//);
		//
		//$send_number = "028368145";
		//$receive_number = "01030824355";
		////$receive_number = "01056454784";
		//$biz_id = "gayainternet";
		//$smskey = "XONDA_S31X8GN9DVK";
		//$return_url = "https://tjrals627.cafe24.com/info.php";
		//$data = "send_number=".$send_number."&receive_number=".$receive_number."&biz_id=".$biz_id."&smskey=".$smskey."&return_url=".$return_url."&sms_contents=".urlencode("gaya")."&reserved_flag=false&reserved_year=&reserved_month=&reserved_day=&reserved_hour=&reserved_minute=&usrdata1=a&usrdata2=b&usrdata3=c";
		//
		//$url = "http://biz.xonda.net/biz/biz_newV2/SMSASP_WEBV4_s.asp";
		//
		//$ch = curl_init();                                 //curl 초기화
		//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		//요청 결과를 문자열로 반환 
		//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);       //POST data
		//curl_setopt($ch, CURLOPT_POST, true);              //true시 post 전송 
		//curl_setopt($ch, CURLOPT_SSLVERSION,3);			//ssl 셋팅
		//curl_setopt($ch, CURLOPT_HEADER, 1 );			// 호출의 헤더값
		////일부 사이트의 경우 referer을 검증 값으로 사용할 수 있음
		//curl_setopt($ch, CURLOPT_REFERER, 'https://example.com'); // https, http 구분
		//
		//
		//$response = curl_exec($ch); //cual 실행
		//curl_close($ch);
		//
		//echo $response;
		//echo curl_errno($ch);
	
	?>

  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <script src="//code.jquery.com/jquery.min.js"></script>
  <title>Document</title>
 </head>
 <body>
	<div id="cnum"><?=$row['result']?></div>
	<input type="button" onclick="numADD(<?=$row['seqno']?>)" value="클릭">
 </body>
 <script>
	function numADD(seqno)
	{
		$.ajax({
			url : "curl_ok.php",
			type : "POST",
			data : {"seqno":seqno},
			success : function(data){
				console.log(data);
				$('#cnum').empty();
				$('#cnum').append(data);
			}
		
		});
	}
 </script>
</html>
