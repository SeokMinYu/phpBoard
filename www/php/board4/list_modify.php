<?php
	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";

	$bno = $_REQUEST['idx'];
	$sql = mysqli_query($db,"select * from board2 where idx='".$bno."';");
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

                <form name="writeForm" method="post" enctype="multipart/form-data" onsubmit="return writeChk();" action="modify_ok.php?idx=<?=$bno ?>">
                    <div>
                        글쓴이 : <?=$board['name']?>
                    </div>
					<div>
                        제목 : <input name="utitle" id="utitle" maxlength="100" value="<?=$board['title']?>" />
                    </div>
					지역 : <select name="selectBox">
						<option value="">선택해주세요</option>
						<option value="seoul" <?php if($board['selectBox'] == "seoul") echo "selected"; ?> >서울</option>
						<option value="daejeon" <?php if($board['selectBox'] == "daejeon") echo "selected"; ?>>대전</option>
						<option value="daegu" <?php if($board['selectBox'] == "daegu") echo "selected"; ?>>대구</option>
						<option value="busan" <?php if($board['selectBox'] == "busan") echo "selected"; ?>>부산</option>
					</select>
					| 성별 : <input type="radio" name ="gender" value="male" <?php if($board['radioBtn'] == "male") echo "checked"; ?>/>남성
							<input type="radio" name ="gender" value="female" <?php if($board['radioBtn'] == "female") echo "checked"; ?>/>여성
					<div>
					<?php 
						$chk = explode(",",$board['checkBox']);
					?>
					좋아하는 색 : 
						<input type="checkbox" name="color[]" value="red" <?php if(in_Array("red",$chk)) echo "checked"; ?>/>빨강
						<input type="checkbox" name="color[]" value="orange" <?php if(in_Array("orange",$chk)) echo "checked"; ?>/>주황
						<input type="checkbox" name="color[]" value="yellow" <?php if(in_Array("yellow",$chk)) echo "checked"; ?>/>노랑
						<input type="checkbox" name="color[]" value="green" <?php if(in_Array("green",$chk)) echo "checked"; ?>/>초록
						<input type="checkbox" name="color[]" value="blue" <?php if(in_Array("blue",$chk)) echo "checked"; ?>/>파랑
						<input type="checkbox" name="color[]" value="navy" <?php if(in_Array("rnavyed",$chk)) echo "checked"; ?>/>남색
						<input type="checkbox" name="color[]" value="puple" <?php if(in_Array("puple",$chk)) echo "checked"; ?>/>보라
					</div>
                    <div style="width : 500px">
                        내용 : <textarea name="ucontent" id="ucontent"><?=$board['content']?></textarea>
                    </div>
					<div id="filelist">
						<?php
							$filesql = mysqli_query($db,"select * from upload2 where idx='".$bno."'");

							while($row = mysqli_fetch_array($filesql)) {
								?>
									<a href='download.php?filepath=<?=$row['changename']?>"&filename="<?=$row['realname']?>"'><?=$row['realname']?></a>
									<input type='button' onclick='del("<?=$row['seqno']?>","<?=$row['idx']?>")' value='삭제'/>
								<?php
							}?>
					</div>
					<div>
						<table>
							<thead>
								<th><input type="file" name="u_file[]" id="u_file"></th>
							</thead>
							<tbody id="upload"></tbody>
						</table>
						<input type="button" onclick="add_row()" value="추가" />
						<input type="button" onclick="delete_row()" value="삭제" />
					</div>
					<?php 
						$arr = unserialize($board['serialtable']);
					?>
					<div>
						<table border="1px">
							<thead>
								<th>학교명</th>
								<th>졸업년도</th>
								<th>이메일</th>
								<th>선생님이름</th>
							</thead>
							<?php
								for($i=0; $i< count($arr); $i++)
								{ 
									?>
									<tr>
										<td><input type="text" name="school<?=$i+1?>[]" value="<?=$arr[$i][0] ?>"/></td>
										<td><input type="text" name="school<?=$i+1?>[]" value="<?=$arr[$i][1] ?>"/></td>
										<td><input type="text" name="school<?=$i+1?>[]" value="<?=$arr[$i][2] ?>"/></td>
										<td><input type="text" name="school<?=$i+1?>[]" value="<?=$arr[$i][3] ?>"/></td>
									</tr>
									<? 
								} 
								?>

						</table>
					</div>
                    <div>
						<br><br>
						<input type="submit" value=" 글 수정 "/>
						<input type="button" onclick="history.back()" value="취소" />
                    </div>
					<script type="text/javascript">
						function del(sqn,idx)
						{
							$.ajax({
								url : "./filedelete.php",
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
                </form>
			</div>
	</body>
</html>
