<?php
	$url = "https://www.naver.com";
	$result = explode("//",$url);
	if ($result[0] != "http:" && $result[0] != "https:")
	{
		echo "http://".$url;
	}
	else
	{
		echo $url;
	}
?>