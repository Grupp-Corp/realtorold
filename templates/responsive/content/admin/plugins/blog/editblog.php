<?php
if ($add_edit_blog == 1) {
	$BlogAct = new BlogActions();
	$BlogRow = $BlogAct->GetBlogSelect($_GET['id']);
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$error = 0;
		$err_mess = '';
		// Error Check
		if (!isset($_POST['catid'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Please choose a Category.</strong><br />';
		} elseif ($_POST['catid'] == '') {
			$error = 1;
			$err_mess .= '<strong class="red">Please choose a Category.</strong><br />';
		}
		if (!isset($_POST['title'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Please enter a Title.</strong><br />';
		} elseif ($_POST['title'] == '') {
			$error = 1;
			$err_mess .= '<strong class="red">Please enter a Title.</strong><br />';
		}
		if (!isset($_POST['blog_content'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Please enter the Content.</strong><br />';
		} elseif ($_POST['blog_content'] == '') {
			$error = 1;
			$err_mess .= '<strong class="red">Please enter the Content.</strong><br />';
		}
		if (!isset($_POST['keywords'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Please enter the Keywords.</strong><br />';
		} elseif ($_POST['keywords'] == '') {
			$error = 1;
			$err_mess .= '<strong class="red">Please enter the Keywords.</strong><br />';
		}
		if ($error == 0) {
			// Attempt query to update blog
			if ($BlogAct->UpdateBlog($_POST['catid'], $_POST['title'], $_POST['blog_content'], $_POST['author'], $_POST['author_id'], $_POST['keywords'], $_POST['id']) === true) {
				echo '<strong class="red">Blog Edited.</strong><br /><br />';
			} else {
				echo '<strong class="red">There was an error with the database.</strong><br /><br />';
			}
		} else {
			echo $err_mess . '<br /><br />';
		}
	}
	?>
	<form action="" method="post" id="EditBlog" name="EditBlog">
	  <div class="FormContainer">
	  <div class="FormLeftColumn"><label title="Category"><strong>Category:</strong></label></div>
		<div class="FormRightColumn">
		  <?php echo $BlogAct->ShowCategoriesInForm($BlogRow['id']); ?>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Title"><strong>Title:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="30" id="title" name="title" value="<?php if (isset($_POST['title'])) { echo $_POST['title']; } else { echo $BlogRow['title']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn">
		  <label title="Keywords"><strong>Keywords:</strong></label>
		  <br />
		  <textarea id="blog_content" name="blog_content" rows="30" cols="85"><?php if (isset($_POST['blog_content'])) { echo $_POST['blog_content']; } else { echo $BlogRow['content']; } ?></textarea>
          <br />
          <?php
		  echo $BlogAct->ShowBlogComments($BlogRow['id'], 0);
		  ?>
		  <script type="text/javascript">
			//<![CDATA[
				// Replace the <textarea id="editor1"> with an CKEditor instance.
				var editor = CKEDITOR.replace('blog_content');
			//]]>
		  </script>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Keywords"><strong>Keywords:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="35" id="keywords" name="keywords" value="<?php if (isset($_POST['keywords'])) { echo $_POST['keywords']; } else { echo $BlogRow['keywords']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<input type="hidden" id="id" name="id" value="<?php echo $BlogRow['id']; ?>" />
		<input type="hidden" id="author" name="author" value="<?php echo $BlogRow['author']; ?>" />
		<input type="hidden" id="author_id" name="author_id" value="<?php echo $BlogRow['author_id']; ?>" />
		<div class="alignCenter"><input type="submit" id="submit" name="submit" value="Edit Blog" /></div>
	  </div>
	</form>
<?php
} else {
	echo 'Access Denied.';
}
?>