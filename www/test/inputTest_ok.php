<?
	//$name_1 = $_POST["name_1"];
	//$name_2 = $_POST["name_2"];
	//$val_1 = $_POST["val_1"];
	//$val_2 = $_POST["val_2"];
	//
	//
	//echo "name : ".$name_1." / value : ".$val_1;
	//echo "<br/>";
	//echo "name : ".$name_2." / value : ".$val_2;
	//
	//$isarr = array($arr, $arr2);
	
	$arr = serialize($_POST);
	$data = unserialize($arr);

	foreach($data as $key => $value)
	{
		echo $key." / ".$value;
		echo "<br>";
	}
?>