<?	include "DBconnect.php"; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Sortable - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
  #sortable li span { position: absolute; margin-left: -1.3em; }
  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
  } );
  </script>
</head>
<body>
<ul id="sortable">
<?	
	$numsql = mysqli_query($connect,"select * from sortable where 1=1");
	$row_num = mysqli_num_rows($numsql);

	$sql = "select * from sortable where 1=1 order by sortorder asc limit 0,30";
	$sqlObj = mysqli_query($connect,$sql);
	
	$C__I = 0;
	while($board = mysqli_fetch_array($sqlObj))
	{
		$C__I = $C__I + 1;
		$title = $board["title"];
		$listnum = $C__I;
?>
  <li class="ui-state-default"><?=$listnum?><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?=$title?></li>
<?	} ?>
</ul>
<input type="button" onclick="location.href='write.php';" value="글쓰기"/>
</body>
</html>