<?php	
	header('Context-Type : text/html; charset=utf-8');
	include "DBconnect.php";
	include "login_check.php";
?>
<!doctype html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <script src="//code.jquery.com/jquery.min.js"></script>
  <title>게시판</title>
</head>
<body>
	<div align="center">
		<h1>게시판</h1>
	<div>
	안녕하세요. <?=$row['memberName']?>님  <input type="button" onclick="location.href='member_update.php'" value="정보수정"> | <input type="button" onclick="location.href='logout.php'" value="로그아웃"> | <input type="button" onclick="delOk();" value="회원탈퇴"><?if($_SESSION['user_id'] == "admin") { ?> | <input type="button" onclick="location.href='member_list.php'" value="회원관리"><?}?>
	</div></br>
	<form method="POST" name="listform">
	<input type="hidden" name="idx" value="<?=$board['idx']?>">

	<table align="center" border="1px">
		<thead>
		<?if($_SESSION['user_id'] == "admin") { ?>
			<th width="70"><input type="checkBox" name="chk" id="chk" onclick="checkAll();"></th>
		<? } ?>
			<th width="70">번호</th>
			<th width="400">제목</th>
			<th width="120">글쓴이</th>
			<th width="100">날짜</th>
			<th width="100">조회수</th>
		</thead>
		<tbody>
		<?php

			if(isset($_GET['page'])) //게시물의 첫 위치
			{
				$page = $_GET['page'];
			}
			else
			{
				$page = 1;
			}

			$search = $_GET['kind'];
			$search_r = $_GET['search_r'];
			$area = $_GET['selectBox'];
			$gender = $_GET['gender'];

			$search = mysqli_real_escape_string($connect,$search);
			$search_r = mysqli_real_escape_string($connect,$search_r);
			$area = mysqli_real_escape_string($connect,$area);
			$gender = mysqli_real_escape_string($connect,$gender);
			
			$sql_1 = "";

			if($search_r != '')
			{ 
				$sql_1 = "and ".$search." like '%".$search_r."%'";
			}
			if($area != '')
			{
				$sql_1 = $sql_1 . " and selectBox = '".$area."'";
			}
			
			if($gender != '')
			{
				$sql_1 = $sql_1." and radioBtn = '".$gender."'";
			}

			$sql = mysqli_query($connect,"select * from board19");
			$row_num = mysqli_num_rows($sql); //게시판 총 레코드 수
			$list = 10; //한 화면에 출력할 줄 수
			

            $start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

			$sql2 = mysqli_query($connect,"select * from board19 where 1=1 ".$sql_1." order by listorder desc, parentsidx asc, depth asc, idx desc limit $start_num, $list");

			$i = $start_num - 1 ;

            while($board = mysqli_fetch_array($sql2))
				{
					$title = $board["title"]; 

					if(strlen($title)>26) //글자수 26자 이상이면
					{ 
						$title=str_replace($board["title"],mb_substr($board["title"],0,26,"utf-8")."...",$board["title"]);
					}

					$i++;

					$listnum = $row_num - $i; //리스트번호 넘버링

					$mydate = $board['starttime'];

					$mydate = substr($mydate, 0, 10); //날짜 형식 yyyy-mm-dd -포함 10글자

					$replyNum = mysqli_query($connect,"select * from reply19 where list_num ='".$board['idx']."'");
					$rep_count = mysqli_num_rows($replyNum);

					$uploadNum = mysqli_query($connect,"select * from upload19 where idx ='".$board['idx']."'");
					$upload_count = mysqli_num_rows($uploadNum);
				
					if($board['listdelete'] == "")
					{

						if($board['depth'] == 0)
						{
				?>
						<tr align="center" style="cursor:pointer;">
						<?if($_SESSION['user_id'] == "admin") { ?>
							<td><input type="checkbox" name="ck[]" class="chk" value="<?=$board['idx']?>"></td>
						<? } ?>
							
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'"><?=$listnum?></td>	
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'" align="left">&nbsp&nbsp<?=$title?>
							<?php if($rep_count != 0) 
								{ ?> 
									[<?=$rep_count?>]						
							<?php } ?>
							<?php if($upload_count != 0) 
								{?>
									<img src="file.jpg"/>
							<?php } ?>
							</td>
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'"><?=$board['name']?></td>
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'"><?=$mydate ?></td>
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'"><?=$board['hit']?></td>
						</tr>
					</tbody>
					<?php }
						else
						{  ?>
							<tr align="center" style="cursor:pointer;">
							<?if($_SESSION['user_id'] == "admin") { ?>
							<td><input type="checkbox" name="ck[]" class="chk" value="<?=$board['idx']?>"></td>
							<? } ?>

							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'"><?=$listnum?></td>	
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'" align="left">&nbsp&nbsp&nbsp&nbsp<B>┖Re:</B> <?=$title?>
							<?php if($rep_count != 0) { ?> 
									[<?=$rep_count?>]						
							<?php } ?>
							<?php if($upload_count != 0) {?>
									<img src="file.jpg"/>
							<?php } ?>
							</td>
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'"><?=$board['name']?></td>
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'"><?=$mydate ?></td>
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'"><?=$board['hit']?></td>
							</tr>
						</tbody>
			<?php		}
				}
				else
					{ ?>
						<tr align="center">
						<td><?=$listnum?></td>
						<td>삭제된 게시물입니다.</td><td></td><td></td><td></td>
						</tr>
				<?	}
			} ?>
	</table>
	<br>
	<?if($_SESSION['user_id'] == "admin") { ?>
		<input type="button" onclick="checkDel(listform);" value="삭제"/>
	<?}?>
	<input type="button" onclick="lvchk(<?=$userLevel?>);" name="level" value="글쓰기"/>
	</form>
	<br>
		<?php include_once 'page.php'; ?> <!--페이징-->

		<div>
		<br>
		<form name="search" method="GET">
			<select name="kind">
				<option value="">선택</option>
				<option value="title" <?php if($search == 'title') { ?> selected <? } ?>>제목</option>
				<option value="content" <?php if($search == 'content') { ?> selected <? } ?>>내용</option>
				<option value="name" <?php if ($search == 'name') { ?> selected <? } ?>>글쓴이</option>
			</select>
			<input type="search" name="search_r" id="search_r" width="200" placeholder="검색어 입력" value="<?=$search_r ?>"/>
			<input type="submit" value="검색"/><input type="button" onclick="location.href='list.php?kind=&search_r='" value="초기화"/>
		</div>
		<?if($_SESSION['user_id'] == "admin") { ?>
			<div>
			지역 : <select name="selectBox">
						<option value="">선택해주세요</option>
						<option value="seoul" <? if($area == "seoul"){?> selected<?}?> >서울</option>
						<option value="daejeon" <? if($area == "daejeon"){?> selected<?}?>>대전</option>
						<option value="daegu" <? if($area == "daegu"){?> selected<?}?>>대구</option>
						<option value="busan" <? if($area == "busan"){?> selected<?}?>>부산</option>
					</select>
			| 성별 : <label><input type="radio" name ="gender" value="male" <? if($gender == "male"){?> checked <?}?> />남성</label>
						<label><input type="radio" name ="gender" value="female" <? if($gender == "female"){?> checked <?}?> >여성</label>
			</div>
		<?}?>
		</form>
		 <script>
			function lvchk(level)
			{

				if(level >= 5)
				{
					location.href="list_write.php";
				}
				else
				{
					alert("5레벨 이상부터 가능합니다.");
				}

			}
			function delOk()
			{
				var con = confirm("탈퇴하시겠습니까?");

				if(con == true)
				{
					location.href="member_out.php";
				}

			}

			function checkAll() 
			{
				$("input[class=chk]").prop("checked",$("#chk").is(':checked'));
			}


			function checkDel(form1) //리스트 선택 삭제
			{
				var chkbox = form1['ck[]'];
				var cnt = 0;
				var con = confirm("삭제하면 복구 할 수 없습니다. 게시물, 답글이 삭제됩니다.");

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
					form1.action="checkedDelete.php";
					form1.submit();
				}
			}
		</script>
	</div>
</body>
</html>
