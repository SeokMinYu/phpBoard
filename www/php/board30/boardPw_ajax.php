<?
	include "DBconnect.php";
	include "login_check.php";
	
	$sql = "select boardPw from board30 where idx = '".$_POST['idx']."'";
	$sqlcon = mysqli_query($connect,$sql);
	$row = mysqli_fetch_array($sqlcon);

	if ($_POST['inputPw'] == $row['boardPw']) 
	{
		$_SESSION['boardPw_'.$_POST['idx']] = $_POST['inputPw'];
		echo 1;
	}
	else 
	{
		echo 0;
	}

?>