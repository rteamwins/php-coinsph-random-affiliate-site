<?php
/**
Handles admin interface login sessions.
PHP 5.4+
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
class Admin
{
	private $pdo;
	private $emailhash;
	private $gravatarimagelg;

	public function adminLogin($username,$password) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select * from adminsettings where adminuser=? and adminpass=? limit 1";
		$q = $pdo->prepare($sql);
		$q->execute(array($username,$password));
		$valid = $q->rowCount();
		if ($valid > 0) {
			# successful login.
			return true;
			}
		else {
			# incorrect login.
			return false;
			}
		Database::disconnect();

	}

	public function forgotLogin($sitename,$domain,$adminemail,$adminuser,$adminpass) {

		$subject = "Your " . $sitename . " Admin Details";
		$message = "Admin URL: " . $domain . "/admin\nUsername: " . $adminuser . "\nPassword: " . $adminpass . "\n\n";
		
		$sendsiteemail = new Email();
		$send = $sendsiteemail->sendEmail($adminemail,$adminemail,$subject,$message,$sitename,$domain,$adminemail, '');
		Database::disconnect();
		return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your admin details were sent to your email address.</strong></div>";
	}

	public function getGravatar($adminemail) {

		$emailhash = trim($adminemail);
		$emailhash = md5($emailhash);
		$gravatarimagelg = "<img src=\"http://gravatar.com/avatar/" . $emailhash . "?s=130\" alt=\"admin\" class=\"avatar img-circle img-thumbnail gravatar-lg\">";
		return $gravatarimagelg;
		
	}

	public function adminLogout() {

		session_unset();
		return;

	}

}