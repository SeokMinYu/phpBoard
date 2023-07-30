<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>회원가입</title>
	<script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<form name="join" method="post" action="member_join_ok.php" onsubmit="return joinChk();">
		<table align="center">
			<tr>
				<td colspan="2" align="center"><h2>회원가입</h2></td>
			</tr>
			<tr>
				<th>아이디</th>
				<td><input type="text" name="newid" id="newid" /> <input type="button" onclick="checkID();" value="중복체크"/></td>
			</tr>
			<tr>
				<th>비밀번호</th>
				<td><input type="password" name="newpw" id="newpw"  placeholder="4글자 이상 입력"/></td>
			</tr>
			<tr>
				<th>비밀번호 확인</th>
				<td><input type="password" name="pwok" id="pwok"  placeholder="4글자 이상 입력"/></td>
			</tr>
			<tr>
				<th>이름</th>
				<td><input type="text" name="newname" id="newname"/></td>
			</tr>
			<tr>
				<th>생년월일</th>
				<td><input type="text" title="생년월일" name="birthNum" maxlength="6" onkeydown="return onlyNumber(event);" onkeyup='removeChar(event);' onchange='removeChar(event)'> ex) 960101</td>
			</tr>
			<tr>
				<th>
				지역
				</th>
				<td><select name="area">
							<option value="">선택해주세요</option>
							<option value="서울">서울</option>
							<option value="경기도">경기도</option>
							<option value="강원도">강원도</option>
							<option value="충청도">충청도</option>
							<option value="전라도">전라도</option>
							<option value="경상도">경상도</option>
							<option value="제주도">제주도</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>
				성별
				</th>
				<td>
					<input type="radio" name ="gender" value="male">남성
					<input type="radio" name ="gender" value="female">여성
				</td>
			</tr>
			<tr>
				<th>
				좋아하는 색
				</th>
				<td colspan="2"> 
					<input type="checkbox" name="color[]" value="red"/>빨강
					<input type="checkbox" name="color[]" value="orange"/>주황
					<input type="checkbox" name="color[]" value="yellow"/>노랑
					<input type="checkbox" name="color[]" value="green"/>초록
					<input type="checkbox" name="color[]" value="blue"/>파랑
					<input type="checkbox" name="color[]" value="navy"/>남색
					<input type="checkbox" name="color[]" value="puple"/>보라
				</td>
			</tr>
			<tr>
				<th>
					연락처
				</th>
				<td>
				<select name="Phone1">
						<option value="010">010</option>
						<option value="011">011</option>
						<option value="016">016</option>
						<option value="017">017</option>
						<option value="018">018</option>
						<option value="019">019</option>
						<option value="070">070</option>
						<option value="02">02</option>
						<option value="031">031</option>
						<option value="032">032</option>
						<option value="033">033</option>
						<option value="041">041</option>
						<option value="042">042</option>
						<option value="043">043</option>
						<option value="051">051</option>
						<option value="052">052</option>
						<option value="053">053</option>
						<option value="054">054</option>
						<option value="055">055</option>
						<option value="061">061</option>
						<option value="062">062</option>
						<option value="063">063</option>
						<option value="064">064</option>
					</select><span>-</span><input type="tel" title="연락처" name="Phone2" maxlength="4" onkeyup="removeChar(event)" onchange="removeChar(event)"><span>-</span><input type="tel" title="연락처" name="Phone3" maxlength="4" onkeyup="removeChar(event)" onchange="removeChar(event)">
				</td>
			</tr>
			<tr>
				<th>
				이메일
				</th>
				<td class="email_form">
					<input type="text" title="이메일" name="Email" maxlength="295"><span>@</span><input type="text" title="이메일" name="Email_sub" id="Email_sub" maxlength="295"> <select class="sel_email" onchange="email_sub(this.value);">
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
			<tr align="center">
			
				<td></td><td><input type="submit" value="가입"/><input type="hidden" id="gaya" value=""/>
				<input type="button" onclick="location.href='login.php'" value="취소"/></td>
				
			</tr>
		</table>
	</form>
</body>
<script>
	function checkID()
	{
		
		$.ajax({
			url : "./member_join_ajax.php",
			type : "POST",
			data : {"newid" : $('#newid').val()},
			success : function(data){

				if($('#newid').val() == "")
				{
					alert("아이디를 입력하세요.");
					}
					else if($('#newid').val() != "")
					{
						if(data == 0)
						{
							document.getElementById("gaya").value = document.getElementById("newid").value;
							alert("사용가능한 아이디 입니다.");
				
						}
						else
						{
							alert("사용중인 아이디 입니다.");
						
						}
					}

			}
		});
	}
		
	function joinChk() 
		{
			var m = document.join;

			if ( m.newid.value == "" ) 
			{
				alert("아이디를 입력해주세요");
				m.newid.focus();
				return false;
			}
			if( m.newpw.value == "" )
			{
				alert("비밀번호를 입력해주세요");
				m.newpw.focus();
				return false;
			}
			if(m.newpw.value.length < 4)
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
			if (m.gaya.value == "" || m.gaya.value != m.newid.value)
			{
				alert("아이디 중복체크를 다시하세요.");
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
				return false;
			}
			
			if( m.area.value == "" )
			{
				alert("지역을 선택해주세요.");
				m.area.focus();
				return false;
			}

			if ( m.Phone1.value == "" )
			{
				alert("연락처를 입력해주세요");
				m.Phone1.focus();
				return false;
			}

			if ( m.Phone2.value == "" )
			{
				alert("연락처를 입력해주세요");
				m.Phone2.focus();
				return false;
			}

			if ( m.Phone3.value == "" )
			{
				alert("연락처를 입력해주세요");
				m.Phone3.focus();
				return false;
			}

			if ( m.Email.value == "" )
			{
				alert("이메일을 입력해주세요");
				m.Email.focus();
				return false;
			}

			if ( m.Email_sub.value == "" )
			{
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