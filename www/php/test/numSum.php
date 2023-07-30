<?

	$fileData = file("./file/num_sum.txt");

	$fileDataExplode = $fileData[0];

?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <script src="//code.jquery.com/jquery.min.js"></script>
  <title>텍스트더하기</title>
 </head>
 <body>
	<div style="float:left; padding-right:5px;">현재 서버시간 :</div>
	<div style="float:left;" id="server_realtime"><?=date("Y-m-d H:i:s", time())?></div>
	<div style="clear:both;"></div>
	<br>
	<input type="button" id="sumbtn" value="더하기" onclick="txtSum();"/>
	<div id="sumtext"><?=$fileDataExplode?></div>
 </body>
 <script>
	function txtSum()
	{
		$.ajax({
			url : "./proc.php",
			type : "POST",
			data : {"filedata" : "change"},
			success : function(data){
				$("#sumtext").empty();
				$("#sumtext").append(data);
			}
		
		});
	}

	var srv_time = "<?=date('F d, Y H:i:s', time()); ?>";
	var now = new Date(srv_time);
	setInterval("server_realtime()", 1000);
	function server_realtime()
	{
		now.setSeconds(now.getSeconds()+1);
		var year = now.getFullYear();
		var month = now.getMonth() + 1;
		var date = now.getDate();
		var hours = now.getHours();
		var minutes = now.getMinutes();
		var seconds = now.getSeconds();
		if (month < 10){
			month = "0" + month;
		}
		if (date < 10){
			date = "0" + date;
		}
		if (hours < 10){
			hours = "0" + hours;
		}
		if (minutes < 10){
			minutes = "0" + minutes;
		}
		if (seconds < 10){
			seconds = "0" + seconds;
		}
		document.getElementById("server_realtime").innerHTML = year + "-" + month + "-" + date + " " + hours + ":" + minutes + ":" + seconds;
	}
</script>
</html>