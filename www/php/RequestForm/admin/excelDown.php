<?php
	include "../userDB.php";
	include "logincheck.php";
	header( "Content-type: application/vnd.ms-excel; charset=utf-8");
	header( "Content-Disposition: attachment; filename = userRequest.xls" ); //filename = 저장되는 파일명을 설정합니다.
	header( "Content-Description: PHP4 Generated Data" );

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>다운로드</title>
</head>
	<body>
	<?php

		$chk = $_POST['ck'];
		//$ck = implode(",",$chk);
		//$sql = mysqli_query($db,"select * from questionForm where idx='{$ck}'");
	?>
	
			<table border='1'>
				<tr>
					<td>제목</td>
					<td>성명</td>
					<td>이메일</td>
					<td>연락처</td>
					<td>주소</td>
					<td>내용</td>
					<td>등록일</td>
				</tr>
	<?php
		 for($i=0 ; $i < count($chk); $i++) 
		 { 
			 $sql = mysqli_query($db,"select * from questionForm where idx='{$chk[$i]}'");
			 $qsDB = mysqli_fetch_array($sql);
			 ?>
			
				<tr>
					<td><?=$qsDB['userTitle']?></td>
					<td><?=$qsDB['userName']?></td>
					<td><?=$qsDB['userEmail']?></td>
					<td><?=$qsDB['userPhone']?></td>
					<td><?=$qsDB['userAdd']?></td>
					<td style=mso-number-format:'\@'><?=nl2br("$qsDB[userContent]")?></td>
					<td><?=$qsDB['createTime']?></td>
				</tr>
		<?	} ?>
			</table>
</body>
</html>