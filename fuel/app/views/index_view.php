<?php

/*
  必要なデータ

  オプション
*/

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

$view_adsense = \View::forge('common/adsense_rectangle_ver2_view');
$code_adsense_rectangle = $view_adsense->render();


// --------------------------------------------------
//   変数設定
// --------------------------------------------------

$class_main = ( ! AGENT_TYPE) ? 'main_pc' : 'main_s';


// \Debug::dump($group, $content, $feed);

?>

<main class="cd-main-content sub-nav-hero <?=$class_main?>">


<?php if ( ! AGENT_TYPE): // メニュー PC ?>


  <nav class="menu menu_margin_feed">

    <div class="slide">


      <div class="ad">
<?=$code_adsense_rectangle?>
      </div>


      <div class="<?php if ($group !== 'feed') echo 'element_hidden';?>" id="menu_feed">

        <div class="margin_bottom_30">

          <div class="normal border_color_1" id="menu_card" data-page="1" data-group="feed" data-content="index" data-feed="all">
            <div class="left"><i class="material-icons">cloud_queue</i></div>
            <div class="right">すべて</div>
            <div class="selected_icon<?php if ($group === 'feed' and $feed !== 'all') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>


          <div class="normal border_color_2" id="menu_card" data-page="1" data-group="feed" data-content="index" data-feed="bbs">
            <div class="left"><i class="material-icons">forum</i></div>
            <div class="right">交流掲示板</div>
            <div class="selected_icon<?php if ($group !== 'feed' or $feed !== 'bbs') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>

          <div class="normal border_color_3" id="menu_card" data-page="1" data-group="feed" data-content="index" data-feed="recruitment">
            <div class="left"><i class="material-icons">priority_high</i></div>
            <div class="right">募集掲示板</div>
            <div class="selected_icon<?php if ($group !== 'feed' or $feed !== 'recruitment') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>

          <div class="normal border_color_4" id="menu_card" data-page="1" data-group="feed" data-content="index" data-feed="community">
            <div class="left"><i class="material-icons">group</i></div>
            <div class="right">コミュニティ</div>
            <div class="selected_icon<?php if ($group !== 'feed' or $feed !== 'community') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="margin_bottom_40">
          <div class="normal border_color_5" id="menu_card" data-look-ahead="true" data-page="1" data-group="feed" data-content="register_game">
            <div class="left"><i class="material-icons">games</i></div>
            <div class="right">ゲーム登録</div>
            <div class="selected_icon<?php if ($group !== 'feed' or $feed !== 'register_game') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>
        </div>

        <?=$code_amazon_menu_1?>

        <div style="margin: 10px 0 5px 0">
          <a href="https://play.google.com/store/apps/details?id=org.gameusers.gameusers" target="_blank"><img src="<?=URI_BASE?>assets/img/common/google-play-badge.png" width="150" height="45"></a>
        </div>

      </div>



      <div class="<?php if ($group !== 'community') echo 'element_hidden';?>" id="menu_community">

        <div class="margin_bottom_40">
          <div class="normal border_color_6" id="menu_card" data-page="1" data-group="community" data-content="index">
            <div class="left"><i class="material-icons">list</i></div>
            <div class="right">コミュニティ一覧</div>
            <div class="selected_icon<?php if ($group === 'community' and $content !== 'index') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>

          <div class="normal border_color_7" id="menu_card" data-look-ahead="true" data-page="1" data-group="community" data-content="create">
            <div class="left"><i class="material-icons">create</i></div>
            <div class="right">コミュニティ作成</div>
            <div class="selected_icon<?php if ($group !== 'community' or $content !== 'create') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>
        </div>

        <?=$code_amazon_menu_2?>

      </div>



      <div class="<?php if ($group !== 'wiki') echo 'element_hidden';?>" id="menu_wiki">

        <div class="margin_bottom_40">
          <div class="normal border_color_8" id="menu_card" data-page="1" data-group="wiki" data-content="index">
            <div class="left"><i class="material-icons">list</i></div>
            <div class="right">Wiki一覧</div>
            <div class="selected_icon<?php if ($group === 'wiki' and $content !== 'index') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>

          <div class="normal border_color_9" id="menu_card" data-look-ahead="true" data-page="1" data-group="wiki" data-content="create">
            <div class="left"><i class="material-icons">create</i></div>
            <div class="right">Wiki作成</div>
            <div class="selected_icon<?php if ($group !== 'wiki' or $content !== 'create') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>

          <div class="normal border_color_10" id="menu_card" data-look-ahead="true" data-page="1" data-group="wiki" data-content="edit">
            <div class="left"><i class="material-icons">settings</i></div>
            <div class="right">Wiki編集</div>
            <div class="selected_icon<?php if ($group !== 'wiki' or $content !== 'edit') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>
        </div>

        <?=$code_amazon_menu_3?>

      </div>



      <div class="<?php if ($group !== 'help') echo 'element_hidden';?>" id="menu_help" style="margin: 0 0 30px 0;">
<?=$code_help_menu?>
      </div>


    </div>

  </nav>


<?php else: // メニュー スマホ・タブレット版 ?>


  <nav id="slideMenu" class="slideMenu">

    <div class="title">Menu</div>


    <div class="<?php if ($group !== 'feed') echo 'element_hidden';?>" id="menu_feed">

      <div class="box" id="menu_card" data-page="1" data-group="feed" data-content="index" data-feed="all">
        <div class="left"><i class="material-icons">cloud_queue</i></div>
        <div class="right"><span class="<?php if (($group === 'feed' and $feed === 'all') or $group !== 'feed') echo 'selected';?>">すべて</span></div>
      </div>

      <div class="box" id="menu_card" data-page="1" data-group="feed" data-content="index" data-feed="bbs">
        <div class="left"><i class="material-icons">forum</i></div>
        <div class="right"><span class="<?php if ($group === 'feed' and $feed === 'bbs') echo 'selected';?>">交流掲示板</span></div>
      </div>

      <div class="box" id="menu_card" data-page="1" data-group="feed" data-content="index" data-feed="recruitment">
        <div class="left"><i class="material-icons">priority_high</i></div>
        <div class="right"><span class="<?php if ($group === 'feed' and $feed === 'recruitment') echo 'selected';?>">募集掲示板</span></div>
      </div>

      <div class="box" id="menu_card" data-page="1" data-group="feed" data-content="index" data-feed="community">
        <div class="left"><i class="material-icons">group</i></div>
        <div class="right"><span class="<?php if ($group === 'feed' and $feed === 'community') echo 'selected';?>">コミュニティ</span></div>
      </div>

      <div class="box" style="margin-top: 30px;" id="menu_card" data-look-ahead="true" data-page="1" data-group="feed" data-content="register_game">
        <div class="left"><i class="material-icons">games</i></div>
        <div class="right"><span class="<?php if ($group === 'feed' and $feed === 'register_game') echo 'selected';?>">ゲーム登録</span></div>
      </div>

    </div>


    <div class="<?php if ($group !== 'community') echo 'element_hidden';?>" id="menu_community">

      <div class="box" id="menu_card" data-page="1" data-group="community" data-content="index">
        <div class="left"><i class="material-icons">list</i></div>
        <div class="right"><span class="<?php if (($group === 'community' and $content === 'index') or $group !== 'community')  echo 'selected';?>">コミュニティ一覧<span></div>
      </div>

      <div class="box" id="menu_card" data-look-ahead="true" data-page="1" data-group="community" data-content="create">
        <div class="left"><i class="material-icons">create</i></div>
        <div class="right"><span class="<?php if ($group === 'community' and $content === 'create') echo 'selected';?>">コミュニティ作成<span></div>
      </div>

    </div>


    <div class="<?php if ($group !== 'wiki') echo 'element_hidden';?>" id="menu_wiki">

      <div class="box" id="menu_card" data-page="1" data-group="wiki" data-content="index">
        <div class="left"><i class="material-icons">list</i></div>
        <div class="right"><span class="<?php if (($group === 'wiki' and $content === 'index') or $group !== 'wiki') echo 'selected';?>">Wiki一覧<span></div>
      </div>

      <div class="box" id="menu_card" data-look-ahead="true" data-page="1" data-group="wiki" data-content="create">
        <div class="left"><i class="material-icons">create</i></div>
        <div class="right"><span class="<?php if ($group === 'wiki' and $content === 'create') echo 'selected';?>">Wiki作成<span></div>
      </div>

      <div class="box" id="menu_card" data-look-ahead="true" data-page="1" data-group="wiki" data-content="edit">
        <div class="left"><i class="material-icons">settings</i></div>
        <div class="right"><span class="<?php if ($group === 'wiki' and $content === 'edit') echo 'selected';?>">Wiki編集<span></div>
      </div>

    </div>


    <div class="<?php if ($group !== 'help') echo 'element_hidden';?>" id="menu_help" style="padding: 0 10px 0 10px;">
<?=$code_help_menu?>
    </div>


  </nav>


<?php endif; ?>


<?php if ( ! AGENT_TYPE): // コンテンツ PC版 ?>


  <article class="content<?php if ($group !== 'feed' or $feed === 'register_game') echo ' element_hidden';?>" style="margin: 0 0 25px 0" id="content_feed_index">

    <div class="feed_card_box" id="feed_card_box">
<?=$code_feed?>
    </div>

    <div style="margin: 14px 0 0 5px" id="feed_pagination">
<?=$code_feed_pagination?>
    </div>

    <div style="margin: 30px 0 0 0;">
<?=$code_social?>
    </div>

  </article>

  <div class="content<?php if ($group !== 'feed' or $feed !== 'register_game') echo ' element_hidden';?>" style="margin: 0 20px 10px 5px" id="content_feed_register_game">
<?=$code_register_game?>
  </div>


  <article class="content<?php if ($group !== 'community' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_community_index">
    <div class="feed_card_box" id="community_list">
<?=$code_community_index?>
    </div>
  </article>

  <div class="content<?php if ($group !== 'community' or $content !== 'create') echo ' element_hidden';?>" style="margin: 0 20px 10px 5px" id="content_community_create">
    <?=$code_community_create?>
  </div>


  <article class="content<?php if ($group !== 'wiki' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_wiki_index">
    <div class="feed_card_box" id="wiki_list">
<?=$code_wiki_list?>
    </div>
  </article>

  <div class="content<?php if ($group !== 'wiki' or $content !== 'create') echo ' element_hidden';?>" style="margin: 0 20px 10px 5px" id="content_wiki_create">
    <?=$code_wiki_create?>
  </div>

  <div class="content<?php if ($group !== 'wiki' or $content !== 'edit') echo ' element_hidden';?>" style="margin: 0 20px 10px 5px" id="content_wiki_edit">
    <?=$code_wiki_edit?>
  </div>


  <div class="content<?php if ($group !== 'help' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 20px 10px 0" id="content_help_index">
    <div class="feed_card_box" id="help"><?=$code_help?></div>
  </div>


<?php else: // コンテンツ スマホ・タブレット版  ?>


  <div class="wrapper_s">


    <article class="content_s<?php if ($group !== 'feed' or $feed === 'register_game') echo ' element_hidden';?>" style="margin: 0 0 15px 0" id="content_feed_index">

      <div class="feed_card_box" id="feed_card_box">
  <?=$code_feed?>
      </div>

      <div style="margin: 10px 10px 0 5px;" id="feed_pagination">
    <?=$code_feed_pagination?>
      </div>

    </article>

    <div class="content_s<?php if ($group !== 'feed' or $feed !== 'register_game') echo ' element_hidden';?>" style="margin: 0 0 10px 5px" id="content_feed_register_game">
<?=$code_register_game?>
    </div>


    <article class="content_s<?php if ($group !== 'community' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_community_index">
      <div class="feed_card_box" id="community_list">
<?=$code_community_index?>
      </div>
    </article>

    <div class="content_s<?php if ($group !== 'community' or $content !== 'create') echo ' element_hidden';?>" style="margin: 0 0 10px 5px" id="content_community_create">
      <?=$code_community_create?>
    </div>


    <article class="content_s<?php if ($group !== 'wiki' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_wiki_index">
      <div class="feed_card_box" id="wiki_list">
<?=$code_wiki_list?>
      </div>
    </article>

    <div class="content_s<?php if ($group !== 'wiki' or $content !== 'create') echo ' element_hidden';?>" style="margin: 0 0 10px 5px" id="content_wiki_create">
      <?=$code_wiki_create?>
    </div>

    <div class="content_s<?php if ($group !== 'wiki' or $content !== 'edit') echo ' element_hidden';?>" style="margin: 0 0 10px 5px" id="content_wiki_edit">
      <?=$code_wiki_edit?>
    </div>


    <div class="content_s<?php if ($group !== 'help') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_help_index">
      <div class="feed_card_box" id="help">
<?=$code_help?>
      </div>
    </div>


    <div class="menu_s" style="margin: 0 0 15px 0; padding: 0 0 9px 0;">

      <div class="slide">

        <div class="icon_box" id="icon_box">
          <div class="icon" id="menu_to_top"><span class="glyphicon glyphicon-triangle-top icon_arrow" aria-hidden="true"></span></div>
          <div class="icon" id="menuTrigger"><span class="glyphicon glyphicon-list-alt icon_menu" aria-hidden="true"></span></div>
          <div class="icon" id="menu_to_bottom"><span class="glyphicon glyphicon-triangle-bottom icon_arrow" aria-hidden="true"></span></div>
        </div>

      </div>

    </div>

  </div>

  <div style="margin: 10px 5px 20px 0;">
<?=$code_social?>
  </div>

<?php endif; ?>

</main>
