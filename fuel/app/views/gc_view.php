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


//\Debug::dump($group, $content, $code_help_menu);

?>

<main class="cd-main-content sub-nav-hero <?=$class_main?>">


<?php if ( ! AGENT_TYPE): // メニュー PC ?>


  <nav class="menu menu_margin_feed">

    <div class="slide">

      <div class="ad">
<?=$code_adsense_rectangle?>
      </div>

      <div class="<?php if ($group !== 'bbs') echo 'element_hidden';?>" id="menu_bbs">

        <div class="margin_bottom_20">
          <button type="submit" class="btn btn-default ladda-button left_menu_button" data-style="expand-right" data-spinner-color="#000000" onclick="modalReadFormBbsCreateThread(this, <?=$game_no?>, 'gc')" style="width: 100%"><span class="glyphicon glyphicon-pencil"> <span class="ladda-label">スレッド作成</span></button>
        </div>

<?php if ($code_bbs_thread_list): ?>
<?=$code_bbs_thread_list?>
<?php else: ?>
        <div id="bbs_thread_list_box"></div>
<?php endif; ?>

      </div>


      <div class="<?php if ($group !== 'rec') echo 'element_hidden';?>" id="menu_rec">
<?=$code_recruitment_menu?>
      </div>


      <div class="<?php if ($group !== 'config') echo 'element_hidden';?>" id="menu_config">
        <div class="margin_bottom_40">
          <div class="normal border_color_8" id="menu_card" data-page="1" data-group="config" data-content="index">
            <div class="left"><i class="material-icons">settings</i></div>
            <div class="right">設定</div>
            <div class="selected_icon<?php if ($group === 'config' and $content !== 'index') echo ' element_hidden';?>">
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
      </div>


      <div class="<?php if ($group !== 'help') echo 'element_hidden';?>" id="menu_help" style="margin: 0 0 30px 0;">
<?=$code_help_menu?>
      </div>


    </div>

  </nav>


<?php else: // メニュー スマホ・タブレット版 ?>


  <nav id="slideMenu" class="slideMenu">

    <div class="title">Menu</div>

    <div class="<?php if ($group !== 'bbs') echo 'element_hidden';?>" id="menu_bbs" style="padding: 0 10px 0 10px;">

      <div class="margin_bottom_20">
        <button type="submit" class="btn btn-default ladda-button left_menu_button" data-style="expand-right" data-spinner-color="#000000" onclick="modalReadFormBbsCreateThread(this, <?=$game_no?>, 'gc')" style="width: 100%"><span class="glyphicon glyphicon-pencil"> <span class="ladda-label">スレッド作成</span></button>
      </div>

<?php if ($code_bbs_thread_list): ?>
<?=$code_bbs_thread_list?>
<?php else: ?>
      <div id="bbs_thread_list_box"></div>
<?php endif; ?>

    </div>


    <div class="<?php if ($group !== 'rec') echo 'element_hidden';?>" id="menu_rec" style="padding: 0 10px 0 10px;">
<?=$code_recruitment_menu?>
    </div>


    <div class="<?php if ($group !== 'config') echo 'element_hidden';?>" id="menu_config">
      <div class="box" id="menu_card" data-page="1" data-group="config" data-content="index">
        <div class="left"><i class="material-icons">settings</i></div>
        <div class="right"><span class="<?php if (($group === 'config' and $content === 'index') or $group !== 'config')  echo 'selected';?>">設定<span></div>
      </div>
    </div>


    <div class="<?php if ($group !== 'help') echo 'element_hidden';?>" id="menu_help" style="padding: 0 10px 0 10px;">
<?=$code_help_menu?>
    </div>


  </nav>

<?php endif; ?>


<?php if ( ! AGENT_TYPE): // コンテンツ PC版 ?>


  <div class="content<?php if ($group !== 'bbs' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_bbs_index">

<?=$code_bbs?>

    <div style="margin: 30px 0 0 0;">
<?=$code_social?>
    </div>

  </div>


  <div class="content<?php if ($group !== 'rec' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_rec_index">
<?=$code_recruitment?>
  </div>


  <div class="content<?php if ($group !== 'config' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 20px 10px 5px" id="content_config_index">
<?=$code_config?>
  </div>


  <div class="content<?php if ($group !== 'help' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 20px 10px 0" id="content_help_index">
    <div class="feed_card_box" id="help"><?=$code_help?></div>
  </div>


<?php else: // コンテンツ スマホ・タブレット版  ?>


  <div class="wrapper_s">

    <article class="content_s<?php if ($group !== 'bbs') echo ' element_hidden';?>" style="margin: 0 0 0 5px" id="content_bbs_index">
<?=$code_bbs?>
    </article>


    <article class="content_s<?php if ($group !== 'rec') echo ' element_hidden';?>" style="margin: 0 0 20px 5px" id="content_rec_index">
<?=$code_recruitment?>
    </article>


    <div class="content_s<?php if ($group !== 'config') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_config_index">
<?=$code_config?>
    </div>


    <div class="content_s<?php if ($group !== 'help') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_help_index">
      <div class="feed_card_box" id="help"><?=$code_help?></div>
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

<aside id="modal_box"></aside>
