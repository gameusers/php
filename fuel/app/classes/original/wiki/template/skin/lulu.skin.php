<?php
// License: GPL v2 or (at your option) any later version
//
// PukiWiki another skin "LuLu"

// ------------------------------------------------------------
// Settings (define before here, if you want)

// Set site identities
$_IMAGE['skin']['favicon']  = ''; // Sample: 'image/favicon.ico';

// SKIN_DEFAULT_DISABLE_TOPICPATH
//   1 = Show reload URL
//   0 = Show topicpath
if (! defined('SKIN_DEFAULT_DISABLE_TOPICPATH'))
	define('SKIN_DEFAULT_DISABLE_TOPICPATH', 0); // 1, 0

// Show / Hide navigation bar UI at your choice
// NOTE: This is not stop their functionalities!
if (! defined('PKWK_SKIN_SHOW_NAVBAR'))
	define('PKWK_SKIN_SHOW_NAVBAR', 1); // 1, 0

// Show / Hide toolbar UI at your choice
// NOTE: This is not stop their functionalities!
if (! defined('PKWK_SKIN_SHOW_TOOLBAR'))
	define('PKWK_SKIN_SHOW_TOOLBAR', 1); // 1, 0

// ------------------------------------------------------------
// Code start

// Prohibit direct access
if (! defined('UI_LANG')) die('UI_LANG is not set');
if (! isset($_LANG)) die('$_LANG is not set');
if (! defined('PKWK_READONLY')) die('PKWK_READONLY is not set');

$lang  = & $_LANG['skin'];
$link  = & $_LINK;
$image = & $_IMAGE['skin'];
$rw    = ! PKWK_READONLY;

// Decide charset for CSS
$css_charset = 'iso-8859-1';
switch(UI_LANG){
	case 'ja': $css_charset = 'Shift_JIS'; break;
}

// ------------------------------------------------------------
// Output

// HTTP headers
pkwk_common_headers();
header('Cache-control: no-cache');
header('Pragma: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);


// --------------------------------------------------
//   オリジナル　meta作成
// --------------------------------------------------

if($title == $page_title)
{
	$meta_title = $page_title . ' - Game Users';
}
else
{
	$meta_title = $title . ' - ' . $page_title;
}

$meta_description = $title . 'について';
$meta_og_url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ($nofollow || ! $is_read)  { ?> <meta name="robots" content="NOINDEX,NOFOLLOW" /><?php } ?>

	<meta name="keywords" content="Wiki,攻略,Game Users" />
	<meta name="description" content="<?php echo $meta_description; ?>" />
	<meta property="og:title" content="<?php echo $meta_title; ?>">
	<meta property="og:type" content="article">
	<meta property="og:description" content="<?php echo $meta_description; ?>">
	<meta property="og:url" content="<?php echo $meta_og_url; ?>">
	<meta property="og:image" content="https://gameusers.org/assets/img/social/ogp_image.jpg">
	<meta property="og:site_name" content="Game Users">
	<meta property="fb:app_id" content="823267361107745" />
	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="@gameusersorg">
	<meta name="twitter:image" content="https://gameusers.org/assets/img/social/ogp_twitter.png">

	<title><?php echo $meta_title ?></title>

	<link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="../../assets/css/jquery/magnific-popup.css" />
	<link type="text/css" rel="stylesheet" href="../../assets/css/jquery/swiper.min.css" />
	<link rel="stylesheet" type="text/css" href="skin/assets/lulu/css/base.css" />
	<link rel="stylesheet" type="text/css" href="../../assets/css/wiki.min.css" />

	<link rel="shortcut icon" href="https://gameusers.org/favicon.ico" />

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<?php echo $head_tag ?>
</head>
<body>

<header>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php echo str_replace('index.php?FrontPage', '', $link['top']) ?>"><?php echo $page_title ?></a>
				<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div><!-- .navbar-header -->
			<div class="navbar-collapse collapse" id="navbar-main" aria-expanded="true" style>
				<ul class="nav navbar-nav">

				<?php if(PKWK_SKIN_SHOW_NAVBAR) { ?>
				<?php
				function _navigator($key, $value = '', $javascript = ''){
					$lang = & $GLOBALS['_LANG']['skin'];
					$link = & $GLOBALS['_LINK'];
					if (! isset($lang[$key])) { echo 'LANG NOT FOUND'; return FALSE; }
					if (! isset($link[$key])) { echo 'LINK NOT FOUND'; return FALSE; }
					if (! PKWK_ALLOW_JAVASCRIPT) $javascript = '';

					echo '<a href="' . $link[$key] . '" ' . $javascript . '>' .
						(($value === '') ? $lang[$key] : $value) .
						'</a>';

					return TRUE;
				}
				?>

				<?php if ($is_page) { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Edit<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">

					<?php if ($rw) { ?>
						<li><?php _navigator('edit') ?>
						<?php if ($is_read && $function_freeze) { ?>
							<li><?php (! $is_freeze) ? _navigator('freeze') : _navigator('unfreeze') ?>
						<?php } ?>
					<?php } ?>
					<li><?php _navigator('diff') ?>
					<?php if ($do_backup) { ?>
						<li><?php _navigator('backup') ?>
					<?php } ?>
					<?php if ($rw && (bool)ini_get('file_uploads')) { ?>
						<li><?php _navigator('upload') ?>
					<?php } ?>
					<li><?php _navigator('reload') ?>

					</ul><!-- .dropdown-menu -->
				</li><!-- .dropdown -->
				<?php } ?>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menu<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">

					<?php if ($rw) { ?>
						<li><?php _navigator('new') ?>
					<?php } ?>
					<li><?php _navigator('list') ?>
					<?php if (arg_check('list')) { ?>
						<li><?php _navigator('filelist') ?>
					<?php } ?>
					<li><?php _navigator('search') ?>
					<li><?php _navigator('recent') ?>
					<li><?php _navigator('help')   ?>

					</ul><!-- .dropdown-menu -->
				</li><!-- .dropdown -->
				</ul>
<?php } // PKWK_SKIN_SHOW_NAVBAR ?>
			</div><!-- .navbar-collapse -->
		</div><!-- .container -->
	</div><!-- .navbar -->
</header>
<div class="container">

<?php if (arg_check('read') && exist_plugin_convert('menu')) { ?>
	<div class="bs-docs-section">

		<div class="row">
			<div class="col-md-9" id="col-body">

<aside class="gu_ad_top" id="gu_ad_default_1"></aside>

				<div class="page-header">

					<?php if ($is_page) { ?>
					 <?php if(SKIN_DEFAULT_DISABLE_TOPICPATH) { ?>
					   <a href="<?php echo $link['reload'] ?>"><span class="small"><?php echo $link['reload'] ?></span></a>
					 <?php } else { ?>
					   <span class="small">
					   <?php require_once(PLUGIN_DIR . 'topicpath.inc.php'); echo plugin_topicpath_inline(); ?>
					   </span>
					 <?php } ?>
					<?php } ?>

					<h1 id="type"><?php echo $title ?></h1>

				</div>
				<?php echo $body ?>

<hr>
<div class="social_box">
  <div class="swiper-container" id="swiper_container_social_button">
	<div class="swiper-wrapper">

      <div class="swiper-slide social_button_box" id="twitter" onclick="socialShare(this)">
	    <img src="https://gameusers.org/assets/img/social/social_twitter.png" width="53" height="58">
	    <span class="social_button_count font_weight_bold">-</span>
	  </div>

	  <div class="swiper-slide social_button_box" id="facebook" onclick="socialShare(this)">
	    <img src="https://gameusers.org/assets/img/social/social_facebook.png" width="53" height="58">
	    <span class="social_button_count font_weight_bold">-</span>
	  </div>

	  <div class="swiper-slide social_button_box" id="google_plus" onclick="socialShare(this)">
	    <img src="https://gameusers.org/assets/img/social/social_google_plus.png" width="53" height="58">
	    <span class="social_button_count font_weight_bold">-</span>
	  </div>

	  <div class="swiper-slide social_button_box" id="hatena" onclick="socialShare(this)">
	    <img src="https://gameusers.org/assets/img/social/social_hatena.png" width="53" height="58">
	    <span class="social_button_count font_weight_bold">-</span>
	  </div>

	  <div class="swiper-slide social_button_box" id="pocket" onclick="socialShare(this)">
	    <img src="https://gameusers.org/assets/img/social/social_pocket.png" width="53" height="58">
	    <span class="social_button_count font_weight_bold">-</span>
	  </div>

	  <div class="swiper-slide social_button_box" id="line" onclick="socialShare(this)">
	    <img src="https://gameusers.org/assets/img/social/social_line.png" width="53" height="58">
	    <span class="social_button_count">LINE</span>
	  </div>

	  <div class="swiper-slide social_button_box" id="email" onclick="socialShare(this)">
	    <img src="https://gameusers.org/assets/img/social/social_email.png" width="53" height="58">
	    <span class="social_button_count">Mail</span>
	  </div>

	</div>
  </div>
</div>
<hr>

<aside class="" id="gu_ad_amazon_slide"></aside>

<aside class="gu_ad_bottom clearfix">

<aside class="gu_ad_bottom_rectangle" id="gu_ad_default_2"></aside>
<aside class="gu_ad_bottom_rectangle" id="gu_ad_default_3"></aside>

</aside>

			</div>
			<div class="col-md-3" id="col-menu">
				<?php echo $hr ?>
				<p id="gu_menu_read_bbs"></p>
				<?php echo $hr ?>
				<?php echo do_plugin_convert('menu') ?>
			</div>
		</div><!-- .row -->
	</div><!-- .bs-docs-section -->
<?php } else { ?>
	<div class="bs-docs-section">
		<div class="row">
			<div class="col-md-12" id="col-body">
				<?php echo $body ?>
			</div>
		</div><!-- .row -->
	</div><!-- .bs-docs-section -->
<?php } ?>

<?php if ($notes != '') { ?>
<div id="note"><?php echo $notes ?></div>
<?php } ?>

<?php if ($attaches != '') { ?>
<div id="attach">
<?php echo $hr ?>
<?php echo $attaches ?>
</div>
<?php } ?>

<?php echo $hr ?>

<?php if (PKWK_SKIN_SHOW_TOOLBAR) { ?>
<!-- Toolbar -->
<div id="toolbar">
<?php

// Set toolbar-specific images
$_IMAGE['skin']['reload']   = 'reload.png';
$_IMAGE['skin']['new']      = 'new.png';
$_IMAGE['skin']['edit']     = 'edit.png';
$_IMAGE['skin']['freeze']   = 'freeze.png';
$_IMAGE['skin']['unfreeze'] = 'unfreeze.png';
$_IMAGE['skin']['diff']     = 'diff.png';
$_IMAGE['skin']['upload']   = 'file.png';
$_IMAGE['skin']['copy']     = 'copy.png';
$_IMAGE['skin']['rename']   = 'rename.png';
$_IMAGE['skin']['top']      = 'top.png';
$_IMAGE['skin']['list']     = 'list.png';
$_IMAGE['skin']['search']   = 'search.png';
$_IMAGE['skin']['recent']   = 'recentchanges.png';
$_IMAGE['skin']['backup']   = 'backup.png';
$_IMAGE['skin']['help']     = 'help.png';
$_IMAGE['skin']['rss']      = 'rss.png';
$_IMAGE['skin']['rss10']    = & $_IMAGE['skin']['rss'];
$_IMAGE['skin']['rss20']    = 'rss20.png';
$_IMAGE['skin']['rdf']      = 'rdf.png';

function _toolbar($key, $x = 20, $y = 20){
	$lang  = & $GLOBALS['_LANG']['skin'];
	$link  = & $GLOBALS['_LINK'];
	$image = & $GLOBALS['_IMAGE']['skin'];
	if (! isset($lang[$key]) ) { echo 'LANG NOT FOUND';  return FALSE; }
	if (! isset($link[$key]) ) { echo 'LINK NOT FOUND';  return FALSE; }
	if (! isset($image[$key])) { echo 'IMAGE NOT FOUND'; return FALSE; }

	echo '<a href="' . $link[$key] . '">' .
		'<img src="' . IMAGE_DIR . $image[$key] . '" width="' . $x . '" height="' . $y . '" ' .
			'alt="' . $lang[$key] . '" title="' . $lang[$key] . '" />' .
		'</a>';
	return TRUE;
}
?>
 <?php _toolbar('top') ?>

<?php if ($is_page) { ?>
 &nbsp;
 <?php if ($rw) { ?>
	<?php _toolbar('edit') ?>
	<?php if ($is_read && $function_freeze) { ?>
		<?php if (! $is_freeze) { _toolbar('freeze'); } else { _toolbar('unfreeze'); } ?>
	<?php } ?>
 <?php } ?>
 <?php _toolbar('diff') ?>
<?php if ($do_backup) { ?>
	<?php _toolbar('backup') ?>
<?php } ?>
<?php if ($rw) { ?>
	<?php if ((bool)ini_get('file_uploads')) { ?>
		<?php _toolbar('upload') ?>
	<?php } ?>
	<?php _toolbar('copy') ?>
	<?php _toolbar('rename') ?>
<?php } ?>
 <?php _toolbar('reload') ?>
<?php } ?>
 &nbsp;
<?php if ($rw) { ?>
	<?php _toolbar('new') ?>
<?php } ?>
 <?php _toolbar('list')   ?>
 <?php _toolbar('search') ?>
 <?php _toolbar('recent') ?>
 &nbsp; <?php _toolbar('help') ?>
 &nbsp; <?php _toolbar('rss10', 36, 14) ?>
</div>
<?php } // PKWK_SKIN_SHOW_TOOLBAR ?>

<?php if ($lastmodified != '') { ?>
<div id="lastmodified">Last-modified: <?php echo $lastmodified ?></div>
<?php } ?>

<?php if ($related != '') { ?>
<div id="related">Link: <?php echo $related ?></div>
<?php } ?>

</div><!-- .container -->

<footer>
	Site admin: <a href="<?php echo $modifierlink ?>"><?php echo $modifier ?></a><br>
	<?php echo S_COPYRIGHT ?>.
	HTML convert time: <?php echo $taketime ?> sec.
</footer>

<script src="../../assets/js/jquery/jquery-3.1.0.min.js"></script>
<script src="../../assets/js/bootstrap/bootstrap.min.js"></script>
<script src="skin/assets/lulu/js/base.js"></script>
<script type="text/javascript" src="../../assets/js/jquery/jquery.magnific-popup.min.js"></script>
<script type="text/javascript" src="../../assets/js/jquery/swiper.jquery.min.js"></script>
<script type="text/javascript" src="../../assets/js/wiki.min.js"></script>
<?php if (PKWK_ALLOW_JAVASCRIPT && $trackback_javascript) { ?><script type="text/javascript" src="skin/trackback.js"></script><?php } ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65840111-1', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>
