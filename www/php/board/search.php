<?php 
   include "boardDB.php";

   	if(isset($_GET['page'])){
		$page = $_GET['page'];
		}else{
			$page = 1;
    }
	/* 검색 변수 */
	$catagory = $_GET['catgo'];
	$search = $_GET['search'];
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<div id="board_area"> 
	<h1>자유게시판</h1>
	  <h3><?php echo $catagory; ?>에서 '<?php echo $search; ?>'검색결과</h3>
	  <h4 style="margin-top:30px;"><a href="list.php">홈으로</a></h4>
		<table class="list-table">
		  <thead>
			  <tr>
				  <th width="70">번호</th>
					<th width="500">제목</th>
					<th width="120">글쓴이</th>
					<th width="100">작성일</th>
					<th width="100">조회수</th>
				</tr>
			</thead>
			<?php

				$sql = mq("select * from board where $catagory like '%{$search}%' order by idx desc");
				$row_num = mysqli_num_rows($sql); //게시판 총 레코드 수

				$list = 5; //한 페이지에 보여줄 개수
				$block_ct = 5; //블록당 보여줄 페이지 개수

				$block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
				$block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
				$block_end = $block_start + $block_ct - 1; //블록 마지막 번호

				$total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
				
				if($block_end > $total_page) {
					$block_end = $total_page;
				}//만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
				$total_block = ceil($total_page/$block_ct); //블럭 총 개수
				$start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.
				
				$sql2 = mq("select * from board where $catagory like '%{$search}%' order by idx desc limit $start_num, $list");  
				while($board = $sql2 -> fetch_array()){
					
				$title=$board["title"]; 
				if(strlen($title)>30)
				{ 
					$title=str_replace($board["title"],mb_substr($board["title"],0,30,"utf-8")."...",$board["title"]);
				}
				$sql3 = mq("SELECT * FROM board");;
				$rep_count = mysqli_num_rows($sql3);

				$mydate = $board['date'];

				$mydate = substr($mydate, 0, 10);
			?>
				<tbody>
					<tr>
						<td width="70" style = "cursor:pointer;" onclick = "location.href='read.php?idx=<?php echo $board["idx"];?>'"><?php echo $board['idx']; ?></td>
						<td width="500" style = "cursor:pointer;" onclick = "location.href'read.php?idx=<?php echo $board["idx"]; ?>'"><span style="background:yellow;"><?php echo $title;?></td>
						<td width="120" style = "cursor:pointer;" onclick = "location.href='read.php?idx=<?php echo $board["idx"];?>'"><?php echo $board['name']?></td>
						<td width="100" style = "cursor:pointer;" onclick = "location.href='read.php?idx=<?php echo $board["idx"];?>'"><?php echo $mydate ?></td>
						<td width="100" style = "cursor:pointer;" onclick = "location.href='read.php?idx=<?php echo $board["idx"];?>'"><?php echo $board['hit']?></td>
					</tr>
				</tbody>
		  <?php } 
?>
		</table>
		<div id="search_box2" align="center">
			<form action="search.php" method="GET">
				<select name="catgo">
					<option value="title">제목</option>
					<option value="name">글쓴이</option>
					<option value="content">내용</option>
			  </select>
			  <input type="text" name="search" size="40" required="required" /> <button>검색</button>
			</form>
		</div>
		<div id="page_num">
			<ul>
			<?php
				if($page <= 1)
					{ //만약 page가 1보다 크거나 같다면
					echo "<li class='fo_re'>처음</li>"; //처음이라는 글자에 빨간색 표시 
				}else{
					echo "<li><a href='search.php?catgo=$catagory&search=$search$page=1'>처음</a></li>"; //아니라면 처음글자에 1번페이지로 갈 수있게 링크
				}
				if($page <= 1)
					{ //만약 page가 1보다 크거나 같다면 빈값
				}else{
				$pre = $page-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함

				

				echo "<li><a href='search.php?catgo=".$catagory."&search=".$search."&page=".$pre.">이전</a></li>"; //이전글자에 pre변수를 링크한다. 이러면 이전버튼을 누를때마다 현재 페이지에서 -1하게 된다.
				}
				for($i=$block_start; $i<=$block_end; $i++){ 
					//for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
					if($page == $i){ //만약 page가 $i와 같다면 
						echo "<li class='fo_re'>[$i]</li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
					}else{
						echo "<li><a href='search.php?catgo=$catagory&search=$search&page=$i'>[$i]</a></li>"; //아니라면 $i
					}
				}
				if($block_num >= $total_block){ //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
					}else{
						$next = $page + 1; //next변수에 page + 1을 해준다.
						echo "<li><a href='search.php?catgo=$catagory&search=$search&page=$next'>다음</a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
				}
				if($page >= $total_page){ //만약 page가 페이지수보다 크거나 같다면
					echo "<li class='fo_re'>마지막</li>"; //마지막 글자에 긁은 빨간색을 적용한다.
					}else{
						echo "<li><a href='search.php?catgo=$catagory&search=$search&page=$total_page'>마지막</a></li>"; //아니라면 마지막글자에 total_page를 링크한다.
				}
			?>
			</ul>
		</div>
	</div>
</body>
</html>