<?php 
$title = 'Downloads';
require_once('../includes/index.php'); 
?>
<!doctype html>
<html lang="en">
<head>
  <?php require_once(TEMPLATES_DIR . 'charset.php'); ?>
  <title><?php echo SITE_NAME . ' - ' . $title; ?></title>
  <meta name="description" content="<?php echo $title; ?>" />
  <meta name="keywords" content="<?php echo $title; ?>, <?php echo SITE_NAME; ?>" />
  <meta name="author" content="<?php require(TEMPLATES_PATH . 'meta_tags/author.txt'); ?>" />
  <?php require_once(MAIN_JS_PATH . 'index.php'); ?>
  <?php require_once(MAIN_CSS_PATH . 'index.php'); ?>
</head>

<body>
<div id="main">
  <header>
      <?php require(TEMPLATES_PATH . 'header.html'); ?>
  </header>
  <section id="content">
      <section id="ColumnOne">
          <?php require(TEMPLATES_PATH . 'top-menu.php'); ?>
      </section>
      <section id="ColumnTwo">
          <?php require(TEMPLATES_PATH . 'right-menu.html'); ?>
      </section>
      <section id="Breadcrumb">
    	  <?php require(TEMPLATES_PATH . 'breadcrumb.html'); ?>
      </section>
      <section id="Content">
          <div id="column2">
            <h1>Downloads</h1>
            <p>&nbsp;</p>
          </div>
      </section>
  </section>
  <footer>
      <?php require(TEMPLATES_PATH . 'footer.html'); ?>
  </footer>
</div>
</body>
</html>
