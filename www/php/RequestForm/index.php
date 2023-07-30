<!doctype html>
<?php
	include "userDB.php";

?>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<script src="//code.jquery.com/jquery.min.js"></script>
	<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<title>의뢰서</title>
</head>
	<body>
	<div>
		<form name="questionForm" method="post" action="question_proc.php" onsubmit ="return questionSubmit();">
			<div>
				<h2>견적의뢰서</h2>
				<table>
					<tbody>
						<tr>
							<th>성명</th>
							<td><input type="text" name="username" maxlength="255"/></td>
						</tr>
						<tr>
							<th>이메일</th>
							<td>
								<input type="text" name="useremail" maxlength="255"><span>@</span><input type="text" name="emailsub" id="emailsub" maxlength="255"> <select onchange="email_sub(this.value);">
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
							<th>연락처</th>
							<td class="phone_form">
								<select name="phone1">
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
								</select><span>-</span><input type="tel" name="phone2" maxlength="4" pattern="[0-9]+"/><span>-</span><input type="tel" name="phone3" maxlength="4" pattern="[0-9]+"/>
							</td>
						</tr>
						<tr>
							<th>주소</th>
							<td><input type="text" id="zip" name="useradd1" maxlength="5" readonly onclick="openDaumPostcode()" style="width:200px;"><input type="button" onclick="openDaumPostcode()" value="우편번호검색"><br/> <input type="text" id="addr01" name="useradd2" maxlength="20" readonly onclick="openDaumPostcode()" style="width:200px;"><input type="text" id="addr02" name="useradd3" maxlength="200" style="width:200px;">
							</td>
						</tr>
						<tr>
							<th>제목</th>
							<td><input type="text" name="usertitle" style="width:400px;"/></td>
						</tr>
						<tr>
							<th>내용</th>
							<td>
							<textarea style="width:400px; height:100px;" name="usercontent"/></textarea>
							</td>
						</tr>
						
					</tbody>
				</table>
				<p><input type="submit" value="확인"/></p>
			</div>
			<a href="admin/adminLogin.php">.</a>
		</form>
	</div>
	<script>
		function openDaumPostcode() {
			new daum.Postcode({
				oncomplete: function(data) {
					// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
					// 각 주소의 노출 규칙에 따라 주소를 조합한다.
					// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
					var fullAddr = ''; // 최종 주소 변수
					var extraAddr = ''; // 조합형 주소 변수

					// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
					if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
						fullAddr = data.roadAddress;

					} else { // 사용자가 지번 주소를 선택했을 경우(J)
							fullAddr = data.jibunAddress;
					}

					// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
					if(data.userSelectedType === 'R'){
						//법정동명이 있을 경우 추가한다.
						if(data.bname !== ''){
							extraAddr += data.bname;
						}
						// 건물명이 있을 경우 추가한다.
						if(data.buildingName !== ''){
							extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
						}
						// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
						fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
					}

					// 우편번호와 주소 정보를 해당 필드에 넣는다.
					document.getElementById('zip').value = data.zonecode; //5자리 새우편번호 사용
					document.getElementById('addr01').value = fullAddr;

					// 커서를 상세주소 필드로 이동한다.
					document.getElementById('addr02').focus();
				}
			}).open();
		}
		function email_sub(myval){
			if (myval == ""){
				$("#emailsub").val("");
				$("#emailsub").attr("readonly", false); //설정
			}else{
				$("#emailsub").val(myval);
				$("#emailsub").attr("readonly", true); //설정
			}
		}
		function questionSubmit(){

			var u = document.questionForm;

			if ( u.username.value == "" )
				{
				alert("성명을 입력해주세요");
				u.username.focus();
				return false;
			}
			
			if ( u.useremail.value == "" )
				{
				alert("이메일을 입력해주세요");
				u.useremail.focus();
				return false;
			}

			if ( u.emailsub.value == "" )
				{
				alert("이메일 상세를 입력해주세요");
				u.emailsub.focus();
				return false;
			}
			
			if ( u.phone2.value == "" )
				{
				alert("연락처를 입력해주세요");
				u.Phone2.focus();
				return false;
			}

			if ( u.phone3.value == "" )
				{
				alert("연락처를 입력해주세요");
				u.Phone3.focus();
				return false;
			}

			if ( u.zip.value == "" )
				{
				alert("주소를 입력해주세요");
				u.zip.focus();
				return false;
			}

			if ( u.addr01.value == "" )
				{
				alert("주소를 입력해주세요");
				u.addr01.focus();
				return false;
			}

			if ( u.addr02.value == "" )
				{
				alert("상세주소를 입력해주세요");
				u.addr02.focus();
				return false;
			}

			if ( u.usertitle.value == "" )
				{
				alert("제목을 입력해주세요");
				u.usertitle.focus();
				return false;
			}

		
	}
	</script>
	</body>
</html>
