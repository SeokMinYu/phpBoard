<?php
	include "boardDB.php";
	include "logindex.php";
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>
</head>
	<body align="center">
	<?php
		$bno = $_REQUEST['idx'];
		$sql = mysqli_query($db,"select * from board2 where idx='".$bno."'");
		$board = mysqli_fetch_array($sql);
		$hit = $board['hit'] + 1;
		$fet = mysqli_query($db,"update board2 set hit = '".$hit."' where idx = '".$bno."'");
		
		$mydate = $board['modifytime'];
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
			<a href="list.php"><button>목록으로</button></a>
			<a href="modify.php?idx=<?=$board['idx'] ?>"><button>수정</button></a>
			<a href="predelete.php?idx=<?=$board['idx'] ?>"><button>삭제</button></a>
		</div>
		<h3>댓글</h3>
		<!--댓글 목록-->
		<div align='left'>
			<?php
				$replysql = mysqli_query($db,"select * from reply2 where list_num ='".$bno."' order by idx desc");
				while($reply = mysqli_fetch_array($replysql))
				{
					$reply_time = $reply['starttime'];
					$reply_time = substr($reply_time, 0, 19);
			?>
			<div style="margin-left:400px">
				<div><b><?=$reply['username']?></b></div>
				<div><?=nl2br($reply[content]);?></div>
				<div><?=$reply_time?>
				
				<?php if($_SESSION['user_id'] == $reply['userId'] || $_SESSION['user_id'] == "admin"){?>
				<a href="reply_modify.php?idx=<?=$reply['idx'] ?>">수정</a>  |  <a href=# onclick="replydel(<?=$reply['idx']?>);" >삭제</a><? } ?></div><br>
			</div>
		<?php } ?>
		</div>
		<!-- 댓글입력 폼 -->
			<div>
				<form name="replyWr" method="POST" onsubmit="return repWr();" action="reply_ok.php">
					<input type="hidden" name="idx" value="<?=$bno?>"/>
					<div>
						<textarea name="rpcontent" id="rpcontent" style="margin-top:10px; width: 400px; height: 100px"></textarea>
						<input type="submit" value="등록"/>
					</div>
				</form>
			</div>
		<script>
			function replydel(del)
			{
				
				var con = confirm("댓글을 삭제합니다.");
				if(con == true)
				{
					location.href='reply_delete.php?idx='+del;
				}
		}
		</script>
</body>
</html>