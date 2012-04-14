<?php

class User {
	private $userdb;

	private $dn = "";
	private $uid = "";

	public function __construct($userdb, $uid) {
		$this->userdb = $userdb;
		$this->uid = $uid;
	}

	public function getDn() {
		return $this->dn;
	}

	public function getUid() {
		return $this->uid;
	}

	public function isAdmin() {
		return $this->userdb->isAdmin($this->getUid());	
	}
}

?>
