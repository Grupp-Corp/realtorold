<?php
class PermsPub extends CCTemplate
{
	public function __construct() {
		parent::__construct();
	}
	// Get user permissions from User ID or Group ID (GID will override UID)
	public function GetUserPerms($uid = 0, $gid = 0) {
		// Check GID
		if ($gid > 0) {
			// If we have a UID Permission Row, we override all other permissions regardless of group...
			$queryString = "SELECT * FROM " . $this->config['table_prefix'] . "perms WHERE gid = " . $gid . "";
		} else {
			// If we have a UID Permission Row, we override all other permissions regardless of group...
			$queryString = "SELECT * FROM " . $this->config['table_prefix'] . "perms WHERE uid = " . $uid . "";
		}
		$query = $this->db_conn->query($queryString);
		$TotalRows = $this->db_conn->affected_rows;
		// Check for users specific permission rows
		if ($TotalRows > 0) {
			while ($rows = $query->fetch_array(MYSQLI_ASSOC)) {
				$repeat[] = $rows;
			}
			return $repeat;
		} else { // Get user specific permissions (default)
			// Get user's groups
			$queryString = "SELECT * FROM " . $this->config['table_prefix'] . "users_groups WHERE userid = " . $uid . "";
			$query = $this->db_conn->query($queryString);
			$TotalRows = $this->db_conn->affected_rows;
			// Check if user has a group
			if ($TotalRows > 0) {
				while ($rows = $query->fetch_array(MYSQLI_ASSOC)) {
					$repeat[] = $rows;
				}
				// Loop through user's groups
				foreach ($repeat as $groupid) {
					// Get group permissions
					$queryString = "SELECT * FROM " . $this->config['table_prefix'] . "perms WHERE gid = " . $groupid['groupid'] . "";
					$querySecond = $this->db_conn->query($queryString);
					$TotalRows = $this->db_conn->affected_rows;
					// Check if group's permissions exist
					if ($TotalRows > 0) {
						while ($rows = $querySecond->fetch_array(MYSQLI_ASSOC)) {
							$repeat[] = $rows;
						}
					} else {
						return false;
					}
				}
				return $repeat;
			} else {
				return false;
			}
		}
	}
}