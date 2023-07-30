<?php
/* 주의 사항 
   1. P_RETURN_URL 페이지는 결제 결과에 대한 내용을 전달하지 않습니다. 
   2. 하기 명시된 내역 참고하여 페이지 구현될 수 있도록 진행 부탁드립니다. 
      	- oid를 가지고 P_NOTI_URL에서 처리한(DB에 입력된) 데이터를 가져와서 보여준다.
	      - P_NOTI_URL이 먼저 호출되고,  P_RETURN_URL이 호출되는 것이라서 일반적인 경우는 문제가 없지만,
	      - P_NOTI_URL쪽 처리가 지연되는 경우가 발생하면, 이 페이지가 호출되는 시점에 상점DB상에 데이터가 
	      - 존재하지 않을 수 있음. 따라서 select 결과가 없다고 무조건 승인 데이터가 없는 것은 아님
*/

	//return_url 뒤에 get방식으로 전달한 주문식별자 (ex: P_RETURN_URL = http://www.inicis.com/mx_rreturn.php?oid=123456)
	$oid = $_GET['P_OID'];  
	
	//String SQL_QUERY = "select * from 상점테이블 where oid = 'oid' ";
	//쿼리 실행
	
	
  if(true){  // 쿼리결과가 있으면

		if($row[6] == "00") {  // 결과값중 P_STATUS 가 "00" 승인일 경우 
			echo "결제종류 : " . $paymethod_type . "<br />";
			echo $row[13]. "원 결제가 성공하였습니다. <br />";
			if($row[7] =="CARD") echo "승인번호 : " . $row[17];
			if($row[7] =="VBANK") {
				$explode_data = explode('|', $row[14]);
				$aaa = explode('=', $explode_data[0]);
				$bbb = explode('=', $explode_data[1]);

			}
		}
		else {  // P_STATUS 가 "01" 실패일 경우 
			echo "결제가 실패하였습니다. <br />";
		}
	}
	else {  // 쿼리 결과가 없으면 
	  //  처리지연 가능성 출력 / 개인 페이지에서 결제내역을 다시 확인하시기 바랍니다.
		echo "승인 내역이 존재하지 않습니다. 승인내역 처리가 늦어서 조회되지 않는 경우일 수 있습니다. 개인결제내역 페이지에서 내역을 다시 확인해 주시기 바랍니다.";
	}

		


?>

</BODY>
</HTML>
