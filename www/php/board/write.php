<!doctype html>
<html lang="ko">
 <head>
  <meta charset="UTF-8">
  <title>게시판</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
  <script type="text/javascript" src="./smarteditor2-archive/js/HuskyEZCreator.js" charset="utf-8"></script>
 </head>
 <body>

  <div id="board_write">
        <h1><a href="list.php">자유게시판</a></h1>
            <div id="write_area">
                <form name="writeForm" method="post" enctype="multipart/form-data" onsubmit="return writeChk();" action="write_ok.php" >
                    <div id="in_title">
                        <textarea name="utitle" id="utitle" rows="1" cols="55" placeholder="제목" maxlength="100"></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_name">
                        <textarea name="uname" id="uname" rows="1" cols="55" placeholder="글쓴이" maxlength="100" ></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_content">
                        <textarea name="ucontent" id="ucontent" placeholder="내용" ></textarea>
                    </div>
                    <div id="in_pw">
                        <input type="password" name="upw" id="upw"  placeholder="비밀번호"  />  
                    </div>
					<div id="in_file">
						<table align="left">
							<thead>
								<th><input type="file" name="u_file[]" id="u_file"></th>
							</thead>
							<tbody id="upload"></tbody>
						</table>
						<input type="button" onclick="add_row()" value="추가" />
						<input type="button" onclick="delete_row()" value="삭제" />
					</div>
                    <div class="bt_se">
						<input type="submit" value=" 글 작성 "/>
                    </div>
					<script type="text/javascript">
					var oEditors = [];
					nhn.husky.EZCreator.createInIFrame({
					oAppRef: oEditors, elPlaceHolder: "ucontent", sSkinURI: "./smarteditor2-archive/SmartEditor2Skin.html",
					fCreator: "createSEditor2"
					});
					
					function writeChk() {
						var f = document.writeForm;

						oEditors.getById["ucontent"].exec("UPDATE_CONTENTS_FIELD", []); 
						try {
							elClickedObj.form.submit();
						} catch(e) {}

						if ( f.utitle.value == "" ) {
							alert("제목을 입력해주세요");
							f.utitle.focus();
							return false;
						}else if( f.uname.value == "" ) {
							alert("글쓴이을 입력해주세요");
							f.uname.focus();
							return false;
						}else if( f.ucontent.value == "" ) {
							alert("내용을 입력해주세요");
							f.ucontent.focus();
							return false;
						}else if( f.upw.value == "" ) {
							alert("비밀번호를 입력해주세요");
							f.upw.focus();
							return false;
						}
					}
					function add_row() {
						var my_tbody = document.getElementById('upload');
						// var row = my_tbody.insertRow(0); // 상단에 추가
						var row = my_tbody.insertRow( my_tbody.rows.length ); // 하단에 추가
						var cell1 = row.insertCell(0);								
						cell1.innerHTML = '<input type="file" name="u_file[]" id="u_file">';
						}

					function delete_row() {
						var upload = document.getElementById('upload');
						if (upload.rows.length < 1) return;
						// upload.deleteRow(0); // 상단부터 삭제
						upload.deleteRow( upload.rows.length-1 ); // 하단부터 삭제
					}
				</script>
                </form>
            </div>
        </div>
 </body>
</html>
