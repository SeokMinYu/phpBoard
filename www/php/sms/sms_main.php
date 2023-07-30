<?php

	/* 작성자:너나우리 모바일팀(sms@majunsoft.com)
	* 작성일자:2019년 05월 15일
	* 작성목적: 본 프로그램은 너나우리 문자메세지에서 php 웹프로그램 관련 예제입니다.
	*             본 예제에서는 보내는 사람의 핸드폰번호, 받는사람 핸드폰번호,전송내용을 sms_process.php으로
	*             값을 전송하는 모듈입니다.
	*/
?>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<script language="javascript">
<!--
function frm_submit() {
	document.frm_sms_process.submit();
}
-->
</script>
<html>
<form name="frm_sms_process" method="post" action="sms_process.php">
<table width="50%" border="1">
	<tr>
		<td align="center">발신자번호</td>
		<td align="left">
			&nbsp;&nbsp;
			<input type="text" name="snd_number" size="12" maxlength="12" value="011xxxyyyy">
			&nbsp;<font color="red">*</font> 번호를 공백없이 입력
		</td>
	</tr>
	<tr>
		<td align="center">수신자번호</td>
		<td align="left">
			&nbsp;&nbsp;
			<input type="text" name="rcv_number" size="13" value="011nnnmmmm">
			&nbsp;<font color="red">*</font> 번호를 공백없이 입력
		</td>
	</tr>
	<tr>
		<td align="center">내용</td>
		<td align="left">
			&nbsp;&nbsp;
			<input type="text" name="sms_content" size="20" value="테스트내용">
		</td>
	</tr>
	<tr>
		<td align="center">예약1</td>
		<td align="left">
			&nbsp;&nbsp;
			<input type="text" name="reserve_date" size="8" maxlength="8" value="20110430">
			<br>&nbsp;&nbsp;<font color="red">*</font> 날짜를 공백없이 yyyymmdd형식에 맞게 입력
		</td>
	</tr>
	<tr>
		<td align="center">예약2</td>
		<td align="left">
			&nbsp;&nbsp;
			<input type="text" name="reserve_time" size="6" maxlength="6" value="153000">
			<br>&nbsp;&nbsp;<font color="red">*</font> 시간을 공백없이 hhmmss형식에 맞게 입력
		</td>
	</tr>
</table>
</form>
* 동보 전송을 위해서는 수신자번호를 , 로 구분하여 입력하세요.<br>
예)0101234567,0111234567,0121234567,0161234567,0171234567,0191234567<br><br>
<input type="button" name="frm_submit" value="전송하기" onClick="javascript:frm_submit();">
</html>