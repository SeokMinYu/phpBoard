<?php
	header('Context-Type : text/html; charset=utf-8');
	ini_set("allow_url_fopen", 1);


	include "DBconnect.php";
	include "login_check.php";
	include "levelCheck.php";
	
	$captcha = $_POST['g-recaptcha-response'];
	$secretKey = '6LfAx2IaAAAAAFNI_mC8IUXTvdfeJo-76BSupI-R';
	
	$title = $_POST['utitle'];
	$content = $_POST['ucontent'];
	$date = date('y-m-d H:i:s');
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

	$data = array(
				  'secret' => $secretKey,
				  'response' => $captcha,
				  );

	$parameter = http_build_query($data);
	$url = "https://www.google.com/recaptcha/api/siteverify?".$parameter;
	$response = file_get_contents($url);
	$responseKeys = json_decode($response, true);

	$bno = $_REQUEST['idx'];

	$rpsql = mysqli_query($connect,"select * from board2 where idx='".$bno."'");
	$rep = mysqli_fetch_array($rpsql);

	if ($responseKeys["success"]) 
	{
		$depth = $rep['depth']+1;
		$listorder = $rep['listorder'];

		$sql=mysqli_query($connect,"insert into board2 (parentsidx,name,userId,title,content,starttime,modifytime,selectBox,checkBox,radioBtn,serialtable,depth,listorder) values('".$bno."','".$userName."','".$_SESSION['user_id']."','".$title."','".$content."','".$date."','".$date."','".$selectBox."','".$checkBox."','".$gender."','".$seri."','".$depth."','".$listorder."')");

		for($i = 0; $i < count($_FILES['u_file']['name']); $i++)
			{
				$uploadfile = iconv("UTF-8", "EUC-KR",$_FILES['u_file']['name'][$i]);
				$folder = $_SERVER['DOCUMENT_ROOT']."/php/board2/upload/".$uploadfile;
		
				if(move_uploaded_file($_FILES['u_file']['tmp_name'][$i],$folder))
				{
					$filesql = mysqli_query($connect,"insert into upload2 (realname,changename,idx) values('".$uploadfile."','".$folder."','".$bno."')");
				}		
			}
	}else
		{
		echo '<script>alert("인증에 실패하였습니다.");
		history.back(); </script>';
		exit;
		}
	
?>
<script type="text/javascript">alert("글쓰기 완료되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=list.php"/>