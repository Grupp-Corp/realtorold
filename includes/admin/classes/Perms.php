<?php
class Perms extends UserAdmin
{
	public function __construct() {
		parent::__construct();
	}
	public function UpdateGroupPerms($gid, $PostArray = '') {
		// Check Post Array Data
		if (!isset($PostArray['AllowBlogComments'])) {
			$PostArray['AllowBlogComments'] = 0;
		} else {
			$PostArray['AllowBlogComments'] = 1;
		}
		if (!isset($PostArray['AddEditBlog'])) {
			$PostArray['AddEditBlog'] = 0;
		} else {
			$PostArray['AddEditBlog'] = 1;
		}
		if (!isset($PostArray['DeleteBlog'])) {
			$PostArray['DeleteBlog'] = 0;
		} else {
			$PostArray['DeleteBlog'] = 1;
		}
		if (!isset($PostArray['AddEditBlogCats'])) {
			$PostArray['AddEditBlogCats'] = 0;
		} else {
			$PostArray['AddEditBlogCats'] = 1;
		}
		if (!isset($PostArray['DeleteBlogCats'])) {
			$PostArray['DeleteBlogCats'] = 0;
		} else {
			$PostArray['DeleteBlogCats'] = 1;
		}
		if (!isset($PostArray['FormGenerator'])) {
			$PostArray['FormGenerator'] = 0;
		} else {
			$PostArray['FormGenerator'] = 1;
		}
		if (!isset($PostArray['EditUser'])) {
			$PostArray['EditUser'] = 0;
		} else {
			$PostArray['EditUser'] = 1;
		}
		if (!isset($PostArray['DeleteUser'])) {
			$PostArray['DeleteUser'] = 0;
		} else {
			$PostArray['DeleteUser'] = 1;
		}
		if (!isset($PostArray['AddtoGroup'])) {
			$PostArray['AddtoGroup'] = 0;
		} else {
			$PostArray['AddtoGroup'] = 1;
		}
		if (!isset($PostArray['RemovefromGroup'])) {
			$PostArray['RemovefromGroup'] = 0;
		} else {
			$PostArray['RemovefromGroup'] = 1;
		}
		if (!isset($PostArray['DisableUsers'])) {
			$PostArray['DisableUsers'] = 0;
		} else {
			$PostArray['DisableUsers'] = 1;
		}
		if (!isset($PostArray['LockOutUser'])) {
			$PostArray['LockOutUser'] = 0;
		} else {
			$PostArray['LockOutUser'] = 1;
		}
		if (!isset($PostArray['SendUserPass'])) {
			$PostArray['SendUserPass'] = 0;
		} else {
			$PostArray['SendUserPass'] = 1;
		}
		if (!isset($PostArray['UserCenterAccess'])) {
			$PostArray['UserCenterAccess'] = 0;
		} else {
			$PostArray['UserCenterAccess'] = 1;
		}
		if (!isset($PostArray['AdminAccess'])) {
			$PostArray['AdminAccess'] = 0;
		} else {
			$PostArray['AdminAccess'] = 1;
		}
		// Query
		$queryString = "UPDATE " . $this->config['table_prefix'] . "perms SET allow_blog_comments=" . $PostArray['AllowBlogComments'] . ", add_edit_blog=" . $PostArray['AddEditBlog'] . ", delete_blog=" . $PostArray['DeleteBlog'] . ", add_edit_blog_cats=" . $PostArray['AddEditBlogCats'] . ", form_generator=" . $PostArray['FormGenerator'] . ", delete_blog_cats=" . $PostArray['DeleteBlogCats'] . ", edit_user=" . $PostArray['EditUser'] . ", delete_user=" . $PostArray['DeleteUser'] . ", add_to_group=" . $PostArray['AddtoGroup'] . ", remove_from_group=" . $PostArray['RemovefromGroup'] . ", disable_users=" . $PostArray['DisableUsers'] . ", lock_out_users=" . $PostArray['LockOutUser'] . ", send_password=" . $PostArray['SendUserPass'] . ", user_center_access=" . $PostArray['UserCenterAccess'] . ",  admin_access=" . $PostArray['AdminAccess'] . " WHERE gid = " . $gid . "";
		$query = $this->db_conn->query($queryString);
		// Check if query is successful
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	// Check if there are user specific permissions
	public function CheckIfUserSpecific($id) {
		// If we have a UID Permission Row, we override all other permissions regardless of group...
		$queryString = "SELECT * FROM " . $this->config['table_prefix'] . "perms WHERE uid = " . $id . "";
		$query = $this->db_conn->query($queryString);
		$TotalRows = $this->db_conn->affected_rows;
		if ($TotalRows > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Restore User's group permissions
	public function RestoreUserGroupPerms($id) {
		// Delete User Specific Permissions
		$queryString = "DELETE FROM " . $this->config['table_prefix'] . "perms WHERE uid = " . $id . "";
		$query = $this->db_conn->query($queryString);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	// Edit/Add user specific permissions
	public function EditUserSpecificPerms($id, $PostArray = '') {
		// Get User permissions
		$queryString = "SELECT * FROM " . $this->config['table_prefix'] . "perms WHERE uid = " . $id . "";
		$query = $this->db_conn->query($queryString);
		$TotalRows = $this->db_conn->affected_rows;
		// Check Post Array Data
		if (!isset($PostArray['AllowBlogComments'])) {
			$PostArray['AllowBlogComments'] = 0;
		} else {
			$PostArray['AllowBlogComments'] = 1;
		}
		if (!isset($PostArray['AddEditBlog'])) {
			$PostArray['AddEditBlog'] = 0;
		} else {
			$PostArray['AddEditBlog'] = 1;
		}
		if (!isset($PostArray['DeleteBlog'])) {
			$PostArray['DeleteBlog'] = 0;
		} else {
			$PostArray['DeleteBlog'] = 1;
		}
		if (!isset($PostArray['AddEditBlogCats'])) {
			$PostArray['AddEditBlogCats'] = 0;
		} else {
			$PostArray['AddEditBlogCats'] = 1;
		}
		if (!isset($PostArray['DeleteBlogCats'])) {
			$PostArray['DeleteBlogCats'] = 0;
		} else {
			$PostArray['DeleteBlogCats'] = 1;
		}
		if (!isset($PostArray['FormGenerator'])) {
			$PostArray['FormGenerator'] = 0;
		} else {
			$PostArray['FormGenerator'] = 1;
		}
		if (!isset($PostArray['EditUser'])) {
			$PostArray['EditUser'] = 0;
		} else {
			$PostArray['EditUser'] = 1;
		}
		if (!isset($PostArray['DeleteUser'])) {
			$PostArray['DeleteUser'] = 0;
		} else {
			$PostArray['DeleteUser'] = 1;
		}
		if (!isset($PostArray['AddtoGroup'])) {
			$PostArray['AddtoGroup'] = 0;
		} else {
			$PostArray['AddtoGroup'] = 1;
		}
		if (!isset($PostArray['RemovefromGroup'])) {
			$PostArray['RemovefromGroup'] = 0;
		} else {
			$PostArray['RemovefromGroup'] = 1;
		}
		if (!isset($PostArray['DisableUsers'])) {
			$PostArray['DisableUsers'] = 0;
		} else {
			$PostArray['DisableUsers'] = 1;
		}
		if (!isset($PostArray['LockOutUser'])) {
			$PostArray['LockOutUser'] = 0;
		} else {
			$PostArray['LockOutUser'] = 1;
		}
		if (!isset($PostArray['SendUserPass'])) {
			$PostArray['SendUserPass'] = 0;
		} else {
			$PostArray['SendUserPass'] = 1;
		}
		if (!isset($PostArray['UserCenterAccess'])) {
			$PostArray['UserCenterAccess'] = 0;
		} else {
			$PostArray['UserCenterAccess'] = 1;
		}
		if (!isset($PostArray['AdminAccess'])) {
			$PostArray['AdminAccess'] = 0;
		} else {
			$PostArray['AdminAccess'] = 1;
		}
		// Check Rows
		if ($TotalRows > 0) {
			// Query
			$queryString = "UPDATE " . $this->config['table_prefix'] . "perms SET allow_blog_comments=" . $PostArray['AllowBlogComments'] . ", add_edit_blog=" . $PostArray['AddEditBlog'] . ", delete_blog=" . $PostArray['DeleteBlog'] . ", add_edit_blog_cats=" . $PostArray['AddEditBlogCats'] . ", delete_blog_cats=" . $PostArray['DeleteBlogCats'] . ", form_generator=" . $PostArray['FormGenerator'] . ", edit_user=" . $PostArray['EditUser'] . ", delete_user=" . $PostArray['DeleteUser'] . ", add_to_group=" . $PostArray['AddtoGroup'] . ", remove_from_group=" . $PostArray['RemovefromGroup'] . ", disable_users=" . $PostArray['DisableUsers'] . ", lock_out_users=" . $PostArray['LockOutUser'] . ", send_password=" . $PostArray['SendUserPass'] . ", user_center_access=" . $PostArray['UserCenterAccess'] . ",  admin_access=" . $PostArray['AdminAccess'] . "  WHERE uid = " . $id . "";
			$query = $this->db_conn->query($queryString);
			// Check if query is successful
			if ($query) {
				return true;
			} else {
				return false;
			}
		} else {
			// Query
			$queryString = "INSERT INTO " . $this->config['table_prefix'] . "perms (uid, gid, allow_blog_comments, add_edit_blog, delete_blog, add_edit_blog_cats, delete_blog_cats, form_generator, edit_user, delete_user, add_to_group, remove_from_group, disable_users, lock_out_users, send_password, user_center_access, admin_access) VALUES (" . $id . ", 0, " .  $PostArray['AllowBlogComments'] . ", " .  $PostArray['AddEditBlog'] . ", " .  $PostArray['DeleteBlog'] . ", " .  $PostArray['AddEditBlogCats'] . ", " .  $PostArray['DeleteBlogCats'] . ", " . $PostArray['FormGenerator'] . ", " .  $PostArray['EditUser'] . ", " .  $PostArray['DeleteUser'] . ", " .  $PostArray['AddtoGroup'] . ", " . $PostArray['RemovefromGroup'] . ", " . $PostArray['DisableUsers'] . ", " . $PostArray['LockOutUser'] . ", " . $PostArray['SendUserPass'] . ", " . $PostArray['UserCenterAccess'] . ", " . $PostArray['AdminAccess'] . ")";
			$query = $this->db_conn->query($queryString);
			// Check if query is successful
			if ($query) {
				return true;
			} else {
				return false;
			}
		}
	}
}
?>