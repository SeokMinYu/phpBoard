<?
$rannum = sprintf('%06d',rand(000000,999999));

echo $rannum;
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
  <title>Document</title>
 </head>
 <body>
 <form name="reportFrm">
 <table align="center" border="1px">
	<thead>
		<th width="100px">1순위</th>
		<th width="100px">2순위</th>
		<th width="100px">3순위</th>
		<th width="100px">4순위</th>
		<th width="100px">5순위</th>
	</thead>
	<tbody>
		<tr>
			<td>
				<select name="1st" id="1st">
					<option value="">----</option>
					<option value='1'>돈</option>		
					<option value='2'>사랑</option>	
					<option value='3'>우정</option>	
					<option value='4'>가족</option>	
					<option value='5'>자존심</option>
				</select>
			</td>
			<td>
				<select name="2st" id="2st">
					<option value="">----</option>
				</select>
			</td>
			<td>
				<select name="3st" id="3st">
					<option value="">----</option>
				</select>
			</td>
			<td>
				<select name="4st" id="4st">
					<option value="">----</option>
				</select>
			</td>
			<td>
				<select name="5st" id="5st">
					<option value="">----</option>
				</select>
			</td>
		</tr>
		<tr><td colspan="4" align="center">관련 항목</td><td><input type="button" id="btn" value="생성"></td></tr>
		<tr>
			<td>
				① 돈
			</td>
			<td>
				② 사랑
			</td>
			<td>
				③ 우정
			</td>
			<td>
				④ 가족
			</td>
			<td>
				⑤ 자존심
			</td>
		</tr>
	</tbody>
 </table>
 </form>
 </body>
 <script>
	function chageSelect_1()
	{
		var val_1 = document.getElementById("1st").value;

		$('#1st').click(function () {
			$("#2st option[value='"+val_1+"']").remove();
			$("#3st option[value='"+val_1+"']").remove();
			$("#4st option[value='"+val_1+"']").remove();
			$("#5st option[value='"+val_1+"']").remove();
		});
	}

	function chageSelect_2()
	{
		var val_2 = document.getElementById("2st").value;

		$("#1st option[value='"+val_2+"']").remove();
		$("#3st option[value='"+val_2+"']").remove();
		$("#4st option[value='"+val_2+"']").remove();
		$("#5st option[value='"+val_2+"']").remove();
	}

	function chageSelect_3()
	{
		var val_3 = document.getElementById("3st").value;

		$("#1st option[value='"+val_3+"']").remove();
		$("#2st option[value='"+val_3+"']").remove();
		$("#4st option[value='"+val_3+"']").remove();
		$("#5st option[value='"+val_3+"']").remove();
	}

	function chageSelect_4()
	{
		var val_4 = document.getElementById("4st").value;

		$("#1st option[value='"+val_4+"']").remove();
		$("#2st option[value='"+val_4+"']").remove();
		$("#3st option[value='"+val_4+"']").remove();
		$("#5st option[value='"+val_4+"']").remove();
	}

	function chageSelect_5()
	{
		var val_5 = document.getElementById("5st").value;

		$("#1st option[value='"+val_5+"']").remove();
		$("#2st option[value='"+val_5+"']").remove();
		$("#3st option[value='"+val_5+"']").remove();
		$("#4st option[value='"+val_5+"']").remove();
	}

	function createSelect(frm)
	{
		var opts = {"1":"돈","2":"사랑","3":"우정","4":"가족","5":"자존심"};

		var obj = document.createElement("SELECT");

		for(var C__I = 0 ; C__I < opts.size(); C_){
			var opt document.createElement("OPTION");
			opt.value = key;

			opt.text = opts[key];

			obj.options.add(opt);

		}	

		obj.id = 'iSelectBox';

		document.body.appendChild(obj);

}


 </script>
</html>
