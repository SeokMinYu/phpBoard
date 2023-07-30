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
  <title>회원관리게시판</title>
</head>
<body>
	<div align="center">
		<h1>회원관리</h1>
	<div>
	안녕하세요. <?=$row['memberName']?>님
	</div></br>
	<form method="POST" name="listform">
	<input type="hidden" name="seqno" value="<?=$board['seqno']?>">

	<table align="center" border="1px">
		<thead>
			<th width="70"><input type="checkBox" name="chk" id="chk" onclick="checkAll();"></th>
			<th width="70">번호</th>
			<th width="80">이름</th>
			<th width="100">가입일</th>
			<th width="100">탈퇴일</th>
			<th width="100">방문횟수</th>
		</thead>
		<tbody>
		<?php

			if(isset($_GET['page'])) //게회원관리물의 첫 위치
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
				$sql_1 = $sql_1 . " and memberArea = '".$area."'";
			}
			
			if($gender != '')
			{
				$sql_1 = $sql_1." and memberSex = '".$gender."'";
			}

			$sql = mysqli_query($connect,"select * from member21");
			$row_num = mysqli_num_rows($sql); //게시판 총 레코드 수
			$list = 10; //한 화면에 출력할 줄 수
			

            $start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

			$sql2 = mysqli_query($connect,"select * from member21 where 1=1 ".$sql_1." order by seqno desc limit $start_num, $list");

			$i = $start_num;

            while($board = mysqli_fetch_array($sql2))
			{

				$i++;

				$listnum = $row_num - $i; //리스트번호 넘버링
				
				$createtime = $board['createtime'];

				$createtime = substr($createtime, 0, 10); //날짜 형식 yyyy-mm-dd -포함 10글자

				$deletetime = $board['deletetime'];

				$deletetime = substr($deletetime, 0, 10);

				if($board['memberId'] != "admin") {
			
		?>
			<tr align="center" style="cursor:pointer;">
				<td><input type="checkbox" name="memberchk[]" class="memberchk" value="<?=$board['seqno']?>"></td>							
				<td onclick="location.href='member_view.php?seqno=<?=$board['seqno']?>'"><?=$listnum?></td>
				<td onclick="location.href='member_view.php?seqno=<?=$board['seqno']?>'"><?=$board['memberName']?></td>
				<td onclick="location.href='member_view.php?seqno=<?=$board['seqno']?>'"><?=$createtime?></td>
				<td onclick="location.href='member_view.php?seqno=<?=$board['seqno']?>'"><?=$deletetime?></td>
				<td onclick="location.href='member_view.php?seqno=<?=$board['seqno']?>'"><?=$board['logincnt']?></td>
			</tr>

		</tbody>
		<? 	}
		}?>
	</table>
	<br>
	<input type="button" onclick="checkDel(listform);" value="삭제"/>
	<input type="button" onclick="" name="excel" value="엑셀다운"/>
	<input type="button" onclick="location.href='list.php'" value="돌아가기"/>
	</form>
	<br>
		<?php include_once 'page.php'; ?> <!--페이징-->

		<div>
		<br>
		<form name="search" method="GET">
			<select name="kind">
				<option value="">선택</option>
				<option value="memberName" <?php if($search == 'memberName') { ?> selected <? } ?>>이름</option>
				<option value="memberId" <?php if($search == 'memberId') { ?> selected <? } ?>>아이디</option>
			</select>
			<input type="search" name="search_r" id="search_r" width="210" placeholder="검색어 입력" value="<?=$search_r ?>"/>
			<input type="submit" value="검색">
		</div>
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
		</form>
		 <script>

			function checkAll() 
			{
				$("input[class=memberchk]").prop("checked",$("#chk").is(':checked'));
			}


			function checkDel(form1) //리스트 선택 삭제
			{
				var chkbox = form1['memberchk[]'];
				var cnt = 0;
				var con = confirm("회원과 관련된 게시물을 삭제합니다.");

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
