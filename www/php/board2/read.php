<?php
	include "boardDB.php";
	include "logindex.php";
	include "levelCheak.php";

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
	<body align="center">
	<?php
		$bno = $_REQUEST['idx'];
		$sql = mysqli_query($db,"select * from board2 where idx='".$bno."'");
		$board = mysqli_fetch_array($sql);
		$hit = $board['hit'] + 1;
		$fet = mysqli_query($db,"update board2 set hit = '".$hit."' where idx = '".$bno."'");
		
		$mydate = $board['starttime'];
		$mydate = substr($mydate, 0, 10);
	?>
<br>
	<h2>제목 : <?=$board['title']; ?></h2>
		<div>
			글쓴이: <?=$board['name'] ?> | 작성날짜: <?=$mydate ?> | 조회수: <?=$board['hit']?>
		</div>
		<div>
		<?php
			if($board['selectBox'] != '')
			{
				echo "지역 : ",$board['selectBox'];
			}
			if($board['radioBtn'] != '')
			{
				echo " 성별 : ",$board['radioBtn'];
			}
			if($board['checkBox'] != '')
			{
				echo " 좋아하는 색 : ",$board['checkBox'];
			}
		?>
		</div>
		<div>
			<?php
			$filesql = mysqli_query($db,"select * from upload2 where idx='".$bno."'");

			while($row = mysqli_fetch_array($filesql)) {
			
				echo "<a href='download.php?filepath=".$row['changename']."&filename=".$row['realname']."'>".$row['realname']."</a>  ";
			
			}?>
			
		</div>
		<div>
			<br>
				<?=nl2br("$board[content]"); ?>
		</div>
		<div>
			<br><br>
			<a href="list.php"><button>목록으로</button></a><a href="write.php?idx=<?=$board['idx'] ?>"><button>답글</button></a>
			<?php

				if($userLevel >= 5) 
				{
					if($_SESSION['user_id'] == $board['userId'] || $_SESSION['user_id'] == "admin"){
				?>

					<a href="modify.php?idx=<?=$board['idx'] ?>"><button>수정</button></a>
					<a href="predelete.php?idx=<?=$board['idx'] ?>"><button>삭제</button></a>
					<? } ?>
			<? } ?>
		</div>
		
		<h3>댓글</h3>
		<!--댓글 목록-->
				<!-- 댓글입력 폼 -->
			<div>
				<form name="replyWr" method="POST" onsubmit="return repWr();" action="reply_ok.php">
					<input type="hidden" name="idx" value="<?=$bno?>"/>
					<div align="center">
						<textarea name="rpcontent" id="rpcontent" style=
						"width : 700px; height : 100px;"></textarea>
						<input type="submit" value="등록"/>
					</div>
				</form>
			</div>
		<div align='left'><!--댓글목록-->
			<?php
				$replysql = mysqli_query($db,"select * from reply2 where list_num ='".$bno."' order by grporder desc, replyDepth ASC, parentsidx desc, replyidx desc");
				
				while($reply = mysqli_fetch_array($replysql))
				{
					if($reply['replyDepth'] == 0)//댓글
					{
						$reply_time = $reply['starttime'];
						$reply_time = substr($reply_time, 0, 19);
						?>
						<div id="thisreply" style="margin-left:300px">
							<div><b><?=$reply['username']?></b></div>
							<?php

								$delqry = "select * from reply2 where replyidx ='{$reply['replyidx']}'";
								$delsql = mysqli_query($db,$delqry);
								$replydel = mysqli_fetch_array($delsql);
								if($replydel['replydelete'] == "")
								{	?>
									<div id="replymodi_<?=$reply['parentsidx']?>"><?=nl2br($reply[content]);?></div>
									<div><?=$reply_time?>
									
									<?php if($_SESSION['user_id'] == $reply['userId'] || $_SESSION['user_id'] == "admin"){?>
									<input type="button" onclick ="reply(<?=$reply['parentsidx']?>);" value="수정"/>  |  <input type="button" onclick="replydel(<?=$reply['replyidx']?>);" value="삭제" /><? } ?>  |  <input type="button" onclick ="RReply(<?=$reply['replyidx']?>);" value="답글"/></div><br>
							<?	} 
								else
								{ ?>
									<div>삭제된 댓글입니다.</div><br>
								
							<?	}?>
							</div>
						<div id="rereply_<?=$reply['replyidx']?>" style="margin-left:300px"></div>

		<?php	 }
				else//대댓글
					{ ?>
					<div style="margin-left:350px">
					<div>└<?=$reply['username']?></div>
					<div id="replymodi_<?=$reply['replyidx']?>"><?=nl2br($reply[content]);?></div>
					<div><?=$reply_time?>
					
					<?php if($_SESSION['user_id'] == $reply['userId'] || $_SESSION['user_id'] == "admin"){?>
					<input type="button" onclick ="reply(<?=$reply['replyidx']?>);" value="수정"/>  |  <input type="button" onclick="replydel(<?=$reply['replyidx']?>);" value="삭제" /><? } ?>  |  <input type="button" onclick ="RReply(<?=$reply['replyidx']?>);" value="답글"/></div><br>
					</div>
					<div id="rereply_<?=$reply['replyidx']?>" style="margin-left:350px"></div>
					<?}
			} ?>
		</div>
		<script>
			function replydel(del)
			{
				
				var con = confirm("댓글을 삭제합니다.");
				if(con == true)
				{
					location.href='reply_delete.php?replyidx='+del;
					
				}
			}

			var oEditors = [];
			nhn.husky.EZCreator.createInIFrame({
				oAppRef: oEditors, 
				elPlaceHolder: "rpcontent", 
				sSkinURI: "../board/smarteditor2-archive/SmartEditor2Skin.html", fCreator: "createSEditor2"
			});

			function repWr()
			{
				var f = document.replyWr;

				oEditors.getById["rpcontent"].exec("UPDATE_CONTENTS_FIELD", []); 

				if(f.rpcontent.value == "<p>&nbsp;</p>")
				{
					alert("내용을 입력해주세요");
					f.rpcontent.focus();
					return false;
				}

				try 
				{
					//elClickedObj.form.submit();
				} catch(e) {}

				
			}

		</script>
		<script>
		             
			function reply(replyidx)//기본댓글수정
			{
				$.ajax({
					url : "./reply_modify.php",
					type : "POST",
					data : {"mode" : "modify","replyidx":replyidx},
					success : function(data){
						
						$('#replymodi_'+replyidx).empty();
						$('#replymodi_'+replyidx).append(data);
						
						nhn.husky.EZCreator.createInIFrame({
							oAppRef: oEditors, 
							elPlaceHolder: "rpmodi_" + replyidx, 
							sSkinURI: "../board/smarteditor2-archive/SmartEditor2Skin.html", fCreator: "createSEditor2"
						});
					}
				
				});
			}

			function rewrtie(bno)
			{
				oEditors.getById["rpmodi_" + bno].exec("UPDATE_CONTENTS_FIELD", []); 
			}

			function replych(replyidx)//댓글수정 취소
			{
				$.ajax({
					url : "./reply_modify2.php",
					type : "POST",
					data : {"mode" : "cancel","replyidx":replyidx},
					success : function(data){
						
						$('#replymodi_'+replyidx).empty();
						$('#replymodi_'+replyidx).append(data);
					}
				
				});
			}

			function RReply(replyidx)//답글
			{
				$.ajax({
					url : "./Rereply.php",
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

			function rereplyWr(bno)
			{
				oEditors.getById["rerecontent_"+bno].exec("UPDATE_CONTENTS_FIELD", []); 
			}

			function RReplyNo(replyidx)//답글취소
			{
				$.ajax({
					url : "./Rereply.php",
					type : "POST",
					data : {"mode" : "insert","replyidx":replyidx},
					success : function(data){
					
						$('#rereply_'+replyidx).empty();
					}
				
				});
			}
		</script>
</body>
</html>