<div class="alignCenter">
  <?php if ($add_edit_blog == 1) { ?>
  	  <a href="?act=add" title="Add Blog">Add Blog</a>
  <?php } ?>
  <?php if ($delete_blog == 1) { ?>
  	   | <a href="index.php" title="Blog Entries">Blog Entries</a> | 
  <?php } ?>
  <?php if (($add_edit_blog_cats == 1) or ($add_edit_blog_cats == 1)) { ?>
  	  <a href="categories/index.php" title="Categories">Categories</a>
  <?php } ?>
</div>
<br />