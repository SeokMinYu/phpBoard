<?php
	
	include "DBconnect.php";
	include "login_check.php";
?>
	<table align="center" border="1px">
	<thead>
		<th width="80">이름</th>
		<th width="100">가입일</th>
		<th width="100">탈퇴일</th>
		<th width="100">방문횟수</th>
	</thead>
	<tbody>
<?php

	$search = $_POST['kind'];
	$search_r = $_POST['search_r'];
	$area = $_POST['selectBox'];
	$gender = $_POST['gender'];

	$search = mysqli_real_escape_string($connect,$search);
	$search_r = mysqli_real_escape_string($connect,$search_r);
	$area = mysqli_real_escape_string($connect,$area);
	$gender = mysqli_real_escape_string($connect,$gender);

	$sql_1 = "";

	if($search == "" || $search_r == "" || $area == "" || $gender == "")
	{
		$sql = mysqli_query($connect,"select * from member22 order by seqno desc");
	}
	else
	{
		if($search_r != '')
		{ 
			$sql_1 = "and ".$search." like '%".$search_r."%'";
		}
		if($area != '')
		{
			$sql_1 = $sql_1 . " and memberArea like '%".$area."%'";
		}
		
		if($gender != '')
		{
			$sql_1 .= " and memberSex like '%".$gender."%'";
		}
		$sql = mysqli_query($connect,"select * from member22 where 1=1 ".$sql_1." order by seqno desc");
	}

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
			<td><?=$createtime?></td>
			<td><?=$deletetime?></td>
			<td><?=$row['logincnt']?></td>
		</tr>
	</tbody>
<?		} 
	}?>
</table>