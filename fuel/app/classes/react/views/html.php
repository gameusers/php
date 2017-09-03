<!DOCTYPE html>
<html lang="<?=$language?>">
<head>
  <meta charset="UTF-8">
  <title><?=$title?></title>
  <meta name="keywords" content="<?=$metaKeywords?>" />
  <meta name="description" content="<?=$metaDescription?>" />
  <meta property="og:title" content="<?=$title?>">
  <meta property="og:type" content="<?=$metaOgType?>">
  <meta property="og:description" content="<?=$metaDescription?>">
  <meta property="og:url" content="<?=URL_CURRENT?>">
  <meta property="og:image" content="<?=URL_BASE?>assets/img/social/ogp_image.jpg">
  <meta property="og:site_name" content="Game Users">
  <meta property="fb:app_id" content="823267361107745" />
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="@gameusersorg">
  <meta name="twitter:image" content="<?=URL_BASE?>assets/img/social/ogp_twitter.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="format-detection" content="telephone=no">
  <link rel="shortcut icon" href="<?=URL_BASE?>favicon.ico" />
<?php foreach ($cssArr as $key => $valueArr): ?>
  <link type="text/css" rel="stylesheet"<?php foreach ($valueArr as $key => $value): ?> <?=$key?>="<?=$value?>"<?php endforeach; ?>>
<?php endforeach; ?>
  <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<?php if (isset($manifest)) : ?>
  <link rel="manifest" href="<?=URL_BASE?>manifest.json">
<?php endif; ?>
<?php
echo '  <script type="text/javascript">' . "\n";
echo '  function gameUsersInitialStateObj() {' . "\n";
echo '  var obj = ' . json_encode($initialStateArr, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ";\n";
echo '  return obj;' . "\n";
echo '  }' . "\n";
echo '  </script>' . "\n";
?>
</head>
<body>

  <div id="gameusers-root"></div>

<?php foreach ($jsArr as $key => $valueArr): ?>
  <script type="text/javascript"<?php foreach ($valueArr as $key => $value): ?> <?=$key?>="<?=$value?>"<?php endforeach; ?>></script>
<?php endforeach; ?>

</body>
</html>
