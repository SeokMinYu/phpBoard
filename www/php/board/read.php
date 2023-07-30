<?php
include "boardDB.php";
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<script type="text/javascript" src="./smarteditor2-archive/js/HuskyEZCreator.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="./js/jquery-ui.css" />
<script type="text/javascript" src="./js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui.js"></script>
<script type="text/javascript" src="common.js"></script>
</head>
<body>
	<?php
		$bno = $_GET['idx'];
		$hit = mysqli_fetch_array(mq("select * from board where idx ='".$bno."'"));
		$hit = $hit['hit'] + 1;
		$fet = mq("update board set hit = '".$hit."' where idx = '".$bno."'");
		$sql = mq("select * from board where idx='".$bno."'");
		$board = $sql->fetch_array();
		
		$mydate = $board['date'];
		$mydate = substr($mydate, 0, 10);
	
	?>
<!-- 글 불러오기 -->
<div id="board_read">
<h1><a href="list.php">자유게시판</a></h1>
<br><br>
	<h2><?php echo $board['title']; ?></h2>
		<div id="user_info">
			글쓴이: <?php echo $board['name']; ?>  작성날짜: <?php echo $mydate ?> 조회: <?php echo $board['hit']; ?>
				<div id="bo_line"></div>
			</div>
			<div style= "text-align: right">
			<?php
			$filesql = mq("select * from upload where idx='".$bno."'");

			while($row = $filesql->fetch_array()) {
			
				echo "<a href='download.php?filepath=".$row['changename']."&filename=".$row['realname']."'>".$row['realname']."</a>  ";
			
			}?>
			
			</div>
			<div id="bo_content">
				<?php echo nl2br("$board[content]"); ?>
			</div>
	<div id="bo_ser">
		<ul>
			<li><a href="list.php">[목록으로]</a></li>
			<li><a href="modify.php?idx=<?php echo $board['idx']; ?>">[수정]</a></li>
			<li><a href="predel.php?idx=<?php echo $board['idx']; ?>">[삭제]</a></li>
		</ul>
	</div>
</div>
<!--- 댓글 불러오기 -->
<div class="reply_view">
	<h3>댓글목록</h3>
		<?php
			$sql3 = mq("select * from reply where list_num ='".$bno."' order by idx desc");
			while($reply = $sql3->fetch_array()){
				
			$rpdate = $reply['reply_time'];
			$rpdate = substr($rpdate, 0, 19);
		?>
		<div class="dap_lo">
			<div><b><?php echo $reply['name'];?></b></div>
			<div class="dap_to comt_edit"><?php echo nl2br("$reply[content]"); ?></div>
			<div class="rep_me dap_to"><?php echo $reply['date']; ?></div>
			<div class="rep_me rep_menu">
				<div><?php echo $rpdate ?></div>
				<a class="dat_edit_bt" href="#">수정</a> |
				<a class="dat_delete_bt" href="#">삭제</a>
			</div>
			<!-- 댓글 수정 -->
			<div class="dat_edit">
				<form method="post" action="rep_modify_ok.php">
					<input type="hidden" name="rno" value="<?php echo $reply['idx']; ?>" /><input type="hidden" name="b_no" value="<?php echo $bno; ?>">
					<input type="password" name="pw" class="dap_sm" placeholder="비밀번호" />
					<textarea name="content" class="dap_edit_t"><?php echo $reply['content']; ?></textarea>
					<input type="submit" value="수정하기" class="re_mo_bt">
				</form>
			</div>
			<!-- 댓글 삭제 비밀번호 확인 -->
			<div class="dat_delete">
				<form action="reply_delete.php" method="post">
					<input type="hidden" name="rno" value="<?php echo $reply['idx']; ?>" /><input type="hidden" name="bno" value="<?php echo $bno; ?>">
			 		<p>비밀번호<input type="password" name="pw" /> <input type="submit" value="확인"></p>
				 </form>
			</div>
		</div>
	<?php } ?>

	<!--- 댓글 입력 폼 -->
	<div class="dap_ins">
		<form name="rep" action="reply_ok.php?idx=<?php echo $bno; ?>" method="post" onsubmit="repChk();">
			<input type="text" name="dat_user" id="dat_user" class="dat_user" size="15" placeholder="아이디">
			<input type="password" name="dat_pw" id="dat_pw" class="dat_pw" size="15" placeholder="비밀번호">
			<input type="submit" id="rep_bt" class="re_bt" value="추가" />
			<div style="margin-top:10px;">
				<textarea name="content" class="reply_content" id="reply_content" ></textarea>
			</div>
			<script type="text/javascript">
					var oEditors = [];
					nhn.husky.EZCreator.createInIFrame({
					oAppRef: oEditors, elPlaceHolder: "reply_content", sSkinURI: "./smarteditor2-archive/SmartEditor2Skin.html",
					fCreator: "createSEditor2"
					});
					
					function repChk() {
						var f = document.rep;

						oEditors.getById["reply_content"].exec("UPDATE_CONTENTS_FIELD", []); 
						try {
							elClickedObj.form.submit();
						} catch(e) {}

						if ( f.dat_user.value == "" ) {
							alert("아이디을 입력해주세요");
							f.utitle.focus();
							return false;
						}
						if( f.dat_pw.value == "" ) {
							alert("비밀번호를 입력해주세요");
							f.uname.focus();
							return false;
						}
					}
			</script>
		</form>
	</div>
</div><!--- 댓글 불러오기 끝 -->
<div id="foot_box"></div>
</div>
</body>
</html>