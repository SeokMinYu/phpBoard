<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
	<?
		include "DBconnect.php";

		$sql = mysqli_query($connect,"SELECT * FROM T_CURL WHERE seqno = 1");
		$row = mysqli_fetch_array($sql);
	
	?>

  <meta name="Generator" content="EditPlus®">
  <script src="//code.jquery.com/jquery.min.js"></script>
  <title>Document</title>
 </head>
 <body>
	<!-- <div id="cnum"><?=$row['result']?></div> -->
	<!-- <input type="button" onclick="numADD(<?=$row['seqno']?>)" value="클릭"> -->
	<form name="frm" method="POST" action="domainwrite_ok.php" onsubmit="return fnSubmit();" enctype="multipart/form-data">
		도메인 입력 : <input type="text" name="D_Link" size="50" id="D_link" value="<?=$row['domain']?>">
		<input type="submit" value="확인">
	</form>
	<div>정상 도메인 : <?=$row['domainResult']?></div>
 </body>
 <script>
	// http(s)를 제외한 도메인 정규식 // true / false 반환
	function regUrlType(data) {

		var regex = /^(http(s?))\:\/\/([0-9가-힣a-zA-Z\-]+\.)+[가-힣a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?/;
		return regex.test(data);

	}

	function fnSubmit() 
	{
		var tempObject = document.frm;
	
		if (tempObject.D_Link.value != "" && regUrlType(tempObject.D_Link.value) == false)
		{
			alert("올바른 도메인을 입력하시기 바랍니다.\nhttp:// 또는 https://를 입력하시기 바랍니다.");
			tempObject.D_Link.focus();
			return false;
		}
	}
 </script>
</html>
