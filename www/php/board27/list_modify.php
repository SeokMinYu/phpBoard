<?php
	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";

	$bno = $_REQUEST['idx'];
	$sql = mysqli_query($connect,"select * from board27 where idx='".$bno."';");
	$board = mysqli_fetch_array($sql);

	if( !( $_SESSION['user_id'] == $board['userId'] || $_SESSION['user_id'] == "admin" ) )
	{
		echo '<script>alert("접근할 수 없습니다."); history.back(); </script>';
	}
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
				<th>
					글쓴이
				</th>
				<td>
					<?=$userName?>
				</td>
			</tr>
			<tr>
				<th>
					제목
				</th>
				<td>
					<input name="utitle" id="utitle" maxlength="100" value="<?=$board['title']?>">
				</td>
			</tr>
			<tr>
				<th>내용</th>
				<td style="width:460px;">
					<textarea name="ucontent" id="ucontent" style="width:450px; height:270px;"><?=$board['content']?></textarea>
				</td>
			</tr>
			<tr>
				<td id="filelist" colspan="2">
					<?php
					$filesql = mysqli_query($connect,"select * from upload27 where idx='".$bno."'");

					while($row = mysqli_fetch_array($filesql)) {
						?>
							<?=$row['realname']?>
							<input type='button' onclick='del("<?=$row['seqno']?>","<?=$row['idx']?>")' value='삭제'/>
						<?php
					}?>
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
