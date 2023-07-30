<?php
	header('Content-Type : text/html; charset=utf-8');
	include "boardDB.php";

	$bno = $_GET['idx'];
	$username = $_POST['uname'];
	$userpw = $_POST['upw'];
	$title = $_POST['utitle'];
	$content = $_POST['ucontent'];
	$sql = mq("select * from board where idx ='".$bno."'");
	$board = $sql->fetch_array();

		if($userpw == $board['pw']){

			$sql = mq("update board set name='".$username."',pw='".$userpw."',title='".$title."',content='".$content."' where idx='".$bno."'");

				for($i = 0; $i < count($_FILES['u_file']['name']); $i++){

					$uploadfile = iconv("UTF-8", "EUC-KR",$_FILES['u_file']['name'][$i]);
					$folder = $_SERVER['DOCUMENT_ROOT']."/ysm/Board/upload/".$uploadfile;

					if(move_uploaded_file($_FILES['u_file']['tmp_name'][$i],$folder)){
						$filesql = mq("insert into upload(realname,changename,idx) values('".$uploadfile."','".$folder."','".$bno."')");
					}else{
						echo "<script> alert('파일변경 없음');</script>";
					}
				}

		}
		else{
			echo "<script>
			alert('비밀번호가 틀립니다.');
			history.back();</script>";
		exit;
		}
?>

	<script type="text/javascript">alert("수정되었습니다."); </script>
	<meta http-equiv="refresh" content="0 url=read.php?idx=<?php echo $bno; ?>">