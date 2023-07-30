<?php

	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";

	$bno = $_REQUEST['idx'];

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>
<script type="text/javascript" src="../board/smarteditor2-archive/js/HuskyEZCreator.js" charset="utf-8"></script>
<script src="https://www.google.com/recaptcha/api.js?render=sitekey"></script>
</head>
<body>
	<div align="center">
	<h1 onclick="location.href='list.php'">게시판</h1>
		<form name="writeForm" method="post" enctype="multipart/form-data" onsubmit="return writeChk(<?=$bno?>);" action="list_write_ok.php">
		<input type="hidden" name="idx" value="<?=$bno?>" />
		<table>
			<tr>
				<td>
					글쓴이
				</td>
				<td>
					 <?=$userName?>
				</td>
			</tr>
			<tr>
				<td>
					제목
				</td>
				<td>
					<input name="utitle" id="utitle" maxlength="100"/>
				</td>
			</tr>
			<tr>
			<td></td>
			<td>
			지역 : <select name="selectBox">
						<option value="">선택해주세요</option>
						<option value="seoul">서울</option>
						<option value="daejeon">대전</option>
						<option value="daegu">대구</option>
						<option value="busan">부산</option>
				</select>
			성별 : <input type="radio" name ="gender" value="male"/>남성
					<input type="radio" name ="gender" value="female">여성
			</td>
			</tr>
			<tr>
				<td>
				좋아하는 색
				</td>
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
				<td>내용</td>
				<td style="width:460px;">
					<textarea name="ucontent" id="ucontent" style="width:450px; height:200px;"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<input type="file" name="u_file[]" id="u_file">
				</td>
				<td>
					<input type="button" onclick="add_row()" value="추가">
					<input type="button" onclick="delete_row()" value="삭제">
				</td>
			</tr>
			<tbody id="upload" align="center"></tbody>

			</table>
			<br>
			<div id="grecaptcha" class="g-recaptcha" data-sitekey="6LfAx2IaAAAAAIzNbykF8f4uYn9k-FyoNYyZJZis">
			</div>
			<div>
				<br><br>
					<input type="submit" value=" 글 작성 "/>
					<input type="button" onclick="location.href='list.php'" value="취소" />
			</div>
		</form>
		<script type="text/javascript">

			var oEditors = [];
			nhn.husky.EZCreator.createInIFrame({
				oAppRef: oEditors, 
				elPlaceHolder: "ucontent", 
				sSkinURI: "../board/smarteditor2-archive/SmartEditor2Skin.html",
				fCreator: "createSEditor2"
			});

			function writeChk(bno) 
			{
				var ck = document.writeForm;

				oEditors.getById["ucontent"].exec("UPDATE_CONTENTS_FIELD", []); 
				try {
					elClickedObj.form.submit();
				} catch(e) {}

				if ( ck.utitle.value == "" ) 
				{
					alert("제목을 입력해주세요");
					ck.utitle.focus();
					return false;
				}

				if( ck.ucontent.value == "" ) 
				{
					alert("내용을 입력해주세요");
					ck.ucontent.focus();
					return false;
				}

				if (typeof(grecaptcha) != 'undefined') 
				{
					if(grecaptcha.getResponse() == "") {
					alert("자동등록방지 문구를 확인해주세요");
					return false;
					}
				}
			}

			function add_row() 
			{
				var my_tbody = document.getElementById('upload');
				// var row = my_tbody.insertRow(0); // 상단에 추가
				var row = my_tbody.insertRow( my_tbody.rows.length ); // 하단에 추가
				var cell1 = row.insertCell(0);								
				cell1.innerHTML = '<td></td><td><input type="file" name="u_file[]" id="u_file"></td>';
			}

			function delete_row()
			{
				var upload = document.getElementById('upload');
				if (upload.rows.length < 1) return;
				// upload.deleteRow(0); // 상단부터 삭제
				upload.deleteRow( upload.rows.length-1 ); // 하단부터 삭제
			}
		</script>
		</div>
</body>
</html>
