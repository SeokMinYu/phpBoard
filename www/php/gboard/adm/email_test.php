<?php
	$sub_menu = "400100";
	include_once('./_common.php');
	auth_check_menu($auth, $sub_menu, 'r');
	
	$g5['title'] = '이메일';
	include_once('./admin.head.php');

?>
<head>
<script type="text/javascript" src="../plugin/editor/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script src="../plugin/editor/smarteditor2/config.js"></script>
</head>
<form name="emailtest" method="post" action="email_sending.php" onsubmit="return submitcontent();">
	<input type="submit" value="전송" style="width:100px;">
	<table style="width:774px;">
		<tr>
			<td>받는 사람</td>
			<td><input type="text" name="toEmail" id="toEmail" value=
			"">@<input type="text" name="toEmail2" id="toEmail2">
			<select class="sel_email" onchange="email_sub(this.value);">
				<option value="">직접입력</option>
				<option value="naver.com">naver.com</option>
				<option value="hanmail.net">hanmail.net</option>
				<option value="hotmail.com">hotmail.com</option>
				<option value="nate.com">nate.com</option>
				<option value="yahoo.co.kr">yahoo.co.kr</option>
				<option value="empas.com">empas.com</option>
				<option value="dreamwiz.com">dreamwiz.com</option>
				<option value="freechal.com">freechal.com</option>
				<option value="lycos.co.kr">lycos.co.kr</option>
				<option value="korea.com">korea.com</option>
				<option value="gmail.com">gmail.com</option>
				<option value="hanmir.com">hanmir.com</option>
				<option value="paran.com">paran.com</option> 
			</select></td>			
		</tr>
		<tr>
			<td>보내는 사람</td><td><input type="text" name="fromEmail" id="fromEmail" value="tjrals627@naver.com" readonly></td>
		</tr>
		<tr>
			<td>제목</td><td><input type="text" name="title" id="title" style="width:700px;"></td>
		</tr>
		<tr>
			<td>내용</td><td><textarea name="content" id="content" style="width:700px;"></textarea></td>
		</tr>
	</table> 
</form>
<script type="text/javascript">
	var oEditors = [];
		nhn.husky.EZCreator.createInIFrame({
			oAppRef: oEditors, 
			elPlaceHolder: "content", 
			sSkinURI: "../plugin/editor/smarteditor2/SmartEditor2Skin.html",
			fCreator: "createSEditor2"
		});
	function submitcontent()
	{
		var f = document.emailtest;

		oEditors.getById["content"].exec("UPDATE_CONTENTS_FIELD", []); 
		try {
			elClickedObj.form.submit();
		} catch(e) {}
		
		if (f.toEmail.value == "")
		{
			alert("받는사람의 이메일를 입력하세요.");
			f.toEmail.focus();
			return false;
		}

		if (f.toEmail2.value == "")
		{
			alert("받는사람의 이메일를 입력하세요.");
			f.toEmail2.focus();
			return false;
		}

		if (f.title.value == "")
		{
			alert("제목을 입력하세요.");
			f.title.focus();
			return false;
		}

		if (f.content.value == "")
		{
			alert("내용을 입력하세요.");
			f.content.focus();
			return false;
		}

		var con = confirm("메일을 보내겠습니까?");
		if (con == true)
		{
			return true;
		}
	}

	function email_sub(myval)
	{
		if (myval == ""){
			$("#toEmail2").val("");
			$("#toEmail2").attr("readonly", false); //설정
		}else{
			$("#toEmail2").val(myval);
			$("#toEmail2").attr("readonly", true); //설정
		}
	}
</script>
<?php
include_once('./admin.tail.php');
?>