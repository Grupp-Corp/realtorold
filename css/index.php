<?php if (isset($template_name)) { ?>
	<?php include(MAIN_CSS_PATH . $template_name . '/index.php'); ?>
<?php } else { ?>
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
    <![endif]-->
    <link type="text/css" href="/css/main/main.css" rel="stylesheet" media="all" />
    <!--[if IE 6]><link type="text/css" href="/css/main/main-ie6.css" rel="stylesheet" media="all" /><![endif]-->
    <!--[if IE 7]><link type="text/css" href="/css/main/main-ie7.css" rel="stylesheet" media="all" /><![endif]-->
<?php } ?>