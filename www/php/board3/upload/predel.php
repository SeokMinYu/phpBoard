<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>게시판</title>
</head>

<body topmargin="0" leftmargin='0' text=#464646>

<center>
<br>

<!-- 입력된 값을 다음 페이지로 넘기기 위해 FORM을 만든다. -->
<form action="delete.php?idx=<?=$_GET[idx]?>" method="POST">

<table width=300 border=0 cellpadding=2 cellspacing=1 bgcolor=#777777>
<tr>
    <td height=20 align=center>
        <font color=white><B>비 밀 번 호 확 인</B></font>
    </td>
</tr>
<tr>
    <td align="center">
        <font color="white"><B>비밀번호 : </B>
        <INPUT type="password" name=pw size=8 required />
        <INPUT type="submit" value="확 인"/>
        <INPUT type="button" value="취 소" onclick="history.back()"/>
    </td>
</tr>
</table>
</html>
