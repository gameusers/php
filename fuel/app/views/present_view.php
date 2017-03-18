<?php

/*
 * 必要なデータ
 * 
 * 
 * オプション
 * boolean / $app_mode / アプリの場合
 */

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$original_code_basic = new Original\Code\Basic();
if (isset($app_mode)) $original_code_basic->app_mode = $app_mode;


// --------------------------------------------------
//   先週の日曜日取得
// --------------------------------------------------

$datetime = new \DateTime('today');

// 今日が日曜日でない場合、-2
if ($datetime->format('w') != 0)
{
	$datetime->modify('-2 Sunday');
}
// 今日が日曜日の場合、-1
else
{
	$datetime->modify('-1 Sunday');
}
$regi_date = $datetime->format("Y-m-d H:i:s");


// --------------------------------------------------
//   管理者
// --------------------------------------------------

$administrator = (Auth::member(100)) ? true : false;

?>

<div class="content_box" id="<?=$content_id?>">
  
<?php

if (isset($top_image_arr))
{
	


// --------------------------------------------------
//   トップ画像
// --------------------------------------------------

echo '  <div class="c-wrapper">' . "\n\n";
echo '    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="false">' . "\n\n";
echo '      <div class="carousel-inner">' . "\n\n";

$repeat_count = 1;

foreach ($top_image_arr as $key => $value)
{
	
	if ($value['width'] and $value['height'])
	{
		if ($repeat_count == 1)
		{
			echo '        <div class="item active">' . "\n";
		}
		else
		{
			echo '        <div class="item">' . "\n";
		}
		echo '          <img src="' . $uri_base . 'assets/img/present/' . $key . '.png? ' . strtotime($top_image_renewal_date) . '" width="' . $value['width'] . '" height="' . $value['height'] . '">' . "\n";
		echo '        </div>' . "\n\n";
	}
	
	$repeat_count++;
	
}

echo '      </div>' . "\n\n";
echo '    </div>' . "\n\n";
echo '  </div>' . "\n\n\n";

}
else
{
	echo '  <div class="margin_top_10"></div>' . "\n\n";
}

?>

  <div class="bs-example bs-example-tabs">
  	
    <ul id="bsTab" class="nav nav-tabs">
      <li class="active"><a href="#tab_top_<?=$content_id?>">抽選エントリー</a></li>
      <li><a href="#tab_winner_<?=$content_id?>">当選者発表！</a></li>
    </ul>
    
    <div id="myTabContent" class="tab-content margin_top_20">
      
      <div class="tab-pane fade in active" id="tab_top_<?=$content_id?>">

        <h2 id="heading_black">毎週プレゼント！</h2>

        <div class="panel panel-default">
          <div class="panel-body">

            <p>Amazonギフト券プレゼントイベントは、2015年11月15日分で一旦終了します。また再開することがありましたら、ぜひ参加してください。</p>
            
          </div>
        </div>
        
        <div id="present_entry_users">
<?=$code_present_entry_users_1?>
        </div>

        <div class="margin_top_30"  id="present_entry_users">
<?=$code_present_entry_users_2?>
        </div>

      </div>
      
      
      <div class="tab-pane fade" id="tab_winner_<?=$content_id?>">

        <h2 id="heading_black">当選者発表！</h2>

        <div class="panel panel-default">
          <div class="panel-body">

            <p>おめでとうございます。当選ユーザーはこちらの方々です。<br><br>当選された方はログイン後のプレイヤーページの「設定」タグ内で、アマゾンギフト券のコード番号が確認できます。必ず3ヶ月以内に使用してください。それ以降はプレイヤーページにコード番号が表示されなくなります。</p>
            
          </div>
        </div>

<?php if ($administrator): ?>
        <h2 id="heading_orange">抽選！</h2>

        <div class="panel panel-default">
          <div class="panel-body">

            <p>Amazonギフト券を手にするのは誰だ！？</p>
            <input type="text" class="form-control" id="regi_date" placeholder="日曜日の日付を入力" value="<?=$regi_date?>">
            
            <div class="margin_top_20"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="showPresentUserEditForm(this, null, 'lottery', null, null)"><span class="ladda-label">抽選開始</span></button></div>
            
          </div>
        </div>


        <div id="present_user_edit">

        </div>
<?php endif; ?>

        <div id="present_lottery_user_list_winner">
<?=$code_present_winner_users_1?>
<?=$code_present_winner_users_2?>
<?=$code_present_winner_users_3?>
        </div>

      </div>

      
    </div>

<?php

// --------------------------------------------------
//   Adsense
// --------------------------------------------------

if (isset($code_adsense))
{
	echo '<div class="margin_top_20">' . "\n";
	echo $code_adsense;
	echo '</div>' . "\n\n";
}


// --------------------------------------------------
//   アプリストアー　バナー
// --------------------------------------------------

if ( ! $app_mode)
{
	$view_app_store_banner = View::forge('parts/app_store_banner_view');
	echo $view_app_store_banner->render();
}

?>


  </div>

</div>