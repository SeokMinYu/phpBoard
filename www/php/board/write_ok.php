<?php

include "boardDB.php";

$username = $_POST['uname'];
$userpw = $_POST['upw'];
$title = $_POST['utitle'];
$content = $_POST['ucontent'];
$date = date('Y-m-d H:i:s');

$sql = mq("insert into board(name,pw,title,content,date) values('".$username."','".$userpw."','".$title."','".$content."','".$date."')"); 

//$db->insert_id;
//$tmpfile =  $_FILES['u_file']['tmp_name'];
//$o_name = $_FILES['u_file']['name'];
//$filename = iconv("UTF-8", "EUC-KR",$_FILES['u_file']['name'][$i]);
//$folder = $_SERVER['DOCUMENT_ROOT']."/ysm/Board/upload/".$filename;
//$_SERVER['DOCUMENT_ROOT']."/ysm/Board/upload/".$_FILES['u_file']['name'][0];
//move_uploaded_file($_FILES['u_file']['tmp_name'][0],$folder);

$db->insert_id;
$bno = $db->insert_id;


for($i = 0; $i < count($_FILES['u_file']['name']); $i++){

	$uploadfile = iconv("UTF-8", "EUC-KR",$_FILES['u_file']['name'][$i]);
	$folder = $_SERVER['DOCUMENT_ROOT']."/ysm/Board/upload/".$uploadfile;

	if(move_uploaded_file($_FILES['u_file']['tmp_name'][$i],$folder)){
		$filesql = mq("insert into upload(realname,changename,idx) values('".$uploadfile."','".$folder."','".$bno."')");
	}
}
?>
<script type="text/javascript">alert("글쓰기 완료되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=list.php" />
