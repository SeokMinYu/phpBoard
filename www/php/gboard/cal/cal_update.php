<?php
	include_once("../common.php");
	include_once("../head.sub.php");
	include_once("../head.php");


?>
<form method="POST" action="cal_update_ok.php">
	<table>
		<tr>
			<td>
				날짜 :
			</td>
			<td>
				<input type="text" name="caldate">
			</td>
		</tr>
		<tr>
			<td>
				제목 :
			</td>
			<td>
				<input type="text" name="content">
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" value="등록하기">
			</td>
		</tr>
	</table>
</form>
<?include "../tail.php";?>
<?include "../tail.sub.php";?>