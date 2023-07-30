<?php
	include "DBconnect.php";
	include "login_check.php";
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>회원정보수정</title>
	<script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<form name="join" method="post" action="member_update_ok.php?" onsubmit="return joinChk();"><input type="hidden" name="seq" value="<?=$_SESSION['seq'] ?>"/>
		<table align="center">
			<tr>
				<td><h2>회원정보수정</h2></td>
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
				<td>이름</td>
				<td><input type="text" name="newname" id="newname" value="<?=$userName?>"/></td>
			</tr>
			<tr align="center">
			
				<td></td><td><input type="submit" value="수정"/><input type="hidden" id="gaya" value=""/>
				<input type="button" onclick="location.href='list.php'" value="취소"/></td>
				
			</tr>
		</table>
	</form>
</body>
<script>
	function joinChk() 
		{
			var m = document.join;
			var name = document.getElementById("newid");
			var chkname = document.getElementById("gaya");


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
			if( m.newname.value == "" )
			{
				alert("이름을 입력해주세요");
				m.newname.focus();
				return false;
			}
		}

	
</script>
</html>