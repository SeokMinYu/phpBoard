<?php
	header('Context-Type : text/html; charset=utf-8');
	ini_set("allow_url_fopen", 1);

	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";

	$idx = $_POST['idx'];
	
	$captcha = $_POST['g-recaptcha-response'];
	$secretKey = '6LfAx2IaAAAAAFNI_mC8IUXTvdfeJo-76BSupI-R';	

	$title = $_POST['utitle'];
	$content = $_POST['ucontent'];

	$notice=$_POST['notice'];

	$data = array(
				  'secret' => $secretKey,
				  'response' => $captcha,
				  );

	$parameter = http_build_query($data);
	$url = "https://www.google.com/recaptcha/api/siteverify?".$parameter;
	$response = file_get_contents($url);
	$responseKeys = json_decode($response, true);

	if ($responseKeys["success"]) 
	{
		if($idx == "")
		{
			$listsql = mysqli_query($connect,"SELECT (max(listorder)+1) AS listmax FROM board30");
			$listrow = mysqli_fetch_row($listsql);
			$listorder = $listrow[0];
			$depth = 0;

			$sql=mysqli_query($connect,"insert into board30 (name,userId,title,content,starttime,notice,depth) values('".$userName."','".$_SESSION['user_id']."','".$title."','".$content."',now(),'".$notice."','".$depth."')");
			$bno = mysqli_insert_id($connect);
			$repsql = mysqli_query($connect,"update board30 set listorder='".$listorder."', parentsidx='".$bno."' where idx='".$bno."'");

			$file = $_FILES['u_file']['name'];
			$path = $_SERVER['DOCUMENT_ROOT']."/php/board2/upload/";
			
			if($file)
			{
				for($i = 0; $i < count($file); $i++)
				{
					$FileCnt = 0;
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
		}
		else
		{
			$rpsql = mysqli_query($connect,"select * from board30 where idx='".$idx."'");
			$rep = mysqli_fetch_array($rpsql);
			$depth = $rep['depth']+1;
			$listorder = $rep['listorder'];

			$sql=mysqli_query($connect,"insert into board30 (parentsidx,name,userId,title,content,starttime,depth,listorder) values('".$idx."','".$userName."','".$_SESSION['user_id']."','".$title."','".$content."',now(),'".$depth."','".$listorder."')");

			$file = $_FILES['u_file']['name'];
			$path = $_SERVER['DOCUMENT_ROOT']."/php/board2/upload/";
			$FileCnt = 0;

			$bno = mysqli_insert_id($connect);
			
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
		}
	}
	else
	{
		echo '<script>alert("인증에 실패하였습니다.");
		history.back(); </script>';
		exit;
	}
	
?>
<script type="text/javascript">alert("글쓰기 완료되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=list.php"/>