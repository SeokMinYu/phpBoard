<?
	include "../userDB.php";
	include "logincheck.php";
?>
<!doctype html>
<html lang="ko">
 <head>
  <meta charset="UTF-8">
  <title>관리자페이지</title>
 </head>
 <body>
	<h1>관리자 페이지</h1>
	<article style="width : 760px">
	<form method="post" name="adminform">
		<input type="hidden" value="<?=$qsDB['idx']?>"/>
		<a href ="logout.php">로그아웃<a/> | <a href="adminUpdate.php">정보수정</a>
		<div align="right">
			<input type="button" value="선택다운" onclick="excelDown(adminform);"/>
			<input type="button" value="전체다운" onclick="excelAllDown(excelform);"/>
		</div>
		<table border="1px">
			<thead>
			<th width="70"><input type="checkbox" onclick="allcheck(adminform, this);" name="chk"/></th>
				<th width="70">번호</th>
				<th width="400">제목</th>
				<th width="120">작성자</th>
				<th width="100">작성일</th>
			</thead>
			<tbody>
				<?php

					if(isset($_POST['page']))
					{
						$page = $_POST['page'];
					}
					else{
						$page = 1;
					}

					$search = $_POST['kind'];
					$search_r = $_POST['search_r'];
					$search = mysqli_real_escape_string($db,$search);
					$search_r = mysqli_real_escape_string($db,$search_r);
					
					$searchSql = "";

					if($search_r != '')
					{
						$searchSql = "and ".$search." like '%".$search_r."%'";
					}

					$sql = mysqli_query($db,"select * from questionForm where 1=1 ".$searchSql." order by idx desc");
					$row_num = mysqli_num_rows($sql); //게시판 총 레코드 수
					$list = 10;
					$block_ct = 10; //블록당 보여줄 페이징 개수

					$block_num = ceil($page/$block_ct); //현재 페이지 블록 구하기
					$block_start = (($block_num-1)*$block_ct)+1;//블록의 시작번호
					$block_end = $block_start + $block_ct - 1; //블록 마지막 번호

					$total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
					if($block_end > $total_page)
						{//만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
						$block_end = $total_page;
						}
					$total_block = ceil($total_page/$block_ct); //블럭 총 개수
					$start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

					$sql2 = mysqli_query($db,"select * from questionForm where 1=1 ".$searchSql." order by idx desc limit $start_num, $list");

					$i = $start_num -1 ;

					while($qsDB = mysqli_fetch_array($sql2)){
						$title=$qsDB["userTitle"]; 
						if(strlen($title)>30)
						{ 
							$title=str_replace($qsDB["userTitle"],mb_substr($qsDB["userTitle"],0,30,"utf-8")."...",$qsDB["userTitle"]);
						}

						$i++;

						$listnum = $row_num - $i;

						$mydate = $qsDB['createTime'];

						$mydate = substr($mydate, 0, 10);

				?>
					<tr align="center">
						<td><input type = "checkbox" name = "ck[]" id="ck" value="<?=$qsDB["idx"]?>"/></td>
						<td><?=$listnum?></td>	
						<td style="cursor:pointer;" onclick="location.href='adminview.php?idx=<?=$qsDB["idx"]?>'"><?=$title?></td>
						<td><?=$qsDB['userName']?></td>
						<td><?=$mydate ?></td>

					</tr>
				</tbody>
			<?php } ?>
			</table>
			<input type="button" onclick="requestdel(adminform);" value ="삭제"/>
			<div align="center">
				<?php
					if($page <= 1)
					{ //만약 page가 1보다 작거나 같다면
						echo "<B>처음  <B>"; //처음이라는 글자에 빨간색 표시 
					}else{
						echo "<a href='?kind=$search&search_r=$search_r&page=1'><B>처음<B></a>"; //아니라면 처음글자에 1번페이지로 갈 수있게 링크
					}
					if($page <= 1)
					{ //만약 page가 1보다 크거나 같다면 빈값
					
					}else{
						$pre = $page-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
						echo "<a href='?kind=$search&search_r=$search_r&page=$pre'> ◀ </a>"; //이전글자에 pre변수를 링크한다. 이러면 이전버튼을 누를때마다 현재 페이지에서 -1하게 된다.
					}
					for($i=$block_start; $i<=$block_end; $i++)
						{ //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
						if($page == $i){ //만약 page가 $i와 같다면 
							echo "[$i]"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
						}else{
							echo "<a href='?kind=$search&search_r=$search_r&page=$i'>[$i]</a>"; //아니라면 $i
						}
					}
					if($block_num >= $total_block)
					{ //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
					}else{
						$next = $page + 1; //next변수에 page + 1을 해준다.
						echo "<a href='?kind=$search&search_r=$search_r&page=$next'> ▶ </a>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
					}
					if($page >= $total_page){ //만약 page가 페이지수보다 크거나 같다면
						echo "<B>  마지막<B>"; //마지막 글자에 긁은 빨간색을 적용한다.
					}else{
						echo "<a href='?kind=$search&search_r=$search_r&page=$total_page'><B>  마지막<B></a>"; //아니라면 마지막글자에 total_page를 링크한다.
					}
			?>
			</div>
			</form>
			<div align="center"><br>
			<select name="kind">
				<option value="">선택</option>
				<option value="userTitle" <?php if($search == 'userTitle') echo "selected"; ?> >제목</option>
				<option value="userContent" <?php if($search == 'userContent') echo "selected"; ?> >내용</option>
				<option value="userName" <?php if ($search == 'userName') { ?> selected <?php } ?> >작성자</option>
				<option value="userAdd" <?php if ($search == 'userAdd') { ?> selected <?php } ?> >주소</option>
			</select>
			<input type="search" name="search_r" id="search_r" width="200" placeholder="검색어 입력" value="<?php echo $search_r; ?>"/>
			<input type="submit" value="검색"/><input type="button" onclick="location.href='adminPage.php?kind=&search_r='" value="초기화"/>
			</div>
			<form name="excelform" method="POST">
				<input type="hidden" name="kind" value="<?=$search?>"/>
				<input type="hidden" name="search_r" value="<?=$search_r?>"/>
			</form>
		</article>
		<script>

			function excelDown(form1) //엑셀 선택다운로드
			{
				var chkbox = form1['ck[]'];
				var cnt = 0;
				for(var i=0; i < chkbox.length ; i++)
				{
					if(chkbox[i].checked)
						{
							cnt++;
						}
				}
				if(cnt == 0)
				{
					alert('선택된 것이 없습니다.');
					return ;
				}
				form1.action="excelDown.php";
				form1.submit();
				
			}

			function excelAllDown(form1) //리스트 전체 엑셀 다운로드
			{
				var con = confirm("모든 내역을 다운 받겠습니까?");

				if(con == true)
				{
					form1.action = "allexceldown.php";
					form1.submit();
				}
			}

			function requestdel(form1) //리스트 선택 삭제
			{
				var chkbox = form1['ck[]'];
				var cnt = 0;
				var con = confirm("의뢰서를 삭제하겠습니까?");

				if(con == true)
				{
					for(var i=0; i < chkbox.length ; i++)
					{
						if(chkbox[i].checked)
							{
								cnt++;
							}
					}
					if(cnt == 0)
					{
						alert('선택된 것이 없습니다.');
						return ;
					}
					form1.action="listdelete.php";
					form1.submit();
				}
			}

			function allcheck(form1,chkbox) //리스트 전부 체크(한페이지에 10개 목록이라 10개 지정)
			{ // 전체선택/해제
				
				for (var i=0; i < form1.ck.length; i++)
				 {
					var check = form1.ck[i];

					check.checked = chkbox.checked;
				}
				return;
			}

		</script>
 </body>
</html>
