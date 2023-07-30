<?php
include "boardDB.php";
$bno = $_GET['idx'];
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>
</head>

<body>

<center>
<br>

<!-- 입력된 값을 다음 페이지로 넘기기 위해 FORM을 만든다. -->
<form action="delete.php?idx=<?php echo $bno; ?>" method="post">

<table width="300" border="0" cellpadding="2" cellspacing="1">
<tr>
    <td height="20" align="center">
        <B>비 밀 번 호 확 인</B></font>
    </td>
</tr>
<tr>
    <td align="center">
        <B>비밀번호 : </B>
        <INPUT type="password" name="pw" id="upw" size="8" required />
        <INPUT type="submit" value="확 인"/>
        <INPUT type="button" value="취 소" onclick="history.back()"/>
    </td>
</tr>
</table>
</form>
</body>
</html>
