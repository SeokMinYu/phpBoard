<?php

	include "DBconnect.php";

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>
</head>
<body>
	<div align="center">
	<h1 onclick="location.href='list.php'">게시판</h1>
		<form name="writeForm" method="post" enctype="multipart/form-data" onsubmit="return writeChk();" action="write_ok.php">
		<table>
			<tr>
				<th>
					제목
				</th>
				<td>
					 <input type="text" name="title" maxlength="150" value="">
				</td>
			</tr>
			</table>
			<br>
			<div>
				<br><br>
					<input type="submit" value=" 등록 "/>
					<input type="button" onclick="location.href='sortable.php'" value="취소" />
			</div>
		</form>
		<script type="text/javascript">

			function writeChk(bno) 
			{
				var ck = document.writeForm;

				if ( ck.title.value == "" ) 
				{
					alert("제목을 입력해주세요");
					ck.utitle.focus();
					return false;
				}

			}

		</script>
		</div>
</body>
</html>
