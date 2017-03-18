<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
<meta charset="UTF-8">
<title><?=$title?></title>
<meta name="keywords" content="<?=$keywords?>" />
<meta name="description" content="<?=$description?>" />
<meta property="og:title" content="<?=$og_title?>">
<meta property="og:type" content="<?=$og_type?>">
<meta property="og:description" content="<?=$og_description?>">
<meta property="og:url" content="<?=$og_url?>">
<meta property="og:image" content="<?=$og_image?>">
<meta property="og:site_name" content="<?=$og_site_name?>">
<meta property="fb:app_id" content="823267361107745" />
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@gameusersorg">
<meta name="twitter:image" content="<?php echo URI_BASE . 'assets/img/social/ogp_twitter.png'; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="format-detection" content="telephone=no">
<link rel="shortcut icon" href="<?=$favicon_url?>" />
<?php echo Asset::css($css_arr); ?>
<?php echo Asset::js($js_arr); ?>
<?php if (isset($manifest)) : ?>
<link rel="manifest" href="<?=URI_BASE?>manifest.json">
<?php endif; ?>
<?=$original_js?>
</head>
<body>

<?=$header?>

  <main id="wrap">

    <div class="container">

<?=$content?>

    </div>

  </main>

<?=$footer?>

</body>
</html>
