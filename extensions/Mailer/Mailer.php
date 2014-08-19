<?php /**
 * @name Mailer for Kernel Web
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category plugin
 * 
 * Description: This script, via PHP Mailer can send emails.
 * This email is not tested because the current server fails.
 */
class Mailer {
	/**
	 * @name sendMailSMTP This function send mail via SMPT authenticated
	 * @param array $userData Configuration with user data
	 * @param string $subject Subject of message
	 * @param string $body Body
	 * @param array $to Emails who recieve the email
	 * @param array $cc Emails with copy
	 * @param array $bcc Emails with hidden copy
	 * @return boolean
	 */
	static function sendMailSMTP ($userData, $subject, $body, $to = array(), $cc = array(), $bcc = array()) {
		require_once kw::$dir . 'extensions/Mailer/phpmailer/PHPMailerAutoload.php';
	
		$mail = new PHPMailer;
		
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = $userData['host'];  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $userData['username'];                 // SMTP username
		$mail->Password = $userData['password'];                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		
		$mail->From = $userData['from'];
		$mail->FromName = $userData['fromName'];
		
		if (!empty($to)) {
			foreach ($to as $receiver) {
				$mail->addAddress($receiver);     // Add a recipient		
			}
		}
		
		if (!empty($cc)) {
			foreach ($cc as $receiver) {
				$mail->addCC($receiver);     // Add a recipient
			}
		}
		
		if (!empty($bcc)) {
			foreach ($bcc as $receiver) {
				$mail->addBCC($receiver);     // Add a recipient
			}
		}
		
		/*$mail->addAddress('ellen@example.com');               // Name is optional
		$mail->addReplyTo('info@example.com', 'Information');
		$mail->addCC('cc@example.com');
		$mail->addBCC('bcc@example.com');*/
		
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML
		
		$mail->Subject = $subject;
		$mail->Body    = $body;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
		if(!$mail->send()) {
		    //echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		    return false;
		} else {
		    return true;
		}
	}
}