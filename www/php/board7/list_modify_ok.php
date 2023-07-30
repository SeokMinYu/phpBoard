<?php
	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";
	
	$bno = $_REQUEST['idx'];
	$usname = $_COOKIE['uname'];;
	$title = $_POST['utitle'];
	$content = $_POST['ucontent'];
	$selectBox = $_POST['selectBox'];
	$color = $_POST['color'];
	$checkBox = implode(",",$color);
	$gender = $_POST['gender'];

	$arr = array(
				  "0" => $_POST['school1'],
				  "1" => $_POST['school2'],
				  "2" => $_POST['school3'],
				  "3" => $_POST['school4']);

	$seri = serialize($arr);

	$sql = mysqli_query($connect,"select * from board2 where idx='".$bno."'");
	$board = mysqli_fetch_array($sql);

	$sql = mysqli_query($connect,"update board2 set title='".$title."',content='".$content."' ,modifytime=now(), selectBox='".$selectBox."',checkBox='".$checkBox."',radioBtn='".$gender."',serialtable='".$seri."' where idx='".$bno."'");

	for($i = 0; $i < count($_FILES['u_file']['name']); $i++)
		{

		$uploadfile = iconv("UTF-8", "EUC-KR",$_FILES['u_file']['name'][$i]);
		$folder = $_SERVER['DOCUMENT_ROOT']."/php/board2/upload/".$uploadfile;

		if(move_uploaded_file($_FILES['u_file']['tmp_name'][$i],$folder))
			{
			$filesql = mysqli_query($connect,"insert into upload2 (realname,changename,idx) values('".$uploadfile."','".$folder."','".$bno."')");
			}
			else
			{
				echo "<script>alert('파일변경 없음');</script>";
			}
		}

?>
<script type="text/javascript">alert("수정되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=modifyread.php?idx=<?=$bno?>">