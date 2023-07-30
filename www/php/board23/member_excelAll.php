<?php
	
	include "DBconnect.php";
	include "login_check.php";
?>
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

	$search = mysqli_real_escape_string($connect,$search);
	$search_r = mysqli_real_escape_string($connect,$search_r);

	if($search_r != '')
	{ 
		$search_sql = "and ".$search." like '%".$search_r."%'";
	}
	if($search_r != '')
	{
		$search_sql = $search_sql . " and status like '%".$search_r."%'";
	}

	$sql = mysqli_query($connect,"select * from member23 where 1=1 ".$search_sql." ");
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