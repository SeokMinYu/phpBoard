<?php	
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
<body align="center">
	<div align="center">
			<h1>게시판</h1>
		<br>
		<article style="width:866px;">
			<div align="right">
			안녕하세요. <? if($row['memberPay'] == 1) { ?>유료회원 : <? } ?>
			<B><?=$row['memberName']?></B>님&nbsp&nbsp<input type="button" onclick="location.href='member_update.php'" value="정보수정">
												&nbsp<input type="button" onclick="location.href='logout.php'" value="로그아웃"> 
												<?if($_SESSION['user_id'] != "admin") { ?>&nbsp<input type="button" onclick="delOk();" value="회원탈퇴"><?}?>
												<?if($_SESSION['user_id'] == "admin") { ?>&nbsp<input type="button" onclick="location.href='member_list.php'" value="회원관리"><?}?>
												<?if($_SESSION['user_id'] != "admin") { ?>&nbsp<input type="button" onclick="location.href='https://tjrals627.cafe24.com/php/board30/memberpay.php'" value="유료회원전환"><?}?>
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
				<th width="130">글쓴이</th>
				<th width="100">날짜</th>
				<th width="70">조회수</th>
				<th width="70">추천수</th>
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

				$sql = mysqli_query($connect,"select * from board30 where 1=1 ".$sql_1." ".$sql_2." ".$sql_3." ");
				$row_num = mysqli_num_rows($sql); //게시판 총 레코드 수
				$list = 10; //한 화면에 출력할 줄 수
				

				$start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

				$sql2 = mysqli_query($connect,"select * from board30 as b INNER JOIN member30 as m on b.userId=m.memberId where 1=1 ".$sql_1." ".$sql_2." ".$sql_3." order by notice desc, listorder desc,  depth asc, idx ASC limit $start_num, $list");

				$i = $start_num - 1 ;

				while($board = mysqli_fetch_array($sql2))
				{
					$title = $board["title"]; 

					if(strlen($title)>30) //글자수 30자 이상이면
					{ 
						$title=str_replace($board["title"],mb_substr($board["title"],0,30,"utf-8")."...",$board["title"]);
					}

					$i++;

					$listnum = $row_num - $i; //리스트번호 넘버링

					$mydate = $board['starttime'];

					$mydate = substr($mydate, 0, 10); //날짜 형식 yyyy-mm-dd -포함 10글자

					$replyNum = mysqli_query($connect,"select * from reply30 where list_num ='".$board['idx']."'");
					$rep_count = mysqli_num_rows($replyNum);

					$uploadNum = mysqli_query($connect,"select * from upload30 where idx ='".$board['idx']."'");
					$upload_count = mysqli_num_rows($uploadNum);


					$likesql = mysqli_query($connect,"select * from likeboard where list_idx = '".$board['idx']."'");	
					$likecnt = mysqli_num_rows($likesql);
						
					$idx = $board['idx'];
					$parentsidx = $board['parentsidx'];


					$boardPwsql = "select boardPw from board30 where idx = '".$parentsidx."'";
					$boardPwcon = mysqli_query($connect,$boardPwsql);
					$boardPwrow = mysqli_fetch_array($boardPwcon);

					$boardPw = $boardPwrow['boardPw'];
					$sessionPw = $_SESSION['boardPw_'.$board['idx']];
					$userId = $board['userId'];
				
					if($board['listdelete'] == "")
					{

						if($board['depth'] == 0)
						{
				?>
						<tr align="center" style="cursor:pointer;">
						<?if($_SESSION['user_id'] == "admin") { ?>
							<td><input type="checkbox" name="ck[]" class="chk" value="<?=$idx?>"></td>
						<? } ?>
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?>
								<?	if($board['notice'] == "1") 
									{ ?>
										<img src="./icon/notice.png" style="width:20px;"/>
								<?  } else { ?>
										<?=$listnum?>
									<? } ?></td>	
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');" align="left">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'" align="left">
							<? } ?>&nbsp&nbsp
				
							<?	if($mydate == $today)
								{ ?>
									<img src="./icon/new.png" style="width:20px;"/> 
							<?  } ?>
							<? if ( $boardPw != "" && $board['userId'] != $user_id && $user_id != "admin") { ?>
									<img src="./icon/pngwing.com.png" style="width:15px;"/> 
									비밀글 입니다.
								<? } else { ?>
									<? if ($boardPw != "") { ?>
										<img src="./icon/pngwing.com.png" style="width:15px;"/>
									<? } ?>
									<?=$title?>
								<? } ?>
							<?  if($rep_count != 0) 
								{ ?> 
									[<?=$rep_count?>]						
							<?  } ?>
							<? if($upload_count != 0) 
								{?>
									<img src="./icon/file.jpg"/>
							<?  } ?>
							</td>
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?><?=$board['name']?></td>
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?><?=$mydate ?></td>
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?><?=$board['hit']?></td>
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?><?if($likecnt == 0) { ?>♡<? } else { ?>♥<? } ?> <?=$likecnt?></td>
						</tr>
						</tbody>
					<?php }
						else
						{  ?>
							<tr align="center" style="cursor:pointer;">
							<?if($_SESSION['user_id'] == "admin") { ?>
							<td><input type="checkbox" name="ck[]" class="chk" value="<?=$idx?>"></td>
							<? } ?>

							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?><?=$listnum?></td>	
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');" align="left">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'" align="left">
							<? } ?>&nbsp&nbsp&nbsp&nbsp<B>┖Re:</B>
							<? if ( $boardPw != "" && $board['userId'] != $user_id && $user_id != "admin" ) { ?>
								<img src="./icon/pngwing.com.png" style="width:15px;"/>
								비밀글의 답글입니다.
							<? } else { ?>
								<? if ($boardPw != "") { ?>
									<img src="./icon/pngwing.com.png" style="width:15px;"/>
								<? } ?>
								<?=$title?>
							<? } ?>
							<?php if($rep_count != 0) { ?> 
									[<?=$rep_count?>]						
							<?php } ?>
							<?php if($upload_count != 0) {?>
									<img src="file.jpg"/>
							<?php } ?>
							</td>
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?><?=$board['name']?></td>
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?><?=$mydate ?></td>
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?><?=$board['hit']?></td>
							<? if ($boardPw != "") { ?>
								<td onclick="goView('<?=$idx?>', '<?=$parentsidx?>', '<?=$sessionPw?>', '<?=$user_id?>','<?=$userId?>');">
							<? } else { ?>
								<td onclick="location.href='list_view.php?idx=<?=$idx?>'">
							<? } ?><?if($likecnt == 0) { ?>♡<? } else { ?>♥<? } ?> <?=$likecnt?></td>
							</tr>
						</tbody>
				<?php	}
					}
					else
					{ ?>
						<tr align="center">
						<td><?=$listnum?></td>
						<td colspan="5">삭제된 게시물입니다.</td>
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
			<?php include_once "page.php"; ?> <!--페이징-->
			<br><br>
			<form name="search" method="GET">
				<div>
					<select name="kind">
						<option value="title" <? if($kind == 'title') { ?> selected <? } ?>>제목</option>
						<option value="content" <? if($kind == 'content') { ?> selected <? } ?>>내용</option>
						<option value="name" <? if ($kind == 'name') { ?> selected <? } ?>>글쓴이</option>
					</select>
					<input type="search" name="search_r" id="search_r" width="300" placeholder="검색어 입력" value="<?=$search_r ?>"/>
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
				<? } ?>
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

					if(chkbox == undefined)
					{
						alert('선택된 것이 없습니다.');
						return false;
					}

					var cnt = 0;

					for(var i=0; i < document.getElementsByName("ck[]").length ; i++)
					{
						if(document.getElementsByName("ck[]")[i].checked == true)
						{
							cnt = cnt + 1;
						}
					}

					if(cnt == 0)
					{
						alert('선택된 것이 없습니다.');
						return false;
					}

					if(confirm("삭제하면 복구 할 수 없습니다. 게시물, 답글이 삭제됩니다."))
					{
						
						form1.action="checkedDelete.php";
						form1.submit();
						return true;
					}
					else
					{
						return false;
					}
				}

				function goView(idx, parentsidx, sessionPw,sessionId,userId)
				{
	
					if ( sessionPw == "" && sessionId != userId && sessionId != "admin" )
					{
						var inputPw;
						inputPw = prompt('비밀번호를 입력하세요');

						$.ajax({
							url : "./boardPw_ajax.php",
							type : "POST",
							data : {"inputPw" : inputPw, 
									"idx" : parentsidx
									},
							success : function(data){
								if (data == 1)
								{
									location.href="list_view.php?idx="+idx;
								}
								else 
								{
									alert("비밀번호가 다릅니다.");
									location.href="./list.php";
								}
							}	
					
						});

					}
					else
					{
						location.href="list_view.php?idx="+idx;
					}
				}

			</script>
	</div>
</body>
</html>
