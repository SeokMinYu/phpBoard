<?php
	
	include "DBconnect.php";
	include "login_check.php";
	header( "Content-type: application/vnd.ms-excel; charset=utf-8");
	header( "Content-Disposition: attachment; filename = memberList.xls" ); //filename = 저장되는 파일명을 설정합니다.
	header( "Content-Description: PHP4 Generated Data" );

?>
<!doctype html>
<html lang="kr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
	<body>
	<table align="center" border="1px">
	<thead>
		<th width="80">이름</th>
		<th width="80">레벨</th>
		<th width="100">가입일</th>
		<th width="100">탈퇴일</th>
		<th width="100">방문횟수</th>
	</thead>
	<tbody>
<?php

	$search = $_POST['kind'];
	$search_r = $_POST['search_r'];
	$area = $_POST['area'];
	$gender = $_POST['gender'];

	if($search_r != "") 
	{
		$sql_1 = "and ".$search." like '%".$search_r."%'";
	}
	if($area != '')
	{
		$sql_2 = "and memberArea='".$area."'";
	}
	
	if($gender != '')
	{
		$sql_3 = "and memberSex='".$gender."'";
	}

	$sql = mysqli_query($connect,"select * from member30 where 1=1 ".$sql_1." ".$sql_2." ".$sql_3." order by seqno desc");
	while($row = mysqli_fetch_array($sql))
	{

		$createtime = $row['createtime'];

		$createtime = substr($createtime, 0, 10); //날짜 형식 yyyy-mm-dd -포함 10글자

		$deletetime = $row['deletetime'];

		$deletetime = substr($deletetime, 0, 10);

		if($row['memberId'] != "admin") {
?>
		<tr align="center">						
			<td><?=$row['memberName']?></td>
			<td><?=$row['level']?></td>
			<td><?=$createtime?></td>
			<td><?=$deletetime?></td>
			<td><?=$row['logincnt']?></td>
		</tr>
	</tbody>
<?		} 
	}?>
</table>
</body>
</html>