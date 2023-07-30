<?
	class Smtp {

		var $host;
		var $fp;
		var $self;
		var $lastmsg;
		var $parts;
		var $error;
		var $debug;
		var $charset;
		var $ctype;


		function Smtp($host="localhost") {
			if($host == "self") $this->self = true;
			else $this->host = $host;
			$this->parts = array();
			$this->error = array();
			$this->debug = 0;
			$this->charset = "utf-8";
			$this->ctype = "text/html";
		}

		// 디버그 모드 : 1
		function debug($n=1) {
			$this->debug = $n;
		}

		// smtp 통신을 한다.
		function dialogue($code, $cmd) {

			fputs($this->fp, $cmd."\r\n");
			$line = fgets($this->fp, 1024);
			//ereg("^([0-9]+).(.*)$", $line, &$data);
			preg_match("/^([0-9]+).(.*)$/", $line, $data);
			$this->lastmsg = $data[0];

			if($this->debug) {
				echo htmlspecialchars($cmd)."<br>".$this->lastmsg."<br>";
				flush();
			}

			if($data[1] != $code) return false;
			return true;

		}

		//  smptp 서버에 접속을 한다.
		function smtp_connect($host) {

			if($this->debug) {
				echo "SMTP($host) Connecting...<br>";
				flush();
			}

			if(!$host) $host = $this->host;
			if(!$this->fp = fsockopen($host, 25, $errno, $errstr, 10)) {
				$this->lastmsg = "SMTP($host) 서버접속에 실패했습니다.[$errno:$errstr]";
				return false;
			}

			$line = fgets($this->fp, 1024);
			//ereg("^([0-9]+).(.*)$", $line, &$data);
			preg_match("/^([0-9]+).(.*)$/", $line, $data);
			$this->lastmsg = $data[0];
			if($data[1] != "220") return false;

			if($this->debug) {
				echo $this->lastmsg."<br>";
				flush();
			}

			$this->dialogue(334, "AUTH LOGIN");
			$this->dialogue(334, "aG9zcGljZUBzZW91bG55ZGVudGFsLmNvbQ==");
			$this->dialogue(235, "azgzNjgxNDU=");
			$this->dialogue(250, "HELO phpmail");
			return true;

		}

		// stmp 서버와의 접속을 끊는다.
		function smtp_close() {

			$this->dialogue(221, "QUIT");
			fclose($this->fp);
			return true;

		}

		// 메시지를 보낸다.
		function smtp_send($email, $from, $data) {

			if(!$mail_from = $this->get_email($from)) return false;
			if(!$rcpt_to = $this->get_email($email)) return false;

			if(!$this->dialogue(250, "MAIL FROM:$mail_from")) 
				$this->error[] = $email.":MAIL FROM 실패($this->lastmsg)";
			if(!$this->dialogue(250, "RCPT TO:$rcpt_to"))
				$this->error[] = $email.":RCPT TO 실패($this->lastmsg)";
			$this->dialogue(354, "DATA");

			$mime = "Message-ID: <".$this->get_message_id().">\r\n";
			$mime .= "From: $from\r\n";
			$mime .= "To: $email\r\n";

			fputs($this->fp, $mime);
			fputs($this->fp, $data);
			$this->dialogue(250, ".");

		}

		// Message ID 를 얻는다.
	  function get_message_id() {
		$id = date("YmdHis",time());
		mt_srand((float) microtime() * 1000000);
		$randval = mt_rand();
		$id .= $randval."@phpmail";
		return $id;
	  }

		// Boundary 값을 얻는다.
	  function get_boundary() {
		$uniqchr = uniqid(time());
		$one = strtoupper($uniqchr[0]);
		$two = strtoupper(substr($uniqchr,0,8));
		$three = strtoupper(substr(strrev($uniqchr),0,8));
		return "----=_NextPart_000_000${one}_${two}.${three}";
	  }

		// 첨부파일이 있을 경우 이 함수를 이용해 파일을 첨부한다.
		function attach($path, $name="", $ctype="application/octet-stream") {
			if(file_exists($path)) {
				$fp = fopen($path, "r");
				$message = fread($fp, filesize($path));
				fclose($fp);
				$this->parts[] = array ("ctype" => $ctype, "message" => $message, "name" => $name);
			} else return false;
		}

		// Multipart 메시지를 생성시킨다.
		function build_message($part) {

			$msg .= "Content-Type: ".$part['ctype'];
			if($part['name']) $msg .= "; name=\"".$part['name']."\"";
			$msg .= "\r\nContent-Transfer-Encoding: base64\r\n";
			$msg .= "Content-Disposition: attachment; filename=\"".$part['name']."\"\r\n\r\n";
			$msg .= chunk_split(base64_encode($part['message']));
			return $msg;

		}

		// SMTP에 보낼 DATA를 생성시킨다.
		function build_data($subject, $body) {

			$boundary = $this->get_boundary();

			$mime .= "Subject: $subject\r\n";
			$mime .= "Date: ".date ("D, j M Y H:i:s T",time())."\r\n";
			$mime .= "MIME-Version: 1.0\r\n";
			$mime .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n\r\n".
					 "This is a multi-part message in MIME format.\r\n\r\n";
		$mime .= "--".$boundary."\r\n".
				 "Content-Type: ".$this->ctype."; charset=\"".$this->charset."\"\r\n".
				 "Content-Transfer-Encoding: base64\r\n\r\n".
				 chunk_split(base64_encode($body)).
				 "\r\n\r\n--".$boundary;

			$max = count($this->parts);
			for($i=0; $i<$max; $i++) {
				$mime .= "\r\n".$this->build_message($this->parts[$i])."\r\n\r\n--".$boundary;
			}
			$mime .= "--\r\n";

			return $mime;

		}

		// MX 값을 찾는다.
		function get_mx_server($email) {
			
			//if(!ereg("([\._0-9a-zA-Z-]+)@([0-9a-zA-Z-]+\.[a-zA-Z\.]+)", $email, $reg)) return false;
			if(!preg_match("/([\._0-9a-zA-Z-]+)@([0-9a-zA-Z-]+\.[a-zA-Z\.]+)/", $email, $reg)) return false;
			getmxrr($reg[2], $host);
			if(!$host) $host[0] = $reg[2];
			return $host;

		}

		// 이메일의 형식이 맞는지 체크한다.
		function get_email($email) {
			//if(!ereg("([\._0-9a-zA-Z-]+)@([0-9a-zA-Z-]+\.[a-zA-Z\.]+)", $email, $reg)) return false;
			if(!preg_match("/([\._0-9a-zA-Z-]+)@([0-9a-zA-Z-]+\.[a-zA-Z\.]+)/", $email, $reg)) return false;
			return "<".$reg[0].">";
		}


		// 메일을 전송한다.
		function send($to, $from, $subject, $body) {
			
			if(!is_array($to)) $to = split("[,;]",$to);
			if($this->self) {

				$data = $this->build_data($subject, $body);
				foreach($to as $email) {
					if($host = $this->get_mx_server($email)) {
						$flag = false; $i = 0;
						while($flag == false) {
							if($host[$i]) {
								$flag = $this->smtp_connect($host[$i]);
								$i++;
							} else break;
						}
						if($flag) {
							$this->smtp_send($email, $from, $data);
							$this->smtp_close();
						} else {
							$this->error[] = $email.":SMTP 접속실패";
						}
					} else {
						$this->error[] = $email.":형식이 잘못됨";
					}
				}

			} else {

				if(!$this->smtp_connect($this->host)) {
					$this->error[] = "$this->host SMTP 접속실패";
					return false;
				}
				$data = $this->build_data($subject, $body);
				foreach($to as $email) $this->smtp_send($email, $from, $data);
				$this->smtp_close();

			}

		}

	}
?>
