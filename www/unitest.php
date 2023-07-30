<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Document</title>
  <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
 </head>
 <body>
	
	<input type="button" value="클릭" onclick="test();" />

	<script>
		function test()
		{
			var datas			= {
				'return_type'	: "goti-inqr-link-pay", 
				'org_tran_id'	: "951012345T20201021152245C00012", 
				'rsp_code'		: "A0000", 
				'rsp_msg'		: "정상 처리",
			};

			var apiURI = "https://unisoft.co.kr/index.php";
			$.ajax({
				url: apiURI,
				type: "POST",
				data: JSON.stringify(datas),
				contentType: "application/json; charset=UTF-8",
				async: "false",
				success : function(response) {
				if( response.rsp_code == 'A0000' )
				{
					console.log(response);
				}
			},
			error : function(xhr, status, error) {
				//console.log(error);
			}
		});
	}
		
	</script>
 </body>
</html>