<?php
	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";
	
	$bno = $_POST['idx'];
	$usname = $_COOKIE['uname'];;
	$title = $_POST['utitle'];
	$content = $_POST['ucontent'];

	$sql = mysqli_query($connect,"update board27 set title='".$title."',content='".$content."' ,modifytime=now()  where idx='".$bno."'");

	for($i = 0; $i < count($_FILES['u_file']['name']); $i++)
	{

		$uploadfile = iconv("UTF-8", "EUC-KR",$_FILES['u_file']['name'][$i]);
		$folder = $_SERVER['DOCUMENT_ROOT']."/php/board2/upload/".$uploadfile;

		if(move_uploaded_file($_FILES['u_file']['tmp_name'][$i],$folder))
		{
			$filesql = mysqli_query($connect,"insert into upload27 (realname,changename,idx) values('".$uploadfile."','".$folder."','".$bno."')");
		}
		else
		{
			echo "<script>alert('파일변경 없음');</script>";
		}
	}

?>
<script type="text/javascript">alert("수정되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=list_view.php?idx=<?=$bno?>">