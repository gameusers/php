<?php

/*
  必要なデータ

  オプション
*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------


// --------------------------------------------------
//   変数
// --------------------------------------------------

//$keywords = implode(',', $keywords_arr);

$description = str_replace(array("\r\n","\r","\n"), ' ', $description);
$description = (mb_strlen($description) > 120) ? mb_substr($description, 0, 119, 'UTF-8') . '…' : $description;

?>

<meta charset="UTF-8">
<title><?=$title?></title>
<meta name="keywords" content="<?=$keywords?>" />
<meta name="description" content="<?=$description?>" />
<meta property="og:title" content="<?=$title?>">
<meta property="og:type" content="<?=$og_type?>">
<meta property="og:description" content="<?=$description?>">
<meta property="og:url" content="<?=URI_CURRENT?>">
<meta property="og:image" content="<?=URI_BASE?>assets/img/social/ogp_image.jpg">
<meta property="og:site_name" content="Game Users">
<meta property="fb:app_id" content="823267361107745" />
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@gameusersorg">
<meta name="twitter:image" content="<?=URI_BASE?>assets/img/social/ogp_twitter.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="format-detection" content="telephone=no">
<link rel="shortcut icon" href="<?=URI_BASE?>favicon.ico" />
