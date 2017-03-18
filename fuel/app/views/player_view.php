<?php

/*
 * 必要なデータ
 * string / content_id / コンテンツID
 * integer / login_user_no / ログインユーザーNo
 * integer / page_user_no / このページ所有者のユーザーNo
 * string / uri_base /
 * string / renewal_date / 更新日
 * array / top_image_arr / トップの画像
 *
 * コード
 * code / main_profile / メインプロフィール
 * code / profiles / プロフィール
 * code / code_pagination_profile / プロフィールページネーション
 * code / code_participation_community / 参加コミュニティ
 * code / code_participation_community_secret / 参加コミュニティ非公開
 *
 * オプション
 * boolean / $app_mode / アプリの場合
 * string / explanation / 説明文
 */

?>

<div class="content_box">

<?php

// --------------------------------------------------
//   Adsense
// --------------------------------------------------

// if (isset($code_adsense))
// {
// 	echo '<div class="margin_top_10 margin_bottom_20">' . "\n";
// 	echo $code_adsense;
// 	echo '</div>' . "\n\n";
// }

?>

<?php

// --------------------------------------------------
//   トップ画像
// --------------------------------------------------

if ($top_image_arr)
{

	if ($agent_type == 'smartphone')
	{
		echo '  <div class="c-wrapper_sp">' . "\n\n";
	}
	else
	{
		echo '  <div class="c-wrapper">' . "\n\n";
	}

	echo '    <div id="carousel" class="carousel slide" data-ride="carousel" data-interval="false">' . "\n\n";


	// --------------------------------------------------
	//   下の●○○
	// --------------------------------------------------

	$top_image_arr_count = count($top_image_arr);

	if ($top_image_arr_count != 1)
	{
		echo '      <ol class="carousel-indicators">' . "\n";
		echo '        <li data-target="#carousel" data-slide-to="0" class="active"></li>' . "\n";
		echo '        <li data-target="#carousel" data-slide-to="1"></li>' . "\n";
		if ($top_image_arr_count == 3) echo '        <li data-target="#carousel" data-slide-to="2"></li>' . "\n";
		echo '      </ol>' . "\n\n";
	}


	//var_dump($top_image_arr);
	// --------------------------------------------------
	//   画像
	// --------------------------------------------------

	echo '      <div class="carousel-inner">' . "\n\n";

	$repeat_count = 1;

	foreach ($top_image_arr as $key => $value) {

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

			if ($key == 'none')
			{
				echo '          <img src="' . $value['url'] . '" width="' . $value['width'] . '" height="' . $value['height'] . '">' . "\n";
			}
			else
			{
				echo '          <img src="' . $uri_base . 'assets/img/user/' . $page_user_no . '/' . $key . '.jpg?' . strtotime($renewal_date) . '" width="' . $value['width'] . '" height="' . $value['height'] . '">' . "\n";
			}

			echo '        </div>' . "\n\n";
		}

		$repeat_count++;

	}

	echo '      </div>' . "\n\n";


	// --------------------------------------------------
	//   次の画像、前の画像　カーソル
	// --------------------------------------------------

	if ($top_image_arr_count > 1)
	{
		echo '      <a class="left carousel-control" href="#carousel" data-slide="prev">' . "\n";
		echo '        <span class="glyphicon glyphicon-chevron-left"></span>' . "\n";
		echo '      </a>' . "\n";
		echo '      <a class="right carousel-control" href="#carousel" data-slide="next">' . "\n";
		echo '        <span class="glyphicon glyphicon-chevron-right"></span>' . "\n";
		echo '      </a>' . "\n\n";
	}

	echo '    </div>' . "\n\n";

	echo '  </div>' . "\n\n\n";

}
else
{
	echo '<div class="gameusers_top_image"><img src="' . $uri_base . 'assets/img/index/image_2.png" /></div>';
}

?>


<?=$code_social?>


    <ul id="bsTab" class="nav nav-tabs bsTab">
      <li class="active"><a href="#tab_top" id="bs_tab_a">トップ</a></li>
      <li><a href="#tab_participation_community" id="bs_tab_a">参加コミュニティ</a></li>
<?php if ($check_author) : ?>
      <li role="presentation" class="dropdown">

        <a href="#" class="dropdown-toggle" data-toggle="dropdown">設定 <span class="caret"></span></a>

        <ul class="dropdown-menu" id="myTabDrop1-contents">
          <li class=""><a href="#tab_config" role="tab" id="bs_tab_a" data-toggle="tab">基本設定</a></li>
          <li class=""><a href="#tab_config_notification" role="tab" id="bs_tab_a" data-toggle="tab">通知設定</a></li>
          <li class=""><a href="#tab_config_advertisement" role="tab" id="bs_tab_a" data-toggle="tab">広告設定</a></li>
        </ul>

      </li>
<?php endif; ?>
      <li><a href="#tab_help" id="bs_tab_a"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></li>
    </ul>


    <div id="myTabContent" class="tab-content padding_top_20 padding_bottom_20">

      <div class="tab-pane fade in active" id="tab_top">


<?php
if (isset($first_access) and $check_author)
{
	echo '<div class="alert alert-info" role="alert">まずはプレイヤープロフィール内の「編集ボタン」を押して、最も重要なプレイヤープロフィールを編集しましょう！<br>※ ページ下部にある「プロフィールを追加するボタン」を押すと、ゲームごとにプロフィールを追加することもできます。<br><br>その次は「設定タブ」を押して、このページのタイトルやプレイヤーID（このページのURLになります）を設定しましょう。</div>';
}
?>
<?=$main_profile?>


        <div id="profile_box">

<?=$profiles?>
        </div>

<?php

if (isset($code_pagination_profile))
{
	echo '        <div id="profile_pagination">' . "\n";
	echo $code_pagination_profile;
	echo '        </div>' . "\n";
}

if ($check_author)
{
	echo '        <div class="margin_top_30" id="add_profile_form"></div>' . "\n\n";
	echo '        <div class="add_profile"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_show_edit_profile_form_add" onclick="GAMEUSERS.player.showEditProfileForm(this, null, null)"><span class="ladda-label">プロフィールを追加する</span></button></div>' . "\n\n";
}

?>

      </div>



      <div class="tab-pane fade" id="tab_participation_community" data-type="open">

        <div class="margin_bottom_20">

<?php if ($check_author): ?>
      	   <div class="btn-group margin_bottom_20" id="read_participation_button">
             <button type="button" class="btn btn-default ladda-button active" id="read_participation_community_open" data-style="expand-right" data-spinner-color="#000000" data-page="1" onclick="GAMEUSERS.player.readParticipationCommunity(this, null, <?=$page_user_no?>)">公開</button>
             <button type="button" class="btn btn-default ladda-button" id="read_participation_community_close" data-style="expand-right" data-spinner-color="#000000" data-page="1" onclick="GAMEUSERS.player.readParticipationCommunity(this, null, <?=$page_user_no?>)">非公開</button>
          </div>
<?php endif; ?>

<article id="participation_community_box">
<?=$code_participation_community?>
</article>

<article id="participation_community_secret_box">
<?=$code_participation_community_secret?>
</article>

        </div>

     </div>



<?php if ($check_author): ?>

<div class="tab-pane fade" id="tab_config"></div>

<div class="tab-pane fade" id="tab_config_notification"></div>

<div class="tab-pane fade" id="tab_config_advertisement"></div>

<div class="tab-pane fade" id="tab_wiki">

<?=$code_wiki_tab?>

</div>

<?php endif; ?>


      <div class="tab-pane fade" id="tab_help"></div>


    </div>



		<div class="margin_top_30">
		<?=$code_ad_amazon_slide?>
		</div>


<?php

// --------------------------------------------------
//   Adsense
// --------------------------------------------------

		if (isset($code_adsense_rectangle))
		{
			echo '<div class="margin_top_20">' . "\n";
			echo $code_adsense_rectangle;
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
