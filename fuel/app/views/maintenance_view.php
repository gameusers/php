<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Game Users / Maintenance Now</title>
<meta name="keywords" content="Game,Users" />
<meta name="description" content="Game Users" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="format-detection" content="telephone=no">
<link rel="shortcut icon" href="favicon.ico" />
<?php echo Asset::css(array(Config::get('css_bootstrap'), Config::get('css_basic'), 'style.css')); ?>
</head>
<body>

  <header class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <div class="navbar-brand">Game Users</div>
      </div>
    </div>
  </header>

  <div id="wrap">
    <div class="container">
      <div class="content_box">

        <div class="c-wrapper">
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="false">
            <div class="carousel-inner">
              <div class="item active">
                <?php echo Asset::img(array('index/image_1.png')); ?>
              </div>
            </div>
          </div>
        </div>

        <h2>Maintenance Now</h2>
        <p class="explanation">Maintenance Now</p>
    
      </div>
    </div>
  </div>

</body>
</html>