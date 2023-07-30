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
				<td><h2>회원가입</h2></td>
			</tr>
			<tr>
				<td>아이디</td>
				<td><input type="text" name="newid" id="newid" /></td><td><input type="button" onclick="checkID();" value="중복체크"/></td>
			</tr>
			<tr>
				<td>비밀번호</td>
				<td><input type="password" name="newpw" id="newpw"  placeholder="4글자 이상 입력"/></td>
			</tr>
			<tr>
				<td>비밀번호 확인</td>
				<td><input type="password" name="pwok" id="pwok"  placeholder="4글자 이상 입력"/></td>
			</tr>
			<tr>
				<td>이름</td>
				<td><input type="text" name="newname" id="newname"/></td>
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

</script>

<script>
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
		}

	
</script>
</html>