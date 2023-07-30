<?php
	if(isset($_REQUEST['filepath'])){ //Get 방식으로 얻어온 파일 경로 정보가 있다면
		$file = $_REQUEST['filepath'];  //파일 경로를 저장해준다.
	} else { //Get 방식으로 얻어온 파일 경로 정보가 없다면
		exit(); //php문을 종료한다.
	}
	if(isset($_REQUEST['filename'])){ //Get 방식으로 얻어온 파일 이름이 있다면
		$filename = urlencode($_REQUEST['filename']); //파일 이름을 urlencode 방식으로 저장해준다.
	} else {
		exit();
	}
	$size = filesize($file); //전송할 파일의 크기
	
	/*페이지 캐싱 방지 - 출처 : w3shools.com*/
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	//만료일을 설정하는 것으로 페이지 캐싱 방지를 위해 0을 입력하기 보다는 과거의 고정 날짜로 설정하는 것이 좋다.
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");

	header("Content-Type: application/octet-stream");
	//전송을 받게 될 파일의 종류에 따라 Content-type을 지정해주면 된다.
	header("Content-Transfer-Encoding: binary");
	//전송되는 내용의 인코딩 방식을 적어준다. 생략시 7bit로 설정된다.
	//7bit,8bit,binary,quoted-printable,base64 5가지 중 택1
	header("Content-Length: $size");
	//전송되는 파일의 바이트 값을 적어준다.
	header("Content-Disposition: attachment; filename=".$filename);
	//123.mp4파일을 위에서 설정한 이름 123456.mp4라는 파일명 및 확장자명으로 다운로드를 받게 해준다.

	ob_clean();
	//출력 버퍼를 정리(삭제)한다. -> 결과를 보내주는 기능.
	//https://www.php.net/manual/en/ref.outcontrol.php 참고 
	flush();
	//시스템 출력 버퍼를 플러시(전송)한다. 
	readfile($file);
	//다운로드 받을 파일이 있는 경로로부터 파일을 읽어 정상적으로 출력해준다.
?>