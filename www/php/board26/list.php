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
	<br>
	<article style="width:866px;">
	<div align="right">
	안녕하세요. <B><?=$row['memberName']?></B>님&nbsp&nbsp<input type="button" onclick="location.href='member_update.php'" value="정보수정">
										&nbsp<input type="button" onclick="location.href='logout.php'" value="로그아웃"> 
										<?if($_SESSION['user_id'] != "admin") { ?>&nbsp<input type="button" onclick="delOk();" value="회원탈퇴"><?}?>
										<?if($_SESSION['user_id'] == "admin") { ?>&nbsp<input type="button" onclick="location.href='member_list.php'" value="회원관리"><?}?>
	</div>
	</article></br>
	<form method="POST" name="listform">
	<input type="hidden" name="idx" value="<?=$board['idx']?>">

	<table align="center" border="1px">
		<thead>
		<?if($_SESSION['user_id'] == "admin") { ?>
			<th width="70"><input type="checkBox" name="chk" id="chk" onclick="checkAll();"></th>
		<? } ?>
			<th width="70">번호</th>
			<th width="400">제목</th>
			<th width="126">글쓴이</th>
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

			$today = date('Y-m-d');

			$search = $_GET['kind'];
			$search_r = $_GET['search_r'];
			$area = $_GET['area'];
			$gender = $_GET['gender'];

			$search = mysqli_real_escape_string($connect,$search);
			$search_r = mysqli_real_escape_string($connect,$search_r);
			$area = mysqli_real_escape_string($connect,$area);
			$gender = mysqli_real_escape_string($connect,$gender);

			if($search_r != '')
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

			$sql = mysqli_query($connect,"select * from board26");
			$row_num = mysqli_num_rows($sql); //게시판 총 레코드 수
			$list = 10; //한 화면에 출력할 줄 수
			

            $start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

			$sql2 = mysqli_query($connect,"select * from board26 as b INNER JOIN member26 as m on b.userId=m.memberId where 1=1 ".$sql_1." ".$sql_2." ".$sql_3." order by listorder desc, parentsidx asc, depth asc, idx desc limit $start_num, $list");

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

					$replyNum = mysqli_query($connect,"select * from reply26 where list_num ='".$board['idx']."'");
					$rep_count = mysqli_num_rows($replyNum);

					$uploadNum = mysqli_query($connect,"select * from upload26 where idx ='".$board['idx']."'");
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
							<td onclick="location.href='list_view.php?idx=<?=$board['idx']?>'" align="left">&nbsp&nbsp<?if($mydate == $today){?>[new]<?}?>  <?=$title?>
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
				<option value="title" <? if($kind == 'title') { ?> selected <? } ?>>제목</option>
				<option value="content" <? if($kind == 'content') { ?> selected <? } ?>>내용</option>
				<option value="name" <? if ($kind == 'name') { ?> selected <? } ?>>글쓴이</option>
			</select>
			<input type="search" name="search_r" id="search_r" width="260" placeholder="검색어 입력" value="<?=$search_r ?>"/>
			<input type="submit" value="검색"/>
		</div>
		<?if($_SESSION['user_id'] == "admin") { ?>
			<div>
			지역 : <select name="area">
						<option value="">선택해주세요</option>
						<option value="서울" <? if($area == "서울") { ?>selected<?}?>>서울</option>
						<option value="경기도" <? if($area == "경기도") { ?>selected<?}?>>경기도</option>
						<option value="강원도" <? if($area == "강원도") { ?>selected<?}?>>강원도</option>
						<option value="충청도" <? if($area == "충청도") { ?>selected<?}?>>충청도</option>
						<option value="전라도" <? if($area == "전라도") { ?>selected<?}?>>전라도</option>
						<option value="경상도" <? if($area == "경상도") { ?>selected<?}?>>경상도</option>
						<option value="제주도" <? if($area == "제주도") { ?>selected<?}?>>제주도</option>
					</select>
			| 성별 : <select name="gender">
						<option value="">선택해주세요</option>
						<option value="male" <? if($gender == "male") { ?>selected<?}?>>남자</option>
						<option value="female" <? if($gender == "female") { ?>selected<?}?>>여자</option>
					</select>
				
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
