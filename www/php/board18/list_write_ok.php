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
	$selectBox = $_POST['selectBox'];
	$color = $_POST['color'];
	$checkBox = implode(",",$color);
	$gender = $_POST['gender'];

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
			$listsql = mysqli_query($connect,"SELECT (max(listorder)+1) AS listmax FROM board18");
			$listrow = mysqli_fetch_row($listsql);
			$listorder = $listrow[0];
			$depth = 0;

			$sql=mysqli_query($connect,"insert into board18 (name,userId,title,content,starttime,selectBox,checkBox,radioBtn,depth) values('".$userName."','".$_SESSION['user_id']."','".$title."','".$content."',now(),'".$selectBox."','".$checkBox."','".$gender."','".$depth."')");
			$bno = mysqli_insert_id($connect);
			$repsql = mysqli_query($connect,"update board18 set listorder='".$listorder."', parentsidx='".$bno."' where idx='".$bno."'");

			for($i = 0; $i < count($_FILES['u_file']['name']); $i++)
				{
					$uploadfile = iconv("UTF-8", "EUC-KR",$_FILES['u_file']['name'][$i]);
					$folder = $_SERVER['DOCUMENT_ROOT']."/php/board18/upload/".$uploadfile;
			
					if(move_uploaded_file($_FILES['u_file']['tmp_name'][$i],$folder))
					{
						$filesql = mysqli_query($connect,"insert into upload18 (realname,changename,idx) values('".$uploadfile."','".$folder."','".$bno."')");
					}		
				}
		}
		else
		{
			$rpsql = mysqli_query($connect,"select * from board18 where idx='".$idx."'");
			$rep = mysqli_fetch_array($rpsql);
			$depth = $rep['depth']+1;
			$listorder = $rep['listorder'];

			$sql=mysqli_query($connect,"insert into board18 (parentsidx,name,userId,title,content,starttime,selectBox,checkBox,radioBtn,depth,listorder) values('".$idx."','".$userName."','".$_SESSION['user_id']."','".$title."','".$content."',now(),'".$selectBox."','".$checkBox."','".$gender."',''".$depth."','".$listorder."')");

			for($i = 0; $i < count($_FILES['u_file']['name']); $i++)
				{
					$uploadfile = iconv("UTF-8", "EUC-KR",$_FILES['u_file']['name'][$i]);
					$folder = $_SERVER['DOCUMENT_ROOT']."/php/board18/upload/".$uploadfile;
			
					if(move_uploaded_file($_FILES['u_file']['tmp_name'][$i],$folder))
					{
						$filesql = mysqli_query($connect,"insert into upload18 (realname,changename,idx) values('".$uploadfile."','".$folder."','".$bno."')");
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