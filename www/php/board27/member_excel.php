x<?php
	
	include "DBconnect.php";
	include "login_check.php";
	header( "Content-type: application/vnd.ms-excel; charset=utf-8");
	header( "Content-Disposition: attachment; filename = memberList.xls" ); //filename = 저장되는 파일명을 설정합니다.
	header( "Content-Description: PHP4 Generated Data" );


	$memberchk = $_POST['memberchk'];
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
	for($C__I = 0; $C__I < count($memberchk); $C__I++)
	{
		$sql = mysqli_query($connect,"select * from member27 where seqno='".$memberchk[$C__I]."'");
		$row = mysqli_fetch_array($sql);

		$createtime = $row['createtime'];

		$createtime = substr($createtime, 0, 10); //날짜 형식 yyyy-mm-dd -포함 10글자

		$deletetime = $row['deletetime'];

		$deletetime = substr($deletetime, 0, 10);
?>
		<tr align="center">						
			<td><?=$row['memberName']?></td>
			<td><?=$createtime?></td>
			<td><?=$deletetime?></td>
			<td><?=$row['logincnt']?></td>
		</tr>

	</tbody>
<? }?>
</table>