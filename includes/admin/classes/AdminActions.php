<?php
class AdminActions extends UserAdmin
{
	public function __construct() {
		parent::__construct();
	}
	public function AddUserToGroup($uid, $gid) {
		$queryString = "SELECT * FROM " . $this->config['table_prefix'] . "users_groups WHERE userid=" . $uid . " AND groupid=" . $gid . "";
		$query = $this->db_conn->query($queryString);
		$TotalRows = $this->db_conn->affected_rows;
		if ($TotalRows > 0) {
			return false;
		} else {
			$queryString = "INSERT INTO " . $this->config['table_prefix'] . "users_groups (userid, groupid) VALUES (" . $uid . ", " . $gid . ")";
			$query = $this->db_conn->query($queryString);
			if ($query) {
				return true;
			} else {
				return false;
			}
		}
	}
	public function RemoveUserFromGroup($uid, $gid) {
		$queryString = "DELETE FROM " . $this->config['table_prefix'] . "users_groups WHERE userid=" . $uid . " AND groupid=" . $gid . "";
		$query = $this->db_conn->query($queryString);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	public function GetGroupInfo($id) {
		$queryString = "SELECT * FROM " . $this->config['table_prefix'] . "groups WHERE id=" . $id . "";
		$row = $this->db_conn->query($queryString);
		$fetch_single = DBMySQLi::fetch_single($row);
		return $fetch_single;
	}
	public function GetAllGroups() {
		$queryString = "SELECT * FROM " . $this->config['table_prefix'] . "groups ORDER BY title ASC";
		$rows = $this->db_conn->query($queryString);
		$rowFinal = DBMySQLi::fetch_assoc($rows);
		return $rowFinal;
	}
	public function UpdateGroup($gid, $PostArray = '') {
		if ((!isset($PostArray['title'])) or ($PostArray['title'] == '')) {
			$gid = 'N/A';
		}
		if ((!isset($PostArray['description'])) or ($PostArray['description'] == '')) {
			$gid = 'N/A';
		}
		if ((isset($gid)) && (is_numeric($gid))) {
			$queryString = "UPDATE " . $this->config['table_prefix'] . "groups SET title='" . $PostArray['title'] . "', description='" . $PostArray['description'] . "' WHERE id=" . $gid  . "";
			$rows = $this->db_conn->query($queryString);
			return true;
		} else {
			return false;
		}
	}
	public function AddGroup($PostArray) {
		$gid = 1;
		if ((!isset($PostArray['title'])) or ($PostArray['title'] == '')) {
			$gid = 'N/A';
		}
		if ((!isset($PostArray['description'])) or ($PostArray['description'] == '')) {
			$gid = 'N/A';
		}
		if ((isset($gid)) && (is_numeric($gid))) {
			$queryString = "INSERT INTO " . $this->config['table_prefix'] . "groups (title, description) VALUES ('" . $PostArray['title'] . "', '" . $PostArray['description'] . "')";
			$query = $this->db_conn->query($queryString);
			$insertid = $this->db_conn->insert_id;
			$queryString = "INSERT INTO " . $this->config['table_prefix'] . "perms (uid, gid, allow_blog_comments, add_edit_blog, delete_blog, add_edit_blog_cats, delete_blog_cats, edit_user, delete_user, add_to_group, remove_from_group, disable_users, lock_out_users, send_password) VALUES (0, " . $insertid . ", 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
			$query = $this->db_conn->query($queryString);
			return array('error' => false, 'id' => $insertid);
		} else {
			return array('error' => true, 'id' => '');;
		}
	}
	public function DeleteGroup($gid) {
		$queryString = "DELETE FROM " . $this->config['table_prefix'] . "groups WHERE id=" . $gid . "";
		$query = $this->db_conn->query($queryString);
		$queryString = "DELETE FROM " . $this->config['table_prefix'] . "perms WHERE gid=" . $gid . "";
		$query = $this->db_conn->query($queryString);
		$queryString = "DELETE FROM " . $this->config['table_prefix'] . "users_groups WHERE groupid=" . $gid . "";
		$optional = $this->db_conn->query($queryString);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}
?>