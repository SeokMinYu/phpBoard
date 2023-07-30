<?php
	include "DBconnect.php";
	include "login_check.php";

	$sql = mysqli_query($connect,"select * from member23 where seqno = '".$_SESSION['seq']."'");
	$member = mysqli_fetch_array($sql);

	$chk = explode(",",$member['userColor']);
	$phone = explode("-",$member['userPhone']);
	$email = explode("@",$member['userEmail']);
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>회원정보수정</title>
	<script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<form name="join" method="post" action="member_update_ok.php" onsubmit="return joinChk();">
	<input type="hidden" name="seq" value="<?=$_SESSION['seq'] ?>"/>
		<table align="center">
			<tr>
				<td colspan="2" align="center"><h2>회원정보수정</h2></td>
			</tr>
			<tr>
				<td>아이디</td>
				<td><?=$_SESSION['user_id']?></td>
			</tr>
			<tr>
				<td>현재 비밀번호</td>
				<td><input type="password" name="oldpw" id="oldpw"  placeholder="4글자 이상 입력"/></td>
			</tr>
			<tr>
				<td>변경 비밀번호</td>
				<td><input type="password" name="newpw" id="newpw"  placeholder="4글자 이상 입력"/></td>
			</tr>
			<tr>
				<td>비밀번호 확인</td>
				<td><input type="password" name="pwok" id="pwok"  placeholder="4글자 이상 입력"/></td>
			</tr>
			<tr>
				<td>이름</td>
				<td><input type="text" name="newname" id="newname" value="<?=$userName?>"/></td>
			</tr>
			<tr>
				<td>생년월일</td>
				<td><input type="text" title="생년월일" name="birthNum" maxlength="6" onkeydown="return onlyNumber(event);" onkeyup='removeChar(event);' onchange='removeChar(event)' value="<?=$member['userBrith']?>"> ex) 960101</td>
			</tr>
			<tr>
				<td>
				지역
				</td>
				<td><select name="area">
							<option value="">선택해주세요</option>
							<option value="서울" <?php if($member['userArea'] == "서울") { ?>selected<?}?>>서울</option>
							<option value="경기도" <?php if($member['userArea'] == "경기도") { ?>selected<?}?>>경기도</option>
							<option value="강원도" <?php if($member['userArea'] == "강원도") { ?>selected<?}?>>강원도</option>
							<option value="충청도" <?php if($member['userArea'] == "충청도") { ?>selected<?}?>>충청도</option>
							<option value="전라도" <?php if($member['userArea'] == "전라도") { ?>selected<?}?>>전라도</option>
							<option value="경상도" <?php if($member['userArea'] == "경상도") { ?>selected<?}?>>경상도</option>
							<option value="제주도" <?php if($member['userArea'] == "제주도") { ?>selected<?}?>>제주도</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
				성별
				</td>
				<td>
					<input type="radio" name ="gender" value="male" <?php if($member['userSex'] == "male") {?> checked<?} ?>>남성
					<input type="radio" name ="gender" value="female" <?php if($member['userSex'] == "female") {?> checked<?} ?>>여성
				</td>
			</tr>
			<tr>
				<td>
				좋아하는 색
				</td>
				<td colspan="2"> 
					<input type="checkbox" name="color[]" value="red" <?php if(in_Array("red",$chk)) {?>checked<?}?>>빨강
					<input type="checkbox" name="color[]" value="orange" <?php if(in_Array("orange",$chk)) {?>checked<?}?>>주황
					<input type="checkbox" name="color[]" value="yellow" <?php if(in_Array("yellow",$chk)) {?>checked<?}?>>노랑
					<input type="checkbox" name="color[]" value="green" <?php if(in_Array("green",$chk)) {?>checked<?}?>>초록
					<input type="checkbox" name="color[]" value="blue" <?php if(in_Array("blue",$chk)) {?>checked<?}?>>파랑
					<input type="checkbox" name="color[]" value="navy" <?php if(in_Array("navy",$chk)) {?>checked<?}?>>남색
					<input type="checkbox" name="color[]" value="puple" <?php if(in_Array("puple",$chk)) {?>checked<?}?>>보라
				</td>
			</tr>
			<tr>
				<td>
					연락처
				</td>
				<td>
				<select name="Phone1">
						<option value="010" <?php if(in_Array("010",$phone)) {?>selected<?}?>>010</option>
						<option value="011" <?php if(in_Array("011",$phone)) {?>selected<?}?>>011</option>
						<option value="016" <?php if(in_Array("016",$phone)) {?>selected<?}?>>016</option>
						<option value="017" <?php if(in_Array("017",$phone)) {?>selected<?}?>>017</option>
						<option value="018" <?php if(in_Array("018",$phone)) {?>selected<?}?>>018</option>
						<option value="023" <?php if(in_Array("023",$phone)) {?>selected<?}?>>019</option>
						<option value="070" <?php if(in_Array("070",$phone)) {?>selected<?}?>>070</option>
						<option value="02" <?php if(in_Array("02",$phone)) {?>selected<?}?>>02</option>
						<option value="031" <?php if(in_Array("031",$phone)) {?>selected<?}?>>031</option>
						<option value="032" <?php if(in_Array("032",$phone)) {?>selected<?}?>>032</option>
						<option value="033" <?php if(in_Array("033",$phone)) {?>selected<?}?>>033</option>
						<option value="041" <?php if(in_Array("041",$phone)) {?>selected<?}?>>041</option>
						<option value="042" <?php if(in_Array("042",$phone)) {?>selected<?}?>>042</option>
						<option value="043" <?php if(in_Array("043",$phone)) {?>selected<?}?>>043</option>
						<option value="051" <?php if(in_Array("051",$phone)) {?>selected<?}?>>051</option>
						<option value="052" <?php if(in_Array("052",$phone)) {?>selected<?}?>>052</option>
						<option value="053" <?php if(in_Array("053",$phone)) {?>selected<?}?>>053</option>
						<option value="054" <?php if(in_Array("054",$phone)) {?>selected<?}?>>054</option>
						<option value="055" <?php if(in_Array("055",$phone)) {?>selected<?}?>>055</option>
						<option value="061" <?php if(in_Array("061",$phone)) {?>selected<?}?>>061</option>
						<option value="062" <?php if(in_Array("062",$phone)) {?>selected<?}?>>062</option>
						<option value="063" <?php if(in_Array("063",$phone)) {?>selected<?}?>>063</option>
						<option value="064" <?php if(in_Array("064",$phone)) {?>selected<?}?>>064</option>
					</select><span>-</span><input type="tel" title="연락처" name="Phone2" maxlength="4" onkeyup="removeChar(event)" onchange="removeChar(event)" value="<?=$phone[1]?>"><span>-</span><input type="tel" title="연락처" name="Phone3" maxlength="4" onkeyup="removeChar(event)" onchange="removeChar(event)" value="<?=$phone[2]?>">
				</td>
			</tr>
			<tr>
				<td>
				이메일
				</td>
				<td class="email_form">
					<input type="text" title="이메일" name="Email" maxlength="255" value="<?=$email[0]?>"><span>@</span><input type="text" title="이메일" name="Email_sub" id="Email_sub" maxlength="255" value="<?=$email[1]?>"> <select class="sel_email" onchange="email_sub(this.value);">
						<option value="">직접입력</option>
						<option value="naver.com">naver.com</option>
						<option value="hanmail.net">hanmail.net</option>
						<option value="hotmail.com">hotmail.com</option>
						<option value="nate.com">nate.com</option>
						<option value="yahoo.co.kr">yahoo.co.kr</option>
						<option value="empas.com">empas.com</option>
						<option value="dreamwiz.com">dreamwiz.com</option>
						<option value="freechal.com">freechal.com</option>
						<option value="lycos.co.kr">lycos.co.kr</option>
						<option value="korea.com">korea.com</option>
						<option value="gmail.com">gmail.com</option>
						<option value="hanmir.com">hanmir.com</option>
						<option value="paran.com">paran.com</option> 
					</select>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2">
				<input type="submit" value="수정"/><input type="hidden" id="gaya" value=""/>
				<input type="button" onclick="location.href='list.php'" value="취소"/>
				</td>
				
			</tr>
		</table>
	</form>
</body>
<script>
	function joinChk() 
	{
		var m = document.join;

		if( m.oldpw.value == "")
		{
			alert("현재 비밀번호를 입력해주세요");
			m.oldpw.focus();
			return false;
		}
		if(m.newpw.value != "" && m.newpw.value.length < 4)
		{
			alert("비밀번호는 4글자 이상만 가능합니다.");
			m.newpw.focus();
			return false;
		}

		if(m.newpw.value != m.pwok.value)
		{
			alert("비밀번호가 다릅니다.");
			m.pwok.focus();
			return false;
		}

		if( m.newname.value == "" )
		{
			alert("이름을 입력해주세요");
			m.newname.focus();
			return false;
		}

		if( m.newname.value == "관리자" || m.newname.value == "운영자" )
		{
			alert("해당이름은 사용하실 수 없습니다.");
			m.newname.focus();
			return false;
		}

		if( m.birthNum.value == "" )
		{
			alert("생년월일을 입력해주세요");
			m.birthNum.focus();
			return false;
		}

		if( m.gender.value == "" )
		{
			alert("성별을 선택해주세요.");
			m.gender.focus();
			return false;
		}
		
		if( m.area.value == "" )
		{
			alert("지역을 선택해주세요.");
			m.area.focus();
			return false;
		}

		if ( m.Phone1.value == "" ){
			alert("연락처를 입력해주세요");
			m.Phone1.focus();
			return false;
		}

		if ( m.Phone2.value == "" ){
			alert("연락처를 입력해주세요");
			m.Phone2.focus();
			return false;
		}

		if ( m.Phone3.value == "" ){
			alert("연락처를 입력해주세요");
			m.Phone3.focus();
			return false;
		}

		if ( m.Email.value == "" ){
			alert("이메일을 입력해주세요");
			m.Email.focus();
			return false;
		}

		if ( m.Email_sub.value == "" ){
			alert("이메일 상세를 입력해주세요");
			m.Email_sub.focus();
			return false;
		}


	}

	function onlyNumber(event)
	{
		event = event || window.event;
		var keyID = (event.which) ? event.which : event.keyCode;
		if ( (keyID >= 48 && keyID <= 57) || (keyID >= 96 && keyID <= 105) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 || keyID == 9 || keyID == 46 ) 
			return;
		else
			return false;
	}
	 
	function removeChar(event) 
	{
		event = event && window.event;
		var keyID = (event.which) ? event.which : event.keyCode;
		if ( keyID == 8 && keyID == 46 && keyID == 37 && keyID == 39 ) 
			return;
		else
			event.target.value = event.target.value.replace(/[^0-9]/g, "");
	}

	function email_sub(myval)
	{
		if (myval == ""){
			$("#Email_sub").val("");
			$("#Email_sub").attr("readonly", false); //설정
		}else{
			$("#Email_sub").val(myval);
			$("#Email_sub").attr("readonly", true); //설정
		}
	}
	
</script>
</html>