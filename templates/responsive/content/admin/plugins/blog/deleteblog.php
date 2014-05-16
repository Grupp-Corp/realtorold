<?php
if ($delete_blog == 1) {
	// Get Blog Actions
	$BlogAct = new BlogActions();
	if (isset($_GET['id'])) {
		if (is_numeric($_GET['id'])) {
			if ($_GET['id'] > 0) {
				$id = $_GET['id'];
				$BlowRow = $BlogAct->GetBlogSelect($id); // Get selected blog
			} else {
				$id = 0;
			}
		} else {
			$id = 0;
		}
	} else {
		$id = 0;
	}
	// Submit POST
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$err_mess = '';
		$error = 0;
		if (!isset($_POST['id'])) {
			$error = 1;
			$err_mess .= '<strong class="red">The ID is not entered.</strong>';
		} elseif ($_POST['id'] == 0) {
			$error = 1;
			$err_mess .= '<strong class="red">Invalid ID.';
		} elseif (!is_numeric($_POST['id'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Invalid ID.</strong>';
		}
		// Error Check
		if ($error == 0) {
			// Attempt to add blog
			if ($BlogAct->DeleteBlog($id) === true) {
				echo '<strong class="red">Blog Entry Deleted.</strong><br /><br />';
			} else {
				echo '<strong class="red">There was an error with the database.</strong><br /><br />';
			}
		} else {
			echo $err_mess . '<br /><br />';
		}
	} else {
	?>
		<form action="" method="post" id="DeleteBlog" name="DeleteBlog">
		  <div class="alignCenter">
			<strong class="red">Are you sure you want to delete the Blog &quot;<?php echo $BlowRow['title']; ?>&quot;</strong>
		  </div>
		  <input type="hidden" id="id" name="id" value="<?php echo $BlowRow['id']; ?>" />
		  <div class="alignCenter"><input type="submit" id="submit" name="submit" value="Delete Blog" /></div>
		  </div>
		</form>
	<?php
	}
} else {
	echo 'Access Denied.';
}
?>