<?php
	include "../userDB.php";
	include "logincheck.php";

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>
</head>
	<body align="center">
	<?php
		$bno = $_REQUEST['idx'];
		$sql = mysqli_query($db,"select * from questionForm where idx='".$bno."'");
		$qsDB = mysqli_fetch_array($sql);
	?>
<br>
	<table border="1px" align="center">
			<tr><td>제목</td> <td><?=$qsDB['userTitle']?></td></tr>
			<tr><td>성명</td> <td><?=$qsDB['userName']?></td><tr>
			<tr><td>이메일</td> <td><?=$qsDB['userEmail']?></td></tr>
			<tr><td>연락처</td> <td><?=$qsDB['userPhone']?></td><tr>
			<tr><td>주소</td> <td><?=$qsDB['userAdd']?></td><tr>
			<tr><td>내용</td> <td><?=nl2br("$qsDB[userContent]")?></td></tr>
			<tr><td>등록일</td> <td><?=$qsDB['createTime']?></td></tr>
		</tr>
	</table>
			<br><br>
			<a href="adminPage.php"><button>목록으로</button></a>
			<a href=# onclick="requestdel(<?=$qsDB['idx']?>);"><button>삭제</button></a>
		</div>
		<script>
			function requestdel(del)
			{
				
				var con = confirm("의뢰서를 삭제하겠습니까?");
				if(con == true)
				{
					location.href='requestDelete.php?idx='+del;
				}
		}
		</script>
</body>
</html>