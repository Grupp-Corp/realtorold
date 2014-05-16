<?php
class UserChecks extends UserActions
{
	public function __construct() {
		parent::__construct();
	}
	public function CheckIfAnon($username) {
		if (isset($username)) {
			if ($username > '') {
				if ($username != 'Anonymous') {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	public function CheckIfAdmin() {
		if (isset($_SESSION[$this->config['session_prefix'] . 'id'])) {
			if (is_numeric($_SESSION[$this->config['session_prefix'] . 'id'])) {
				$id = $_SESSION[$this->config['session_prefix'] . 'id'];
				$PermCheck = new PermsPub();
				$GetUserPerms = $PermCheck->GetUserPerms($id);
				$found_admin = 0;
				foreach($GetUserPerms as $PermRow) {
					if (isset($PermRow['admin_access']) && $PermRow['admin_access'] == 1) {
						$found_admin = 1;
					}
				}
				if ($found_admin == 1) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// Deprecated
	public function CheckUnverifiedAdmin($id) {
		if (isset($_SESSION[$this->config['session_prefix'] . 'id'])) {
			if (is_numeric($_SESSION[$this->config['session_prefix'] . 'id'])) {
				$id = $_SESSION[$this->config['session_prefix'] . 'id'];
				$PermCheck = new PermsPub();
				$GetUserPerms = $PermCheck->GetUserPerms($id);
				$found_admin = 0;
				foreach($GetUserPerms as $PermRow) {
					if ($PermRow['admin_access'] == 1) {
						$found_admin = 1;
					}
				}
				if ($found_admin == 1) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	public function UserIP() {
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_X_FORWARDED')) {
			$ip = getenv('HTTP_X_FORWARDED');
		} elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
		} elseif (getenv('HTTP_FORWARDED')) {
			$ip = getenv('HTTP_FORWARDED');
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	public function LogOutLink() {
		$html = '';
		if ($this->CheckIfAdmin() === true) {
			if (isset($_SESSION[$this->config['session_prefix'] . 'username']) && $_SESSION[$this->config['session_prefix'] . 'username'] > '') {
				$html .= '[&nbsp;<a href="' . $this->config['site_absolute_url'] . 'profile/" title="User Center">Welcome ' . $_SESSION[$this->config['session_prefix'] . 'username'] . '</a>&nbsp;]&nbsp;&nbsp;';
				$html .= '[&nbsp;<a href="' . $this->config['admin_access_folder'] . '" title="Administration">Administration</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="?logout=1">Logout</a>&nbsp;]';
			}
		} else {
			$html .= '[&nbsp;<a href="' . $this->config['site_absolute_url'] . 'profile/" title="User Center">User Center</a>&nbsp;]&nbsp;&nbsp;|&nbsp;&nbsp;[&nbsp;<a href="?logout=1" title="Logout">Logout</a>&nbsp;]';
		}
		return $html;
	}
	public function ActiveSelf($id, $active) {
		$ExistingUser = $this->db_conn->query('UPDATE ' . $this->config['table_prefix'] . 'users SET active = ' . $active . ' WHERE id = ' . $id . '');
	}
}
?>