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
	<br>
	<article style="width:600px;">
	<div align="right">
	안녕하세요. <B><?=$row['memberName']?></B>님
	</div>
	</article></br>
	<form method="POST" name="listform">
	<input type="hidden" name="seqno" value="<?=$board['seqno']?>">

	<table align="center" border="1px">
		<thead>
			<th width="70"><input type="checkBox" name="chk" id="chk" onclick="checkAll();"></th>
			<th width="70">번호</th>
			<th width="80">이름</th>
			<th width="80">레벨</th>
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
			$area = $_GET['area'];
			$gender = $_GET['gender'];


			$search = mysqli_real_escape_string($connect,$search);
			$search_r = mysqli_real_escape_string($connect,$search_r);
			$search = mysqli_real_escape_string($connect,$search);
			$search_r = mysqli_real_escape_string($connect,$search_r);

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


			$sql = mysqli_query($connect,"select * from member28 where 1=1");
			$row_num = mysqli_num_rows($sql); //게시판 총 레코드 수
			$list = 10; //한 화면에 출력할 줄 수
			

            $start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

			$sql2 = mysqli_query($connect,"select * from member28 where 1=1 ".$sql_1." ".$sql_2." ".$sql_3." order by seqno desc limit $start_num, $list");

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
				<td onclick="location.href='member_view.php?seqno=<?=$board['seqno']?>'"><?=$board['level']?></td>
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
	<input type="button" onclick="excelDown(listform);" name="excel" value="엑셀다운"/>
	<input type="button" onclick="excelAllDown(searchlist);" name="excelall" value="전체엑셀다운"/>
	<input type="button" onclick="location.href='list.php'" value="돌아가기"/>
	</form>
	<br>
		<?php include_once 'page.php'; ?> <!--페이징-->

		<div>
		<br>
		<form name="searchlist" method="GET">
			<select name="kind">
				<option value="">선택</option>
				<option value="memberName" <?php if($kind == 'memberName') { ?> selected <? } ?>>이름</option>
				<option value="memberId" <?php if($kind == 'memberId') { ?> selected <? } ?>>아이디</option>
			</select>
			<input type="search" name="search_r" id="search_r" width="280" placeholder="검색어 입력" value="<?=$search_r ?>"/>
			<input type="submit" value="검색">
		</div>
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

			function excelDown(form1) //엑셀 선택다운로드
			{
				var chkbox = form1['memberchk[]'];
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
				form1.action="member_excel.php";
				form1.submit();
				
			}

			function excelAllDown(form1) //리스트 전체 엑셀 다운로드
			{
				var con = confirm("리스트의 내역을 다운 받겠습니까?");

				if(con == true)
				{
					form1.action = "member_excelAll.php";
					form1.submit();
				}
			}
		</script>
	</div>
</body>
</html>
