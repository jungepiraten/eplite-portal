<?php

require_once(dirname(__FILE__) . "/User.class.php");

class UserDatabase {
	private $admins;

	private $ldapconn;
	private $ldapserver;
	private $ldapbinddn;
	private $ldapbindpw;
	private $ldapbasedn;

	public function __construct($admins, $ldapserver, $ldapbinddn, $ldapbindpw, $ldapbasedn) {
		$this->admins = $admins;
		$this->ldapserver = $ldapserver;
		$this->ldapbinddn = $ldapbinddn;
		$this->ldapbindpw = $ldapbindpw;
		$this->ldapbasedn = $ldapbasedn;
	}

	public function open() {
		$this->ldapconn = ldap_connect($this->ldapserver);
		ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
		ldap_set_option($this->ldapconn, LDAP_OPT_SIZELIMIT, 1000);
		ldap_bind($this->ldapconn, $this->ldapbinddn, $this->ldapbindpw);
	}

	public function close() {
		ldap_close($this->ldapconn);
	}

	public function getUserDN($username) {
		$resource = ldap_search($this->ldapconn, $this->ldapbasedn, "uid=" . ldap_escape($username, true));
		if ($resource) {
			$entry = ldap_first_entry($this->ldapconn, $resource);
			if ($dn = @ldap_get_dn($this->ldapconn, $entry))
				return $dn;
		}
		return false;
	}

	public function userExists($username) {
		return $this->getUserDN($username) !== false;
	}

	public function getUser($username) {
		$resource = ldap_search($this->ldapconn, $this->ldapbasedn, "uid=" . ldap_escape($username, true));
		if ($resource) {
			$entry = ldap_first_entry($this->ldapconn, $resource);
			$dn = @ldap_get_dn($this->ldapconn, $entry);
			if ($dn) {
				$attrs = ldap_get_attributes($this->ldapconn, $entry);
                        	return new User($this, $attrs['uid'][0]);
			}
		}
		return false;
	}

        public function authenticate($username, $password) {
		$resource = ldap_search($this->ldapconn, $this->ldapbasedn, "uid=" . ldap_escape($username, true));
		if ($resource) {
			$entry = ldap_first_entry($this->ldapconn, $resource);
			$dn = @ldap_get_dn($this->ldapconn, $entry);

			if ($dn && @ldap_bind($this->ldapconn, $dn, $password)) {
				ldap_bind($this->ldapconn, $this->ldapbinddn, $this->ldapbindpw);
				$attrs = ldap_get_attributes($this->ldapconn, $entry);
                        	return new User($this, $attrs['uid'][0]);
			}
		}
		return false;
        }

	public function isAdmin($username) {
		return in_array($username, $this->admins);
	}
}

?>
