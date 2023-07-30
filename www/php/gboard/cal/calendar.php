<?php
	include_once("../common.php");
	include_once("../head.sub.php");
	include_once("../head.php");
	include_once("../data/dbconfig.php");
	
	// GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
	$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
	// GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
	$month = isset($_GET['month']) ? $_GET['month'] : date('m');

	$day = isset($_GET['day']) ? $_GET['day'] : date('d');

	$date = "$year-$month-$day"; // 현재 날짜
	$time = strtotime($date); // 현재 날짜의 타임스탬프
	$start_week = date('w', $time); // 1. 시작 요일
	$total_day = date('t', $time); // 2. 현재 달의 총 날짜
	$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차


	$C__I = 0;
	$sql = sql_query("select * from g5_cal_test where 1=1 order by idx desc");
	while ($row = sql_fetch_array($sql)) 
	{
		$calArray[$C__I] = $row;
		$C__I++;
	}
?>
<head>
	<meta charset="UTF-8">
	<title>calendar</title>
	<style type="text/css">
		table 
		{
			border-spacing: 0;
			height: 500px;
		}
		table td 
		{
			text-align: center;
		}
	</style>
</head>
<body>
	<div style="font-size:30px;">
	<?php echo "$year 년 $month 월" ?>
	<!-- 현재가 1월이라 이전 달이 작년 12월인경우 -->
	<?php if ($month == 1){ ?>
			<!-- 작년 12월 -->
			<a href="?year=<?php echo $year-1 ?>&month=12"><B> < </B></a>
	<?php } 
		else
		{ ?>
			<!-- 이번 년 이전 월 -->
			<a href="?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><B> < </B></a>
	<? } ?>

	<!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
	<?php if ($month == 12) 
			{ ?>
			<!-- 내년 1월 -->
			<a href="?year=<?php echo $year+1 ?>&month=1"><B> ></B></a>
	<?php } 
		else 
			{?>
			<!-- 이번 년 다음 월 -->
			<a href="?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><B> ></B></a>
		<?} ?>
		<input type="button" onclick="location.href='cal_update.php'" value="등록">
	</div>
	<table border="1">
		<tr> 
			<th style="color: red;">일</th> 
			<th>월</th> 
			<th>화</th> 
			<th>수</th> 
			<th>목</th> 
			<th>금</th> 
			<th style="color: blue;">토</th> 
		</tr> 

		<!-- 총 주차를 반복합니다. -->
		<?php 
				
			for ($d = 1, $i = 0; $i < $total_week; $i++) { ?> 
			<tr> 
				<!-- 1일부터 7일 (한 주) -->
				<?php for ($k = 0; $k < 7; $k++) { ?>
					<td style="width:150px"> 
						<!-- 시작 요일부터 마지막 날짜까지만 날짜를 보여주도록 -->
						<?php if ( ($d > 1 || $k >= $start_week) && ($total_day >= $d) ) 
							{

								if ($year == date('Y') && $month == date('m') && $d == date("d")) 
								{
									// 13. 날짜 출력
									echo '<B><';
									echo $d;
									echo '></B>';
								} 
								else 
								{
									echo $d;
								}
								
									for ($C__I = 0; $C__I< count($calArray); $C__I++ ) 
									{									

										$calyear = substr($calArray[$C__I]['caldate'], 0, 4);
										$calmonth = substr($calArray[$C__I]['caldate'], 4, 2);
										$calday = substr($calArray[$C__I]['caldate'], 6, 2);


										if ($year == $calyear && $month == $calmonth && $d == $calday) 
										{ ?>								
											<p><?=$calArray[$C__I]['content']?></p>
									<?	} 
									}
								 $d++;
						 }  ?>
					</td> 
				<?php } ?> 
			</tr> 
		<?php } ?> 
	</table>
</body>
<?include "../tail.php";?>
<?include "../tail.sub.php";?>