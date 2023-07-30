<?
	$fileData = file("./file/num_sum.txt");

	$fileDataExplode = $fileData[0];

	if ( $fileDataExplode >= 0 )
	{
		$fileDataExplode = $fileDataExplode + 1;
	}

	$fileAppendData = fopen("./file/num_sum.txt", "w");
	fwrite($fileAppendData,$fileDataExplode);
?>
<div id="sumtext"><?=$fileDataExplode?></div>