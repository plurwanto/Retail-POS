<?php
// Main ReciveMail Class File - Version 1.0 (01-03-2006)
/*
 * File: recivemail.class.php
 * Description: Reciving mail With Attechment
 * Version: 1.0
 * Created: 01-03-2006
 * Author: Mitul Koradia
 * Email: mitulkoradia@gmail.com
 * Cell : +91 9879697592
 */
ini_set ('error_reporting', E_ALL);

 
class receivemail
{
	var $server			='';
	var $username		='';
	var $password		='';	
	var $marubox		='';				
	var $email			='';			
	
	function receivemail($config = array()){ //Constructure
		$servertype 	= $config['servertype'];
		$username		= $config['username'];
		$password		= $config['password'];
		$EmailAddress	= $config['EmailAddress'];
		$port			= $config['port'];
		$mailserver		= $config['mailserver'];
//		if($servertype=='imap'){
//			if($port=='') $port='143'; 
//			$strConnect='{'.$mailserver.':'.$port. '}INBOX'; 
//		}else{
//                    '{192.168.0.98:110/pop}INBOX';
                        $strConnect = '{192.168.0.98:110/pop}INBOX';
//			$strConnect='{'.$mailserver.':'.$port. '/pop}INBOX'; 
//		}
		$this->server			=	$strConnect;
		$this->username			=	$username;
		$this->password			=	$password;
		$this->email			=	$EmailAddress;
	}
	
	function connect(){ //Connect To the Mail Box	
//            '{192.168.0.98:110/pop}INBOX';
		$this->marubox=imap_open($this->server,$this->username,$this->password);
		sleep (5);
		ini_set ('error_reporting', E_ALL ^ E_NOTICE);
	}
	
	function getHeaders($mid){ // Get Header info
		$mail_header	= imap_header($this->marubox,$mid);
		$sender		= $mail_header->from[0];
		$sender_replyto	= $mail_header->reply_to[0];
		if(strtolower($sender->mailbox)!='mailer-daemon' && strtolower($sender->mailbox)!='postmaster'){
			$mail_details=array(
					'from'			=> strtolower($sender->mailbox).'@'.$sender->host,
					'fromName'		=> $sender->personal,
					'toOth'			=> strtolower($sender_replyto->mailbox).'@'.$sender_replyto->host,
					'toNameOth'		=> $sender_replyto->personal,
					'subject'		=> $mail_header->subject,
					'to'			=> strtolower($mail_header->toaddress)
				);
		}
		return $mail_details;
	}
	
	function get_mime_type(&$structure){ //Get Mime type Internal Private Use
		$primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER"); 
//		echo $primary_mime_type;die();
		if($structure->subtype) { 
			return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype; 
		} 
		return "TEXT/PLAIN"; 
	} 
	
	function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false){ //Get Part Of Message Internal Private Use 
		$prefix = "";
		if(!$structure) { 
			$structure = imap_fetchstructure($stream, $msg_number); 
		} 
		if($structure) { 
			if($mime_type == $this->get_mime_type($structure))
			{ 
				if(!$part_number) 
				{ 
					$part_number = "1"; 
				} 
				$text = imap_fetchbody($stream, $msg_number, $part_number); 
				if($structure->encoding == 3) 
				{ 
					return imap_base64($text); 
				} 
				else if($structure->encoding == 4) 
				{ 
					return imap_qprint($text); 
				} 
				else
				{ 
					return $text; 
				} 
			} 
			if($structure->type == 1) /* multipart */ 
			{ 
				while(list($index, $sub_structure) = each($structure->parts))
				{ 
					if($part_number)
					{ 
						$prefix = $part_number . '.'; 
					} 
					$data = $this->get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1)); 
					if($data)
					{ 
						return $data; 
					} 
				} 
			} 
		} 
		return false; 
	} 
	
	function getTotalMails(){ //Get Total Number off Unread Email In Mailbox
//		$headers=imap_headers($this->marubox);
		$headers=imap_search($this->marubox,'ALL');
//                imap_search($inbox,'ALL');
		return count($headers);
	}
		
	function GetAttech($mid,$path) {// Get Atteced File from Mail
		$struckture = imap_fetchstructure($this->marubox,$mid);  
		$ar="";
		if(!empty($struckture->parts))
		{
			foreach($struckture->parts as $key => $value)
			{
				$enc=$struckture->parts[$key]->encoding;
				if($struckture->parts[$key]->ifdparameters)
				{
					$name=$struckture->parts[$key]->dparameters[0]->value; 
					$message = imap_fetchbody($this->marubox,$mid,$key+1);
					if ($enc == 0)
						$message = imap_8bit($message);
					if ($enc == 1)
						$message = imap_8bit ($message);
					if ($enc == 2)
						$message = imap_binary ($message);
					if ($enc == 3)
						$message = imap_base64 ($message); 
					if ($enc == 4)
						$message = quoted_printable_decode($message);
					if ($enc == 5)
						$message = $message;
					 	
					$fp=fopen($path.$name,"w");
					
					fwrite($fp,$message);
					fclose($fp);
					$ar=$ar.$name.",";
				}
			}
			$ar=substr($ar,0,(strlen($ar)-1));
		}
		else { $ar = ""; }
//		print $ar;
		return $ar;
	}
	
	function getBody($mid){ // Get Message Body
		$body = $this->get_part($this->marubox, $mid, "TEXT/HTML");
		if ($body == "")
			$body = $this->get_part($this->marubox, $mid, "TEXT/PLAIN");
		if ($body == "") { 
			return "";
		}
		return $body;
	}
	
	function deleteMails($mid){ // Delete That Mail
		imap_delete($this->marubox,$mid);
	}
	
	function close_mailbox(){ //Close Mail Box
		imap_close($this->marubox,CL_EXPUNGE);
	}
}
?>