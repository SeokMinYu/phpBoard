<?php
	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";

	$bno = $_REQUEST['idx'];
	$sql = mysqli_query($connect,"select * from board19 where idx='".$bno."';");
	$board = mysqli_fetch_array($sql);

	if( !( $_SESSION['user_id'] == $board['userId'] || $_SESSION['user_id'] == "admin" ) )
	{
		echo '<script>alert("접근할 수 없습니다."); history.back(); </script>';
	}
	$chk = explode(",",$board['checkBox']);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>
<script type="text/javascript" src="../board/smarteditor2-archive/js/HuskyEZCreator.js" charset="utf-8"></script>
</script>
<script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<div align="center"> 
	<h1>수정하기</h1>

		<form name="writeForm" method="post" enctype="multipart/form-data" onsubmit="return writeChk();" action="list_modify_ok.php">
		<input type="hidden" name="idx" value="<?=$bno ?>">
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
					<input name="utitle" id="utitle" maxlength="100" value="<?=$board['title']?>">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
				지역 : <select name="selectBox">
							<option value="">선택해주세요</option>
							<option value="seoul" <?php if($board['selectBox'] == "seoul") { ?>selected<?}?> >서울</option>
							<option value="daejeon" <?php if($board['selectBox'] == "daejeon") { ?>selected<?}?>>대전</option>
							<option value="daegu" <?php if($board['selectBox'] == "daegu") { ?>selected<?}?>>대구</option>
							<option value="busan" <?php if($board['selectBox'] == "busan") { ?>selected<?}?>>부산</option>
						</select>
				성별 : <input type="radio" name ="gender" value="male" <?php if($board['radioBtn'] == "male") {?> checked<?} ?>/>남성
						<input type="radio" name ="gender" value="female" <?php if($board['radioBtn'] == "female") {?> checked<?} ?>/>여성
				</td>
			</tr>
			<tr>
				<td>
				좋아하는 색
				</td>
				<td colspan="2">
					<input type="checkbox" name="color[]" value="red" <?php if(in_Array("red",$chk)) {?>checked<?}?>/>빨강
					<input type="checkbox" name="color[]" value="orange" <?php if(in_Array("orange",$chk)) {?>checked<?}?>/>주황
					<input type="checkbox" name="color[]" value="yellow" <?php if(in_Array("yellow",$chk)) {?>checked<?}?>/>노랑
					<input type="checkbox" name="color[]" value="green" <?php if(in_Array("green",$chk)) {?>checked<?}?>/>초록
					<input type="checkbox" name="color[]" value="blue" <?php if(in_Array("blue",$chk)) {?>checked<?}?>/>파랑
					<input type="checkbox" name="color[]" value="navy" <?php if(in_Array("navy",$chk)) {?>checked<?}?>/>남색
					<input type="checkbox" name="color[]" value="puple" <?php if(in_Array("puple",$chk)) {?>checked<?}?>/>보라
				</td>
			</tr>
			<tr>
			<td>내용</td>
			<td style="width:460px;">
				<textarea name="ucontent" id="ucontent" style="width:450px; height:200px;"><?=$board['content']?></textarea>
			</td>
			</tr>
			<tr>
				<td id="filelist" colspan="2">
					<?php
					$filesql = mysqli_query($connect,"select * from upload19 where idx='".$bno."'");

					while($row = mysqli_fetch_array($filesql)) {
						?>
							<?=$row['realname']?>
							<input type='button' onclick='del("<?=$row['seqno']?>","<?=$row['idx']?>")' value='삭제'/>
						<?php
					}?>
				</td>
			</tr>
			</table>
			<div>		
				<table>
					<th><input type="file" name="u_file[]" id="u_file"></th>
					<td>
						<input type="button" onclick="add_row()" value="추가" />
						<input type="button" onclick="delete_row()" value="삭제" />
					</td>
					<tbody id="upload"></tbody>
				</table>
			</div>
			<br>
			<div id="grecaptcha" class="g-recaptcha" data-sitekey="6LfAx2IaAAAAAIzNbykF8f4uYn9k-FyoNYyZJZis">
			</div>
			<div>
				<br><br>
				<input type="submit" value=" 글 수정 "/>
				<input type="button" onclick="history.back()" value="취소" />
			</div>
		</form>
	</div>
</body>
	<script type="text/javascript">
		function del(sqn,idx)
		{
			$.ajax({
				url : "./file_delete.php",
				type : "POST",
				data : {"mode" : "insert","sqn":sqn,"idx":idx},
				success : function(data){
					console.log(data);
					$('#filelist').empty();
					$('#filelist').append(data);
				}
			
			});
		}
	</script>
	<script type="text/javascript">
		var oEditors = [];
		nhn.husky.EZCreator.createInIFrame({
			oAppRef: oEditors,
			elPlaceHolder: "ucontent", 
			sSkinURI: "../board/smarteditor2-archive/SmartEditor2Skin.html",
			fCreator: "createSEditor2"
		});
		function writeChk() {
			var ck = document.writeForm;

			oEditors.getById["ucontent"].exec("UPDATE_CONTENTS_FIELD", []); 
			try {
				elClickedObj.form.submit();
			} catch(e) {}

			if ( ck.utitle.value == "" ) {
				alert("제목을 입력해주세요");
				ck.utitle.focus();
				return false;
			}else if( ck.uname.value == "" ) {
				alert("글쓴이을 입력해주세요");
				ck.uname.focus();
				return false;
			}else if( ck.ucontent.value == "" ) {
				alert("내용을 입력해주세요");
				ck.ucontent.focus();
				return false;
			}else if( ck.upw.value == "" ) {
				alert("비밀번호를 입력해주세요");
				ck.upw.focus();
				return false;
			}
		}
		function add_row() {
			var my_tbody = document.getElementById('upload');
			var row = my_tbody.insertRow( my_tbody.rows.length );
			var cell1 = row.insertCell(0);								
			cell1.innerHTML = '<input type="file" name="u_file[]" id="u_file">';
		}

		function delete_row() {
			var upload = document.getElementById('upload');
			if (upload.rows.length < 1) 
			{
				return;
			}
			upload.deleteRow( upload.rows.length-1 );
		}
	</script>
</html>
