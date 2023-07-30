<meta charset="UTF-8">
<?php
	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";
	
	$bno = $_POST['idx'];
	$usname = $_COOKIE['uname'];;
	$title = $_POST['utitle'];
	$content = $_POST['ucontent'];
	$notice = $_POST['notice'];
	$boardPw = $_POST['boardPw'];

	$sql = mysqli_query($connect,"update board30 set title='".$title."',content='".$content."',notice='".$notice."', modifytime=now(), boardPw='".$boardPw."' where idx='".$bno."'");

	$file = $_FILES['u_file']['name'];
	$path = $_SERVER['DOCUMENT_ROOT']."/php/board2/upload/";
	$FileCnt = 0;
	
	if($file)
	{
		for($i = 0; $i < count($file); $i++)
		{

			$uploadfile = iconv("UTF-8", "EUC-KR",$file[$i]);

			$FileExt = substr(strrchr($uploadfile, "."), 1);
			$FileName = substr($uploadfile, 0, strlen($uploadfile) - strlen($FileExt) - 1);

			$ret = $FileName.".".$FileExt;
			while(file_exists($path.$ret)) // 화일명이 중복되지 않을때 까지 반복
			{
				$FileCnt++;
				$ret = $FileName."_".$FileCnt.".".$FileExt; // 화일명뒤에 (_1 ~ n)의 값을 붙여서....
			}

			if(move_uploaded_file($_FILES['u_file']['tmp_name'][$i],$path.$ret))
			{
				$filesql = mysqli_query($connect,"insert into upload30 (realname,changename,idx) values('".$ret."','".$path.$ret."','".$bno."')");
			}
		}
	}


?>
<script type="text/javascript">alert("수정되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=list_view.php?idx=<?=$bno?>">