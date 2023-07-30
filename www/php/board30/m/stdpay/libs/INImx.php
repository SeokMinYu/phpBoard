<?

extract($_POST);

extract($_GET);

require("HttpClient.php");

define (HOST_IP, $host);
define (PAY_URL, $path);
define (VERSION, "(V1000B20111219PHP)");
	 
class INImx	
{
	//요청 구분 : 공통
	var $reqtype;
	
	//인증 및 승인요청
	var $inipayhome;
	var $id_merchant;
	var $status;
	var $rmesg1;
	var $tid;
	var $req_url;


	//클래스 내부 용
	var $m_serviceurl;
  var $m_resultCode;
  var $m_resultmsg;
  var $noti;
	var $m_resultprice; 
	var $m_pgAuthDate; 
	var $m_authCode;
	var $m_cardQuota; 
	var $m_cardCode;
	var $m_cardIssuerCode;
	var $m_vacct;
	var $m_vcdbank;
	var $m_dtinput;
	var $m_tminput;
	var $m_nminput;
	var $m_buyerName;
	var $m_nextUrl;
	var $m_notiUrl;
	var $m_prtc;
	
	var $m_authdt;
	var $m_mname;
	var $m_noti;
	var $m_cardinterest;
	var $m_cardcheckflag;
	var $m_hppapplnum;
	var $m_hppnum;
	
	

	function startAction()
	{
		$this->printLog("Start INImx_AUTH ".$this->reqtype.VERSION);
		$this->printLog("INIPAYHOME:".$this->inipayhome);
		$this->printLog("P_MID:".$this->id_merchant);
		$this->printLog("P_STATUS:".$this->status);
		$this->printLog("P_RMESG1:".$this->rmesg1);
		$this->printLog("P_TID:".$this->tid);
		$this->printLog("P_REQ_URL:".$this->req_url);
		$this->printLog("P_NOTI:".$this->noti);
		$this->printLog("AUTH Transaction End");

		switch($this->reqtype)
		{
			case("PAY"):
				$this->doPay();			
			break;

		}
//		$this->printLog("End INImx ".$this->reqtype.VERSION);
	}

	function doPay()
	{
	$msg  = "";
        $msg .= "P_MID=".$this->id_merchant."&";
        $msg .= "P_TID=".$this->tid."&";
        $msg .= "P_REQ_URL=".$this->req_url."&";
        $this->m_serviceurl = PAY_URL;
        $this->connectURL($msg);
	}

	

	function connectURL($msg)
	{
		$httpclient = new HttpClient("true", HOST_IP);
		
		$this->printLog("Start INImx_APPL");
		
		$this->printLog("Start HTTP Connect:".HOST_IP.$this->m_serviceurl);
    
		if($httpclient->HttpConnect())
   	{
			$this->printLog("HTTP CONNECTION SUCCESS");
     	if($httpclient->HttpRequest($this->m_serviceurl, $msg))
			{
				$this->printLog("RECV REQUEST:".trim($httpclient->getBody()));
       	// 응답 전문 파싱
				parse_str(trim($httpclient->getBody()), $resultString);
				
				
				//공통
				$this->m_tid  = $resultString['P_TID'];
				$this->m_resultCode = $resultString['P_STATUS'];
				$this->m_resultMsg  = $resultString['P_RMESG1'];
				$this->m_cardQuota  = $resultString['P_RMESG2'];
				$this->m_payMethod = $resultString['P_TYPE'];
				$this->m_mid  = $resultString['P_MID'];
				$this->m_moid  = $resultString['P_OID'];
				$this->m_resultprice = $resultString['P_AMT'];
				$this->m_buyerName  = $resultString['P_UNAME'];
				$this->m_nextUrl  = $resultString['P_NEXT_URL'];
				$this->m_notiUrl  = $resultString['P_NOTEURL'];
				$this->m_authdt  = $resultString['P_AUTH_DT'];  
				$this->m_pgAuthDate  = substr($resultString['P_AUTH_DT'],'0','8');
				$this->m_pgAuthTime  = substr($resultString['P_AUTH_DT'],'8','6');
				$this->m_authCode = $resultString['P_AUTH_NO'];				
				$this->m_cardCode = $resultString['P_FN_CD1'];
				$this->m_mname  = $resultString['P_MNAME'];  // 추가 
				$this->m_noti  = $resultString['P_NOTI'];  // 추가 
				
				
				//신용카드
        $this->m_cardIssuerCode = $resultString['P_CARD_ISSUER_CODE'];
				$this->m_cardNum  = $resultString['P_CARD_NUM'];
				$this->m_cardMembernum  = $resultString['P_CARD_MEMBER_NUM'];
				$this->m_cardpurchase  = $resultString['P_CARD_PURCHASE_CODE'];
				$this->m_prtc  = $resultString['P_CARD_PRTC_CODE'];
				$this->m_cardinterest  = $resultString['P_CARD_INTEREST'];  // 추가
				$this->m_cardcheckflag  = $resultString['P_CARD_CHECKFLAG'];  // 추가
				$this->m_cardName  = $resultString['P_FN_NM'];              
				$this->m_cardSrcCode  = $resultString['P_SRC_CODE'];               
				
				
				
				//휴대폰
				$this->m_codegw  = $resultString['P_HPP_CORP'];
				$this->m_hppapplnum  = $resultString['P_APPL_NUM'];  // 추가
				$this->m_hppnum  = $resultString['P_HPP_NUM'];  // 추가
				
				

				//가상계좌
				$this->m_vacct  = $resultString['P_VACT_NUM'];
				$this->m_dtinput = $resultString['P_VACT_DATE'];
        $this->m_tminput = $resultString['P_VACT_TIME'];
				$this->m_nmvacct = $resultString['P_VACT_NAME'];
				$this->m_vcdbank = $resultString['P_VACT_BANK_CODE'];

				
				
			}
     	else
     	{
				$this->printLog("HTTP REQUEST FAIL:".$httpclient->getErrorCode().":".$httpclient->getErrorMsg());
       	// 전문 응답 요청 오류
       	$this->m_resultCode = "05";
       	$this->m_resultmsg  = "HTTP REQUEST FAIL";
     	}
   	}
   	else
   	{
	$this->printLog("HTTP CONNECTION FAIL:".$httpclient->getErrorCode().":".$httpclient->getErrorMsg());
     	// 서버 접속 오류
     	$this->m_resultCode = "05";
     	$this->m_resultmsg  = "HTTP CONNECTION FAIL";
   	}
				
				$this->printLog("** COMMON *********");
    		$this->printLog("P_STATUS:".$this->m_resultCode);
    		$this->printLog("P_RMESG1:".$this->m_resultMsg);
    		$this->printLog("P_RMESG2:".$this->m_cardQuota);
    		$this->printLog("P_TYPE:".$this->m_payMethod);
    		$this->printLog("P_TID:".$this->m_tid);
    		$this->printLog("P_MID:".$this->m_mid);
    		$this->printLog("P_OID:".$this->m_moid);
    		$this->printLog("P_UNAME:".$this->m_buyerName);
    		$this->printLog("P_AMT:".$this->m_resultprice);
    		$this->printLog("P_AUTH_DT(YYYYmmddHHmmss):".$this->m_authdt);
    		$this->printLog("P_AUTH_DT:".$this->m_pgAuthDate);
    		$this->printLog("P_AUTH_TM:".$this->m_pgAuthTime);
    		$this->printLog("P_AUTH_NO:".$this->m_authCode);
    		$this->printLog("P_FN_CD1:".$this->m_cardCode);
    		$this->printLog("P_NEXT_URL:".$this->m_nextUrl);
    		$this->printLog("P_NOTEURL:".$this->m_notiUrl);
    		$this->printLog("P_MNAME:".$this->m_mname);
    		$this->printLog("P_NOTI:".$this->m_noti);
    		
    		$this->printLog("** CARD *********");               		
    		$this->printLog("P_CARD_ISSUER_CODE:".$this->m_cardIssuerCode);
    		$this->printLog("P_CARD_PURCHASE_CODE:".$this->m_cardpurchase);
    		$this->printLog("P_CARD_PRTC_CODE:".$this->m_prtc);
    		$this->printLog("P_CARD_INTEREST:".$this->m_cardinterest);
    		$this->printLog("P_CARD_CHECKFLAG:".$this->m_cardcheckflag);
    		$this->printLog("P_CARD_NUM:".$this->m_cardNum);
    		$this->printLog("P_CARD_MEMBER_NUM:".$this->m_cardMembernum);
    		$this->printLog("P_FN_NM:".$this->m_cardName);
    		$this->printLog("P_SRC_CODE:".$this->m_cardSrcCode);
    		
				
				$this->printLog("** HPP *********");  
				$this->printLog("P_APPL_NUM:".$this->m_hppapplnum);
    		$this->printLog("P_HPP_NUM:".$this->m_hppnum);
    		$this->printLog("P_HPP_CORP:".$this->m_codegw);


    		$this->printLog("** VACT *********");  
    		$this->printLog("P_VACT_NUM:".$this->m_vacct);
    		$this->printLog("P_VACT_BANK_CODE:".$this->m_vcdbank);
    		$this->printLog("P_VACT_DATE:".$this->m_dtinput);
    		$this->printLog("P_VACT_TIME:".$this->m_tminput);
    		$this->printLog("P_VACT_NAME:".$this->m_nmvacct);
    		

    		$this->printLog("APPL Transaction End");


    
	}
	
	function getResult()
	{
		return $this->m_resultCode;
		return $this->m_resultMsg;
		return $this->m_payMethod;
		return $this->m_moid;
		return $this->m_tid;
		return $this->m_buyerName;
		return $this->m_resultprice;
		return $this->m_pgAuthDate;
		return $this->m_pgAuthTime;
		return $this->m_authCode;
		return $this->m_cardQuota;
		return $this->m_cardCode;
		return $this->m_cardIssuerCode;
		return $this->m_cardpurchase;
		return $this->m_prtc;
		return $this->m_vacct;
		return $this->m_vcdbank;
		return $this->m_dtinput;
		return $this->m_tminput;
		return $this->m_nmvacct;
		return $this->m_buyerName;
		return $this->m_nextUrl;
		return $this->m_notiUrl;
		return $this->m_authdt;
		return $this->m_mname;
		return $this->m_noti;
		return $this->m_cardinterest;
		return $this->m_cardcheckflag;
		return $this->m_hppapplnum;
		return $this->m_hppnum;
		return $this->m_cardName;
		return $this->m_cardNum;
		return $this->m_cardMumbernum;
		return $this->m_cardName;
		return $this->m_codegw;
		return $this->m_cardSrcCode;



	}


	
	function printLog($msg)
	{
		$path = $this->inipayhome."/log/";
		$file = "INImx".$this->reqtype."_".$this->id_merchant."_".date("Ymd").".log";
		$msg_head = "[".date("Y-m-d")."_".date("H:i:s")."][P=".getmypid()."]";

		if(!is_dir($path)) 
		{
			mkdir($path, 0755);
		}
    	if(!($fp = fopen($path.$file, "a+"))) return 0;
        fwrite($fp, $msg_head);

		if(!empty($cmt) || !empty($line))
		{
			$cmt = basename($cmt);
      		fwrite($fp, "[$cmt($line)]");
    	}

		ob_start();
		print_r($msg);
		$ob_msg = ob_get_contents();
		ob_clean();

		if(fwrite($fp, " ".$ob_msg."\n") === FALSE)
		{
			fclose($fp);
			return 0;
		}
		fclose($fp);
		return 1;
	}
}
?>
