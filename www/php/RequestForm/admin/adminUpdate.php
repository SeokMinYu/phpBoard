<?php
	include "../userDB.php";
	include "logindex.php";
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>회원정보수정</title>
	<script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<form name="join" method="post" action="adminUpdate_ok.php?" onsubmit="return joinChk();">
		<table align="center">
			<tr>
				<td><h2>정보수정</h2></td>
			</tr>
			<tr>
				<td>현재 비밀번호</td>
				<td><input type="password" name="oldpw" id="oldpw"  placeholder="4글자 이상 입력"/></td>
			</tr>
			<tr>
				<td>변경 비밀번호</td>
				<td><input type="password" name="newpw" id="newpw"  placeholder="4글자 이상 입력"/></td>
			</tr>

			<tr align="center">
			
				<td></td><td><input type="submit" value="수정"/>
				<input type="button" onclick="location.href='adminPage.php'" value="취소"/></td>
				
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
		}

	
</script>
</html>