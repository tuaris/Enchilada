<?php
/* Enchilada 3.0 
 * Core Email
 * 
 * $Id$
 * 
 * Software License Agreement (BSD License)
 * 
 * Copyright (c) 2013-2014, The Daniel Morante Company, Inc.
 * All rights reserved.
 * 
 * Redistribution and use of this software in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 * 
 *   Redistributions of source code must retain the above
 *   copyright notice, this list of conditions and the
 *   following disclaimer.
 * 
 *   Redistributions in binary form must reproduce the above
 *   copyright notice, this list of conditions and the
 *   following disclaimer in the documentation and/or other
 *   materials provided with the distribution.
 * 
 *   Neither the name of The Daniel Morante Company, Inc. nor the names of its
 *   contributors may be used to endorse or promote products
 *   derived from this software without specific prior
 *   written permission of The Daniel Morante Company, Inc.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
 * PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
 * TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

class EnchiladaEmail {

	private $SENDER;
	private $RECIPIENT = array();
	private $CC = array();
	private $BCC = array();
	private $REPLY_TO = array();
	private $ERROR_TO = array();

	private $SUBJECT;
	private $HEADERS = array();
	private $CONTENT  = array();
	private $MIME_BOUNDARY;

	private $MESSAGE_PARTS;

	public function __construct(){
		$this->MIME_BOUNDARY = "----BOUNDRY----" . md5(time());
	}

	public function setSender($email, $name = ''){
		$this->SENDER = $this->validate_name_email($name, $email);
	}

	public function setSubject($subject){
		$this->SUBJECT = $subject;
	}

	public function addRecpt($email, $name = ''){
		$this->RECIPIENT[] = $this->validate_name_email($name, $email);
		return count($this->RECIPIENT) - 1;
	}

	public function addCC($email, $name = ''){
		$this->CC[] = $this->validate_name_email($name, $email);
		return count($this->CC) - 1;
	}
	
	public function addReplyTo($email, $name = ''){
		$this->REPLY_TO[] = $this->validate_name_email($name, $email);
		return count($this->REPLY_TO) - 1;
	}
	
	public function addErrorTo($email, $name = ''){
		$this->ERROR_TO[] = $this->validate_name_email($name, $email);
		return count($this->ERROR_TO) - 1;
	}

	public function addBCC($email, $name = ''){
		$this->BCC[] = $this->validate_name_email($name, $email);
		return count($this->BCC) - 1;
	}

	private function validate_name_email($name, $email){
		if (empty($email)){
			throw new Exception("Invalid Email Address");
		}
		elseif(empty($name)){
			return $email;
		}
		else{
			return "$name <$email>";
		}
	}

	public function addHeader($header){
		$this->HEADERS[] = $header;
		return count($this->HEADERS) - 1;
	}

	public function addContent($content, $type = 'text/plain', $charset = 'UTF-8', $encoding = '8bit', array $extra = array()){
		$this->CONTENT[] =	"--" . $this->MIME_BOUNDARY . PHP_EOL .
									"Content-Type: $type; charset=$charset" . PHP_EOL . 
									(empty($extra) ? '' : (implode(PHP_EOL, array_filter($extra)) . PHP_EOL)) .
									"Content-Transfer-Encoding: $encoding" . PHP_EOL . PHP_EOL . 
									$content;
		return count($this->CONTENT) - 1;
	}

	private function build_email(){
		$this->MESSAGE_PARTS = array('header', 'subject', 'body', 'rcpt');

		// Build Message
		$this->MESSAGE_PARTS['body'] = "This is a multi-part message in MIME format..." . PHP_EOL . PHP_EOL;
		$this->MESSAGE_PARTS['body'] .= implode(PHP_EOL, $this->CONTENT) . PHP_EOL;
		$this->MESSAGE_PARTS['body'] .= '--' . $this->MIME_BOUNDARY . '--' . PHP_EOL . PHP_EOL;

		// Build Header
		$this->MESSAGE_PARTS['header'] = implode(PHP_EOL, array_merge($this->HEADERS, array_filter(array(
			"From: {$this->SENDER}",
			"MIME-Version: 1.0",
			"Content-Type: multipart/alternative; boundary=\"{$this->MIME_BOUNDARY}\"",
			"X-Mailer: Enchilada Framework/3.0",
			(empty($this->CC) ? NULL : ("Cc: " . implode(', ', $this->CC))),
			(empty($this->BCC) ? NULL : ("Bcc: " . implode(', ', $this->BCC))),
			(empty($this->REPLY_TO) ? NULL : ("Reply-to: " . implode(', ', $this->REPLY_TO))),
			(empty($this->ERROR_TO) ? NULL : ("Errors-to: " . implode(', ', $this->ERROR_TO)))
		))));

		$this->MESSAGE_PARTS['subject'] = $this->SUBJECT;
		$this->MESSAGE_PARTS['rcpt'] = implode(', ', $this->RECIPIENT);
	}

	public function send(){
		$this->build_email();
		return mail($this->MESSAGE_PARTS['rcpt'], 
					$this->MESSAGE_PARTS['subject'], 
					$this->MESSAGE_PARTS['body'], 
					$this->MESSAGE_PARTS['header']
				);
	}

	public function raw(){
		$this->build_email();
		$raw = "To: " . $this->MESSAGE_PARTS['rcpt'] . PHP_EOL;
		$raw .= "Subject: " . $this->MESSAGE_PARTS['subject'] . PHP_EOL;
		$raw .= $this->MESSAGE_PARTS['header'] . PHP_EOL . PHP_EOL;
		$raw .= $this->MESSAGE_PARTS['body'];

		return $raw;
	}
}
?>
