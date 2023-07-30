<?php
	include "../../DBconnect.php";
	include "../../login_check.php";
	require("./libs/INImx.php");
	
	$inimx = new INImx;


	/////////////////////////////////////////////////////////////////////////////
	///// 1. ���� �ʱ�ȭ �� POST ������ ����                                 ////
	/////////////////////////////////////////////////////////////////////////////
	
	$inimx->reqtype 		= "PAY";  //������û���
	$inimx->inipayhome 	= "/home/today/mobile"; //�αױ�� ��� (�� ��ġ�� ���������� log���� ���� �� log������ ���� 777 ���� ����)
	$inimx->status			= $P_STATUS;
	$inimx->rmesg1			= $P_RMESG1;
	$inimx->tid		= $P_TID;
	$inimx->req_url		= $P_REQ_URL;
	$inimx->noti		= $P_NOTI;
	
	
	/////////////////////////////////////////////////////////////////////////////
	///// 2. ���� ���̵� ���� :                                              ////
	/////    ������û ���������� ����� MID���� �����ϰ� �����ؾ� ��...      ////
	/////    ����TID�� �߶� ��밡�� : substr($P_TID,'10','10');           ////
	/////////////////////////////////////////////////////////////////////////////
	$inimx->id_merchant = substr($P_TID,'10','10');  //
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////
	///// 3. ������� Ȯ�� :                                                 ////
	/////    �������� ������ ����/���п� ���� ó�� ���                      ////
	/////////////////////////////////////////////////////////////////////////////
  if($inimx->status =="00")   // ����� ������ ������
  {


	/////////////////////////////////////////////////////////////////////////////
	///// 4. ���ο�û :                                                      ////
	/////    ����������  P_REQ_URL�� ���ο�û�� ��...                        ////
	/////////////////////////////////////////////////////////////////////////////
	  $inimx->startAction();  // ���ο�û
	  
	  
	  
	  $inimx->getResult();  //���ΰ�� �Ľ�, P_REQ_URL���� ������ ����� �Ľ� 
	  
	  
	  /**
	  ����� �Ľ� ������ INImx�� ������ ��� ǥ���ϰ� �ֽ��ϴ�. ( �޴���� �� �����Ͽ� �ʿ��� �� ������ �� �ֵ��� ��Ź�帳�ϴ�.)
	  
	      --����
				$this->m_tid  = $resultString['P_TID'];                                     // �ŷ���ȣ
				$this->m_resultCode = $resultString['P_STATUS'];                            // �ŷ����� - ���Ұ�� ����:00, ����:00 �̿� ����
				$this->m_resultMsg  = $resultString['P_RMESG1'];                            // ���� ��� �޽���
				$this->m_cardQuota  = $resultString['P_RMESG2'];                            // �ſ�ī�� �Һ� ���� �� (�޴��� Ȯ�� �ʿ�)
				$this->m_payMethod = $resultString['P_TYPE'];                               // ���Ҽ��� 
				$this->m_mid  = $resultString['P_MID'];                                     // �������̵�
				$this->m_moid  = $resultString['P_OID'];                                    // �����ֹ���ȣ
				$this->m_resultprice = $resultString['P_AMT'];                              // �ŷ��ݾ�
				$this->m_buyerName  = $resultString['P_UNAME'];                             // �����ڸ�
				$this->m_nextUrl  = $resultString['P_NEXT_URL'];                            // ������ ���� P_NEXT_URL 
				$this->m_notiUrl  = $resultString['P_NOTEURL'];                             // ������ ���� NOTE_URL --->>�̰ŵ� ���� �����ϳ� 
				$this->m_authdt  = $resultString['P_AUTH_DT'];                              // ��������(YYYYmmddHHmmss)
				$this->m_pgAuthDate  = substr($resultString['P_AUTH_DT'],'0','8');          
				$this->m_pgAuthTime  = substr($resultString['P_AUTH_DT'],'8','6');          
				$this->m_mname  = $resultString['P_MNAME'];                                 // ��������
				$this->m_noti  = $resultString['P_NOTI'];                                   // ��Ÿ�ֹ�����
				$this->m_authCode = $resultString['P_AUTH_NO'];                             // �ſ�ī�� ���ι�ȣ - �ſ�ī�� �ŷ������� ���		
				$this->m_cardCode = $resultString['P_FN_CD1'];                              // ī���ڵ� 
				
				
				--�ſ�ī��		
        $this->m_cardIssuerCode = $resultString['P_CARD_ISSUER_CODE'];              // �߱޻� �ڵ� 
				$this->m_cardNum  = $resultString['P_CARD_NUM'];                            // ī���ȣ 
				$this->m_cardMumbernum  = $resultString['P_CARD_MEMBER_NUM'];               // ��������ȣ
				$this->m_cardpurchase  = $resultString['P_CARD_PURCHASE_CODE'];             // ���Ի� �ڵ� 
				$this->m_prtc  = $resultString['P_CARD_PRTC_CODE'];                         // �κ���� ���� ����
				$this->m_cardinterest  = $resultString['P_CARD_INTEREST'];                  // ������ �Һο��� (�Ϲ� : 0, ������ : 1)
				$this->m_cardcheckflag  = $resultString['P_CARD_CHECKFLAG'];                // üũī�忩�� (�ſ�ī��:0, üũī��:1, ����Ʈī��:2)
				$this->m_cardName  = $resultString['P_FN_NM'];                              // ����ī���ѱ۸�
				$this->m_cardSrcCode  = $resultString['P_SRC_CODE'];                        // �ۿ��� ���� P : ������, K : ���ξ�ī��
				
				
				--�޴���
				$this->m_codegw  = $resultString['P_HPP_CORP'];                             // �޴��� ��Ż��ڵ�
				$this->m_hppapplnum  = $resultString['P_APPL_NUM'];                         // �޴������� ���ι�ȣ
				$this->m_hppnum  = $resultString['P_HPP_NUM'];                              // �� �޴��� ��ȣ
				
				
				--�������
				$this->m_vacct  = $resultString['P_VACT_NUM'];                              // �Ա��� ���� ��ȣ
				$this->m_dtinput = $resultString['P_VACT_DATE'];                            // �Աݸ�������(YYYYmmdd)
        $this->m_tminput = $resultString['P_VACT_TIME'];                            // �Աݸ����ð�(hhmmss)
				$this->m_nmvacct = $resultString['P_VACT_NAME'];                            // �����ָ�
				$this->m_vcdbank = $resultString['P_VACT_BANK_CODE'];                       // �����ڵ�
	  */
	if($inimx->m_resultCode == "00") {

		$memberSql = mysqli_query($connect,"update member30 set memberPay=1, memberPayDate=now() where memberId = '".$user_id."'");

		$paySql = mysqli_query($connect,"insert into pay_result (memberId,T_id,createDate) values ('".$user_id."','".$inimx->m_tid."',now())");
		
		echo "<script>alert('������ �Ϸ�Ǿ����ϴ�.');location.href='https://tjrals627.cafe24.com/php/board30/m/list.php';</script>";
		exit;
	}
	else{
		echo "<script>alert('���� �����Ͽ����ϴ�.');location.href='https://tjrals627.cafe24.com/php/board30/m/list.php';</script>";
		exit;
	}
	  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html> 
<head> 
<title>INIpay Mobile WEB example</title> 
<meta http-equiv="Expires" content="0"/> 
<meta name="Author" content="yw0399"/> 
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr"/> 
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
<head>

<body>
<?	  
	  
	 
  	 switch($inimx->m_payMethod)
  	 {   
      
      case(CARD):  //�ſ�ī�� �Ƚ�Ŭ��
      
      
  	   echo("���ΰ���ڵ�:".$inimx->m_resultCode."<br>");
  		 echo("����޽���:".$inimx->m_resultMsg."<br>");
  		 echo("���Ҽ���:".$inimx->m_payMethod."<br>");
  		 echo("�ֹ���ȣ:".$inimx->m_moid."<br>");
  		 echo("TID:".$inimx->m_tid."<br>");
  		 echo("���αݾ�:".$inimx->m_resultprice."<br>");
  		 echo("������:".$inimx->m_pgAuthDate."<br>");
  		 echo("���νð�:".$inimx->m_pgAuthTime."<br>");
  		 echo("����ID:".$inimx->m_mid."<br>");
  		 echo("�����ڸ�:".$inimx->m_buyerName."<br>");
  		 echo("P_NOTI:".$inimx->m_noti."<br>");
  		 echo("NEXT_URL:".$inimx->m_nextUrl."<br>");
  		 echo("NOTI_URL:".$inimx->m_notiUrl."<br>");
       echo("���ι�ȣ:".$inimx->m_authCode."<br>");
  		 echo("�Һΰ���:".$inimx->m_cardQuota."<br>");
  		 echo("ī���ڵ�:".$inimx->m_cardCode."<br>");
  		 echo("�߱޻��ڵ�:".$inimx->m_cardIssuerCode."<br>");
  		 echo("ī���ȣ:".$inimx->m_cardNumber."<br>");
  		 echo("��������ȣ:".$inimx->m_cardMember."<br>");
  		 echo("���Ի��ڵ�:".$inimx->m_cardpurchase."<br>");
  		 echo("�κ���Ұ��ɿ���(0:�Ұ�, 1:����):".$inimx->m_prtc."<br>");
  
  		
  		break;
  		
  	  case(MOBILE):  //�޴�������
      
  	   echo("���ΰ���ڵ�:".$inimx->m_resultCode."<br>");
  		 echo("����޽���:".$inimx->m_resultMsg."<br>");
  		 echo("���Ҽ���:".$inimx->m_payMethod."<br>");
  		 echo("�ֹ���ȣ:".$inimx->m_moid."<br>");
  		 echo("TID:".$inimx->m_tid."<br>");
  		 echo("���αݾ�:".$inimx->m_resultprice."<br>");
  		 echo("������:".$inimx->m_pgAuthDate."<br>");
  		 echo("���νð�:".$inimx->m_pgAuthTime."<br>");
  		 echo("����ID:".$inimx->m_mid."<br>");
  		 echo("�����ڸ�:".$inimx->m_buyerName."<br>");
  		 echo("P_NOTI:".$inimx->m_noti."<br>");
  		 echo("NEXT_URL:".$inimx->m_nextUrl."<br>");
  		 echo("NOTI_URL:".$inimx->m_notiUrl."<br>");
       echo("��Ż�:".$inimx->m_codegw."<br>");
  		
  		break;
  		
  		case(VBANK):  //�������
      
  	   echo("���ΰ���ڵ�:".$inimx->m_resultCode."<br>");
  		 echo("����޽���:".$inimx->m_resultMsg."<br>");
  		 echo("���Ҽ���:".$inimx->m_payMethod."<br>");
  		 echo("�ֹ���ȣ:".$inimx->m_moid."<br>");
  		 echo("TID:".$inimx->m_tid."<br>");
  		 echo("���αݾ�:".$inimx->m_resultprice."<br>");
  		 echo("��û��:".$inimx->m_pgAuthDate."<br>");
  		 echo("��û�ð�:".$inimx->m_pgAuthTime."<br>");
  		 echo("����ID:".$inimx->m_mid."<br>");
  		 echo("�����ڸ�:".$inimx->m_buyerName."<br>");
  		 echo("P_NOTI:".$inimx->m_noti."<br>");
  		 echo("NEXT_URL:".$inimx->m_nextUrl."<br>");
  		 echo("NOTI_URL:".$inimx->m_notiUrl."<br>");
  		 echo("������¹�ȣ:".$inimx->m_vacct."<br>");
  		 echo("�Աݿ�����:".$inimx->m_dtinput."<br>");
  		 echo("�Աݿ����ð�:".$inimx->m_tminput."<br>");
  		 echo("������:".$inimx->m_nmvacct."<br>");
  		 echo("�����ڵ�:".$inimx->m_vcdbank."<br>");
  
  		break;
  		
  		default: //��ȭ��ǰ��,���ǸӴ�
  
       echo("���ΰ���ڵ�:".$inimx->m_resultCode."<br>");
  		 echo("����޽���:".$inimx->m_resultMsg."<br>");
  		 echo("���Ҽ���:".$inimx->m_payMethod."<br>");
  		 echo("�ֹ���ȣ:".$inimx->m_moid."<br>");
  		 echo("TID:".$inimx->m_tid."<br>");
  		 echo("���αݾ�:".$inimx->m_resultprice."<br>");
  		 echo("������:".$inimx->m_pgAuthDate."<br>");
  		 echo("���νð�:".$inimx->m_pgAuthTime."<br>");
  		 echo("����ID:".$inimx->m_mid."<br>");
  		 echo("�����ڸ�:".$inimx->m_buyerName."<br>");
  		 echo("P_NOTI:".$inimx->m_noti."<br>");
  		 echo("NEXT_URL:".$inimx->m_nextUrl."<br>");
  		 echo("NOTI_URL:".$inimx->m_notiUrl."<br>");
  	  }
	
	}
	else                      // ����� ���� ����
	{
	  echo("��������ڵ�:".$inimx->status);
	  echo("<br>");
	  echo("��������޽���:".$inimx->rmesg1);
	}
	  
  
?>

</body>
</html>
