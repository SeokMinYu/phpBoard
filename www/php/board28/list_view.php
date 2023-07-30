<?php
	include "DBconnect.php";
	include "login_check.php";
	
	$bno = $_REQUEST['idx'];

	$sql = mysqli_query($connect,"select * from board28 where idx='".$bno."'");
	$board = mysqli_fetch_array($sql);

	if($board['userId'] != $_SESSION['user_id'])
	{
		$hit = $board['hit'] + 1;
		$hitsql = mysqli_query($connect,"update board28 set hit = '".$hit."' where idx = '".$bno."'");
	}
	
	$mydate = $board['starttime'];
	$mydate = substr($mydate, 0, 10);

	$beforesql = mysqli_query($connect,"select idx from board28 where idx = (select max(idx) from board28 where depth = 0 and idx < '".$bno."')");
	$beforeview = mysqli_fetch_array($beforesql);
	
	$nextsql = mysqli_query($connect,"select idx from board28 where idx = (select min(idx) from board28 where depth = 0 and idx > '".$bno."')");
	$nextview = mysqli_fetch_array($nextsql);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>
<script type="text/javascript" src="../board/smarteditor2-archive/js/HuskyEZCreator.js" charset="utf-8"></script>
<script src="https://www.google.com/recaptcha/api.js?render=sitekey"></script>
<script src="//code.jquery.com/jquery.min.js"></script>
</head>
	<body>
	<div align="center">
	<article style="width:600px;">
	<br>
	<h2>제목 : <?=$board['title']; ?></h2>
	<br><br>
	<div>
		<div style="float: left;">
			<span>글쓴이: <?=$board['name'] ?></span>&nbsp&nbsp<span>조회수: <?=$board['hit']?></span>
		</div>
		<div style="float: right;">
			<span style="margin-left:240px">작성날짜: <?=$mydate ?></span>
		</div>
	</div>
	<br><br>
	<div>
		<div align="left">
			<?=nl2br("$board[content]"); ?>
		</div>
	</div>
	<br>
	<br>
	<div>
		<div align="left"> 
			<?php //업로드한 파일목록 출력

				$filesql = mysqli_query($connect,"select * from upload28 where idx='".$bno."'");

				while($row = mysqli_fetch_array($filesql)) 
				{			
					echo "첨부파일 : <a href='file_download.php?filepath=".$row['changename']."&filename=".$row['realname']."'>".$row['realname']."</a>  ";
				
				}
			?>
			
		</div>
	</div>
		<br><br>
		<div style="float: left;">
		<?if($beforeview['idx']) { ?>
			<a href="list_view.php?idx=<?=$beforeview['idx']?>" style="text-decoration: none; color:black">◀이전</a>&nbsp&nbsp
		<? } ?>
		<?if($nextview['idx']) { ?>
		<a href="list_view.php?idx=<?=$nextview['idx']?>" style="text-decoration: none; color:black">다음▶</a>
		<? } ?>
		</div>
		<div style="float: right;">
			<input type="button" onclick="location.href='list.php'" value="목록으로"> 
			<input type="submit" onclick="location.href='list_write.php?idx=<?=$board['idx'] ?>'" value="답글">
			<?php

				if($userLevel >= 5) 
				{
					if($_SESSION['user_id'] == $board['userId'] || $_SESSION['user_id'] == "admin")
					{
				?>
					<input type="submit" onclick="location.href='list_modify.php?idx=<?=$board['idx'] ?>'" value="수정">
					<input type="submit" onclick="location.href='list_delete.php?idx=<?=$board['idx'] ?>'" value="삭제">
				<? } ?>
			<? } ?>
		</div>
		<br>
		<div align ="left">
		<h3>댓글</h3>
		<!--댓글 목록-->
				<!-- 댓글입력 폼 -->
			<div>
				<form name="replyWr" method="POST" onsubmit="return repWr();" action="view_reply_ok.php">
					<input type="hidden" name="idx" value="<?=$bno?>"/>
					<table>
						<tr>
							<td style="width : 550px; height : 100px;">
								<textarea name="rpcontent" id="rpcontent" style="width : 520px;"></textarea>
							</td>
							<td>
								<input type="submit" value="등록"/>
							</td>
						</tr>
					</table>
				</form>
			</div>
		<div><!--댓글목록-->
			<?php
				$replysql = mysqli_query($connect,"select * from reply28 where list_num ='".$bno."' order by grporder desc, replyDepth asc, parentsidx desc, replyidx desc");
				
				while($reply = mysqli_fetch_array($replysql))
				{
					$reply_time = $reply['starttime'];
					$reply_time = substr($reply_time, 0, 28);

					$delqry = "select * from reply28 where replyidx ='{$reply['replyidx']}'";
					$delsql = mysqli_query($connect,$delqry);
					$replydel = mysqli_fetch_array($delsql);

					if($reply['replyDepth'] == 0)//댓글
					{
			?>
						<table>
						<th align="left"><?=$reply['username']?></th>
						<?php
							if($replydel['replydelete'] == "")
							{	?>
								<tr id="replymodi_<?=$reply['parentsidx']?>">
									<td align="left"><?=nl2br($reply[content]);?></td>
								</tr>
								<tr>
									<td><?=$reply_time?>
								
								<?php if($_SESSION['user_id'] == $reply['userId'] || $_SESSION['user_id'] == "admin"){?>

										<input type="button" onclick ="reply(<?=$reply['parentsidx']?>);" value="수정"/>  
										<input type="button" onclick="replydel(<?=$reply['replyidx']?>);" value="삭제" />  
								<? } ?>
										<input type="button" onclick ="RReply(<?=$reply['replyidx']?>);" value="답글"/>
									</td>
								</tr>
						<?	} 
							else
							{ ?>
								<tr><td>삭제된 댓글입니다.</td></tr>
								
						<?  } ?>
						</table>
						<div id="rereply_<?=$reply['replyidx']?>" align="left"></div>
						

			<?	 }
				else//대댓글
				{ ?>
					<table style="margin-left : 50px;">
					<th align="left">└ <?=$reply['username']?></th>
				<?	if($replydel['replydelete'] == "")
					{	?>
						<tr id="replymodi_<?=$reply['replyidx']?>">
							<td align="left"><?=nl2br($reply[content]);?></td>
						</tr>
						<tr>
							<td><?=$reply_time?>
						
							<?php if($_SESSION['user_id'] == $reply['userId'] || $_SESSION['user_id'] == "admin"){?>
									<input type="button" onclick ="reply(<?=$reply['replyidx']?>);" value="수정"/>  
									<input type="button" onclick="replydel(<?=$reply['replyidx']?>);" value="삭제" />  
							<? } ?>
									<input type="button" onclick ="RReply(<?=$reply['replyidx']?>);" value="답글"/>
							</td>
						</tr>
				<?	}
					else
					{ ?>
						<tr><td>삭제된 댓글입니다.</td></tr>
						
				<?  } ?>
					</table>
					<div id="rereply_<?=$reply['replyidx']?>" align="left"></div>
					
			<?	}
			} ?>
		</div>
		</div>
		</article>
		</div>
	</body>
		<script>
			function replydel(del) //댓글삭제
			{
				
				var con = confirm("댓글을 삭제합니다.");
				if(con == true)
				{
					location.href='view_reply_delete.php?replyidx='+del;
					
				}
			}

			var oEditors = [];
			nhn.husky.EZCreator.createInIFrame({
				oAppRef: oEditors, 
				elPlaceHolder: "rpcontent", 
				sSkinURI: "../board/smarteditor2-archive/SmartEditor2Skin.html", 
				fCreator: "createSEditor2"
			});

			function repWr() //댓글입력체크
			{
				var f = document.replyWr;

				oEditors.getById["rpcontent"].exec("UPDATE_CONTENTS_FIELD", []); 

				if(f.rpcontent.value == "<p>&nbsp;</p>")
				{
					alert("내용을 입력해주세요");
					f.rpcontent.focus();
					return false;
				}
				
			}
		             
			function reply(replyidx)//기본댓글수정
			{
				$.ajax({
					url : "./view_reply_modify.php",
					type : "POST",
					data : {"mode" : "modify","replyidx":replyidx},
					success : function(data){
						
						$('#replymodi_'+replyidx).empty();
						$('#replymodi_'+replyidx).append(data);
						
						nhn.husky.EZCreator.createInIFrame({
							oAppRef: oEditors, 
							elPlaceHolder: "rpmodi_" + replyidx, 
							sSkinURI: "../board/smarteditor2-archive/SmartEditor2Skin.html", 
							fCreator: "createSEditor2"
						});
					}
				
				});
			}

			function rewrtie(bno) //에디터사용함수
			{
				oEditors.getById["rpmodi_" + bno].exec("UPDATE_CONTENTS_FIELD", []); 
			}

			function replych(replyidx)//댓글수정 취소
			{
				$.ajax({
					url : "./view_reply_content.php",
					type : "POST",
					data : {"mode" : "cancel","replyidx":replyidx},
					success : function(data){
						
						$('#replymodi_'+replyidx).empty();
						$('#replymodi_'+replyidx).append(data);
					}
				
				});
			}

			function RReply(replyidx)//대댓글
			{
				$.ajax({
					url : "./view_re_reply.php",
					type : "POST",
					data : {"mode" : "insert","replyidx":replyidx},
					success : function(data){
						
						$('#rereply_'+replyidx).empty();
						$('#rereply_'+replyidx).append(data);

						nhn.husky.EZCreator.createInIFrame({
							oAppRef: oEditors, 
							elPlaceHolder: "rerecontent_"+replyidx, 
							sSkinURI: "../board/smarteditor2-archive/SmartEditor2Skin.html", 
							fCreator: "createSEditor2"
						});
					}
				});
			}

			function rereplyWr(bno) //대댓글에디터
			{
				oEditors.getById["rerecontent_"+bno].exec("UPDATE_CONTENTS_FIELD", []); 
			}

			function RReplyNo(replyidx)//대댓글취소
			{
				$.ajax({
					url : "./view_re_reply.php",
					type : "POST",
					data : {"mode" : "insert","replyidx":replyidx},
					success : function(data){
					
						$('#rereply_'+replyidx).empty();
					}
				
				});
			}
		</script>
</html>