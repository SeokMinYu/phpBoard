<?php
	
	header('Content-Type : text/html; charset=utf-8');
	
	if($userLevel < 5)
	{
		echo '<script>alert("5레벨 이상만 접근 가능합니다."); history.back(); </script>';
	}
?>