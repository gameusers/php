<?php

/*
  必要なデータ
  array / $game_no_arr / Game No の配列
  array / $tab_arr / タブ用データ

  integer / $community_no / コミュニティNo
  string / $community_id / コミュニティID
  string / $community_name / コミュニティ名

  オプション

*/

// \Debug::dump($tab_arr);
//\Debug::dump($game_no_arr);


// --------------------------------------------------
//   インスタンス
// --------------------------------------------------

$model_image = new \Model_Image();
$model_game = new \Model_Game();
$original_func_common = new \Original\Func\Common();


// --------------------------------------------------
//   変数
// --------------------------------------------------

$hero_image_arr = null;
$hero_image_id = null;
$hero_thumbnail_url = null;
$s = (AGENT_TYPE) ? '_s' : null;

$code_data_hardware = $code_data_genre = $code_data_players_max = $code_data_release_date = $code_data_developer = $code_data_name = $code_link = null;

$db_link_arr = [];


// --------------------------------------------------
//   データ取得　コミュニティのヒーローイメージ
// --------------------------------------------------

if (isset($community_no))
{
  $temp_arr = ['community_no' => $community_no];
  $hero_image_arr = $model_image->select_header_hero_image_community($temp_arr);
  $hero_image_arr = \Security::htmlentities($hero_image_arr);
  //\Debug::dump($hero_image_arr);
}


// --------------------------------------------------
//   データ取得　コミュニティのヒーローイメージがない
//   またはゲームページの場合、こちらでhero_gameを取得する
// --------------------------------------------------

if ( ! $hero_image_arr)
{
  $temp_arr = ['game_no_arr' => $game_no_arr];
  $hero_image_arr = $model_image->select_header_hero_image_game($temp_arr);
  $hero_image_arr = \Security::htmlentities($hero_image_arr);
// \Debug::dump($hero_image_arr);
  $game_no = $hero_image_arr['game_no'] ?? $game_no_arr[0];


  // --------------------------------------------------
  //   データ取得　ゲームデータ
  // --------------------------------------------------

  $db_game_data_arr = $model_game->get_game_data($game_no, null);
  $db_game_data_arr = \Security::htmlentities($db_game_data_arr);

  $hardware_no_arr = $original_func_common->return_db_array('db_php', $db_game_data_arr['hardware']);
  $genre_no_arr = $original_func_common->return_db_array('db_php', $db_game_data_arr['genre']);
  $developer_no_arr = $original_func_common->return_db_array('db_php', $db_game_data_arr['developer']);


  // ---------------------------------------------
  //   ゲーム名
  // ---------------------------------------------

  $code_data_name = $db_game_data_arr['name_ja'];
  if ($db_game_data_arr['subtitle']) $code_data_name .= ' ' . $db_game_data_arr['subtitle'];


  // ---------------------------------------------
  //   プレイ人数
  // ---------------------------------------------


  if ($db_game_data_arr['players_max'] == 1)
  {
    $code_data_players_max = '1人';
  }
  else if ($db_game_data_arr['players_max'] > 1)
  {
    $code_data_players_max = '1-' . $db_game_data_arr['players_max'] . '人';
  }
  else
  {
    $code_data_players_max = null;
  }

  // ---------------------------------------------
  //   発売日　一番古い日付を表示
  // ---------------------------------------------

  //$code_data_release_date = null;
  if ($db_game_data_arr['release_date_1'])
  {

    $release_date_1 = ($db_game_data_arr['release_date_1']) ? new \DateTime($db_game_data_arr['release_date_1']) : null;
    $release_date_2 = ($db_game_data_arr['release_date_2']) ? new \DateTime($db_game_data_arr['release_date_2']) : null;
    $release_date_3 = ($db_game_data_arr['release_date_3']) ? new \DateTime($db_game_data_arr['release_date_3']) : null;
    $release_date_4 = ($db_game_data_arr['release_date_4']) ? new \DateTime($db_game_data_arr['release_date_4']) : null;
    $release_date_5 = ($db_game_data_arr['release_date_5']) ? new \DateTime($db_game_data_arr['release_date_5']) : null;

    $datetime = $release_date_1;
    if ($release_date_2 and $datetime > $release_date_2) $datetime = $release_date_2;
    if ($release_date_3 and $datetime > $release_date_3) $datetime = $release_date_3;
    if ($release_date_4 and $datetime > $release_date_4) $datetime = $release_date_4;
    if ($release_date_5 and $datetime > $release_date_5) $datetime = $release_date_5;

    $code_data_release_date = $datetime->format('Y/n/j');

    // \Debug::dump($release_date_1, $release_date_2, $release_date_3, $release_date_4, $release_date_5, $datetime);
  }




  // --------------------------------------------------
  //   データ取得　ハードウェア
  // --------------------------------------------------

  // $code_data_hardware = null;

  if (count($hardware_no_arr) > 0)
  {
    $db_hardware_arr = $model_game->select_hardware_for_header(['language' => 'ja', 'hardware_no_arr' => $hardware_no_arr]);
    $db_hardware_arr = \Security::htmlentities($db_hardware_arr);

    $temp_arr = [];
    foreach ($db_hardware_arr as $key => $value) array_push($temp_arr, $value['abbreviation']);
    $code_data_hardware = implode(', ', $temp_arr);

    // \Debug::dump($hardware_no_arr, $db_hardware_arr);
  }


  // --------------------------------------------------
  //   データ取得　ジャンル
  // --------------------------------------------------

  // $code_data_genre = null;

  if (count($genre_no_arr) > 0)
  {
    $db_genre_arr = $model_game->select_data_genre_for_header(['genre_no_arr' => $genre_no_arr]);
    $db_genre_arr = \Security::htmlentities($db_genre_arr);

    $temp_arr = [];
    foreach ($db_genre_arr as $key => $value) array_push($temp_arr, $value['name']);
    $code_data_genre = implode(', ', $temp_arr);

    //\Debug::dump($db_genre_arr);
  }


  // --------------------------------------------------
  //   データ取得　開発
  // --------------------------------------------------

  // $code_data_developer = null;

  if (count($developer_no_arr) > 0)
  {
    $db_developer_arr = $model_game->select_data_developer_for_header(['developer_no_arr' => $developer_no_arr]);
    $db_developer_arr = \Security::htmlentities($db_developer_arr);
// \Debug::dump($developer_no_arr, $db_developer_arr);
    $temp_arr = [];
    foreach ($db_developer_arr as $key => $value)
    {
      //$temp = $value['abbreviation'] ?? $value['name'];
      $temp = $value['studio'] ?? $value['name'];
      array_push($temp_arr, $temp);
    }
    $code_data_developer = implode(', ', $temp_arr);

    //\Debug::dump($db_genre_arr);
  }


  // --------------------------------------------------
  //   データ取得　リンク
  // --------------------------------------------------

  if ($game_no)
  {
    $db_link_arr = $model_game->select_data_link_for_header(['game_no' => $game_no]);
    $db_link_arr = \Security::htmlentities($db_link_arr);

    //\Debug::dump($db_link_arr);
  }

}



// --------------------------------------------------
//   ヒーローイメージがある場合
// --------------------------------------------------

if ($hero_image_arr)
{
  $hero_image_id = $hero_image_arr['image_id'];
  $hero_renewal_date = $hero_image_arr['renewal_date'];

  if (isset($community_no))
  {
    $hero_name = $community_name;
    $hero_link = URI_BASE . 'uc/' . $community_id;
  }
  else
  {
    $hero_name = $hero_image_arr['game_name'];
    $hero_link = URI_BASE . 'gc/' . $hero_image_arr['game_id'];
  }

}

// --------------------------------------------------
//   ヒーローイメージがない場合
// --------------------------------------------------

else
{

  // ---------------------------------------------
  //   ゲームページURL
  // ---------------------------------------------

  $hero_link = URI_BASE . 'gc/' . $db_game_data_arr['id'];


  // ---------------------------------------------
  //   サムネイル
  // ---------------------------------------------

  if ($db_game_data_arr['thumbnail'])
  {
    $hero_thumbnail_url = URI_BASE . 'assets/img/game/' . $game_no . '/thumbnail.jpg';
  }
  else
  {
    $datetime_thumbnail_none = new \DateTime($db_game_data_arr['renewal_date']);
    $thumbnail_none_second = $datetime_thumbnail_none->format('s');
    $hero_thumbnail_url = URI_BASE . 'assets/img/common/thumbnail_none_' . $thumbnail_none_second . '.png';
  }


  // ---------------------------------------------
  //   ゲーム名
  // ---------------------------------------------

  $hero_name = $db_game_data_arr['name_ja'];

}


// --------------------------------------------------
//   データ
// --------------------------------------------------

$code_data = "ハード | {$code_data_hardware}<br>\n";
$code_data .= "ジャンル | {$code_data_genre}<br>\n";
$code_data .= "プレイ人数 | {$code_data_players_max}<br>\n";
$code_data .= "発売日 | {$code_data_release_date}<br>\n";
$code_data .= "開発 | {$code_data_developer}<br>\n";


// --------------------------------------------------
//   データ　リンク
// --------------------------------------------------

foreach ($db_link_arr as $key => $value)
{

  if ($value['type'] === 'Official')
  {
    $code_link .= '<span class="icon"><a href="' . $value['url'] . '"><button type="button" class="btn btn-danger btn-xs">公式</button></a></span>' . "\n";
  }
  else if ($value['type'] === 'Twitter')
  {
    $code_link .= '<span class="icon"><a href="' . $value['url'] . '"><img src="' . URI_BASE . 'assets/img/common/twitter@2x.png" width="20" height="20"></a></span>' . "\n";
  }
  else if ($value['type'] === 'Facebook')
  {
    $code_link .= '<span class="icon"><a href="' . $value['url'] . '"><img src="' . URI_BASE . 'assets/img/common/facebook@2x.png" width="20" height="20"></a></span>' . "\n";
  }
  else if ($value['type'] === 'YouTube')
  {
    $code_link .= '<span class="icon"><a href="' . $value['url'] . '"><img src="' . URI_BASE . 'assets/img/common/youtube_alt@2x.png" width="20" height="20"></a></span>' . "\n";
  }
  else if ($value['type'] === 'Steam')
  {
    $code_link .= '<span class="icon"><a href="' . $value['url'] . '"><img src="' . URI_BASE . 'assets/img/common/stream@2x.png" width="20" height="20"></a></span>' . "\n";
  }
  else if ($value['type'] === 'etc')
  {
    $code_link .= '<span class="icon"><a href="' . $value['url'] . '"><button type="button" class="btn btn-danger btn-xs">' . $value['name'] . '</button></a></span>' . "\n";
  }
}


// \Debug::$js_toggle_open = true;
// \Debug::dump($hero_image_arr, $db_game_data_arr);

// echo '$db_hardware_arr';
// \Debug::dump($db_hardware_arr);
//exit();

?>

<header class="cd-auto-hide-header">

  <div class="logo">
    <a href="<?=URI_BASE?>"><img src="<?=URI_BASE?>assets/img/common/gameusers_logo.png" alt="Game Users"></a>
<?php if(USER_NO): ?>
    <div class="bell_box" id="header_notifications" data-user_no="<?=USER_NO?>">
      <div class="bell"><span class="glyphicon glyphicon-bell" aria-hidden="true"></span></div>
      <div class="bell_number"><span class="badge" id="header_notifications_unread_total" data-unread_id="">-</span></div>
    </div>
<?php endif; ?>
  </div>

  <nav class="cd-primary-nav">
    <a href="#cd-navigation" class="nav-trigger">
      <span>
        <em aria-hidden="true"></em>
        Menu
      </span>
    </a>

    <ul id="cd-navigation">
<?php if(USER_NO and USER_ID): ?>
      <li><a href="<?php echo URI_BASE . 'pl/' . USER_ID;?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> プレイヤー</a></li>
<?php endif; ?>
      <li><a href="<?php echo URI_BASE . 'help';?>"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> ヘルプ</a></li>
<?php if(USER_NO and USER_ID): ?>
      <li><a href="<?php echo URI_BASE . 'logout';?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> ログアウト</a></li>
<?php else: ?>
      <li><a href="<?php echo URI_BASE . 'login';?>"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> ログイン</a></li>
<?php endif; ?>
    </ul>
  </nav>

</header>

<?php if ($hero_image_arr): //ヒーローイメージがある場合 ?>

<section class="cd-hero">
  <div class="cd-hero-content" id="hero_image" style="background: url(<?=URI_BASE?>assets/img/u/<?=$hero_image_id?><?php if (AGENT_TYPE === 'smartphone') echo '_s'; ?>.jpg?<?=strtotime($hero_renewal_date);?>) no-repeat center center; background-size: cover;">

    <div class="hero_image_box">

      <a href="<?=$hero_link?>" class="hero_title_link"><h1 class="hero_title<?=$s?>"><?=$hero_name?></h1></a>
<?php if ($code_data_name) : ?>
      <div class="hero_image_data_right">

        <div class="hero_image_data">
          <div class="title"><?=$code_data_name?></div>
          <p class="data">
<?=$code_data?>
          </p>
          <div class="link">
<?=$code_link?>
          </div>
        </div>

      </div>
<?php endif; ?>
    </div>

  </div>
</section>

<?php else: //ヒーローイメージがない場合 ?>

<section class="cd-hero-s">
  <div class="cd-hero-content" id="hero_image" style="background: url(<?=URI_BASE?>assets/img/common/header_back.jpg) no-repeat center center; background-size: cover;">

    <div class="card_hero_box">

      <div class="card_hero">
        <a href="<?=$hero_link?>" class="card_link">
          <div class="image"><img src="<?=$hero_thumbnail_url?>"></div>
        </a>
      </div>

      <div class="hero_image_data">
        <h1 class="title"><?=$code_data_name?></h1>
        <p class="data">
<?=$code_data?>
        </p>
        <div class="link">
<?=$code_link?>
        </div>
      </div>

    </div>

  </div>
</section>

<?php endif; ?>

<nav class="cd-secondary-nav">
  <ul>
<?php foreach ($tab_arr as $key => $value): ?>
<?php

$tab_url = $value['url'] ?? null;
$tab_group = $value['group'] ?? null;
//$tab_content = $value['content'] ?? 'index';
$tab_active = ($value['active']) ? 'class="active"' : null;
$text = ($value['group'] !== 'help') ? $value['text'] : '<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>';

?>
    <li id="tab_<?=$tab_group?>"><a <?=$tab_active?> href="<?=$tab_url?>" data-group="<?=$tab_group?>"><?=$text?></a></li>
<?php endforeach; ?>
  </ul>
</nav>
