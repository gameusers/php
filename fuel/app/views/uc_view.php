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


$announcement_existence = (isset($code_announcement)) ? 'true' : 'false';
// \Debug::dump($authority_arr);

?>

<main class="cd-main-content sub-nav-hero <?=$class_main?>">


<?php if ( ! AGENT_TYPE): // メニュー PC ?>


  <nav class="menu menu_margin_feed">

    <div class="slide">


      <div class="ad">
<?=$code_adsense_rectangle?>
      </div>


      <div class="<?php if ($group !== 'bbs') echo 'element_hidden';?>" id="menu_bbs">

<?php if ($authority_arr['operate_announcement']): ?>
        <div class="margin_bottom_20">
          <button type="submit" class="btn btn-default ladda-button left_menu_button" data-style="expand-right" data-spinner-color="#000000" id="submit_create_announcement" data-existence="<?=$announcement_existence?>" onclick="GAMEUSERS.uc.modalReadFormAnnouncement(this, <?=$community_no?>, null)" style="width: 100%"><span class="glyphicon glyphicon-pencil"> <span class="ladda-label">告知を作成する</span></button>
        </div>
<?php endif; ?>

<?php if ($authority_arr['operate_bbs_thread']): ?>
        <div class="margin_bottom_20">
          <button type="submit" class="btn btn-default ladda-button left_menu_button" data-style="expand-right" data-spinner-color="#000000" onclick="modalReadFormBbsCreateThread(this, <?=$community_no?>, 'uc')" style="width: 100%"><span class="glyphicon glyphicon-pencil"> <span class="ladda-label">スレッド作成</span></button>
        </div>
<?php endif; ?>

<?php if ($authority_arr['read_bbs'] and $code_bbs_thread_list): ?>
<?=$code_bbs_thread_list?>
<?php else: ?>
        <div id="bbs_thread_list_box"></div>
<?php endif; ?>

      </div>


<?php if ($authority_arr['read_member']): ?>
      <div class="<?php if ($group !== 'member') echo 'element_hidden';?>"  id="menu_member">

        <div class="margin_bottom_30">

          <div class="normal border_color_1" data-page="1" data-group="member" data-content="index">
            <div class="left"><i class="material-icons">group</i></div>
            <div class="right">メンバー</div>
            <div class="selected_icon">
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
<?php endif; ?>


      <div class="<?php if ($group !== 'data') echo 'element_hidden';?>" id="menu_data">

        <div class="margin_bottom_30">

          <div class="normal border_color_2" data-page="1" data-group="data" data-content="index">
            <div class="left"><i class="material-icons">info_outline</i></div>
            <div class="right">コミュニティについて</div>
            <div class="selected_icon">
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


<?php if ($authority_arr['operate_send_all_mail']): ?>
      <div class="<?php if ($group !== 'notification') echo 'element_hidden';?>" id="menu_notification">

        <div class="margin_bottom_30">

          <div class="normal border_color_3" data-page="1" data-group="notification" data-content="index">
            <div class="left"><i class="material-icons">settings_remote</i></div>
            <div class="right">通知</div>
            <div class="selected_icon">
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
<?php endif; ?>


<?php if ($authority_arr['member']): ?>
      <div class="<?php if ($group !== 'config') echo 'element_hidden';?>" id="menu_config">

        <div class="margin_bottom_40">

          <div class="normal border_color_4" id="menu_card" data-page="1" data-group="config" data-content="index">
            <div class="left"><i class="material-icons">settings</i></div>
            <div class="right">プロフィール設定</div>
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

          <div class="normal border_color_5" id="menu_card" data-page="1" data-group="config" data-content="notification">
            <div class="left"><i class="material-icons">settings</i></div>
            <div class="right">通知設定</div>
            <div class="selected_icon<?php if ($group !== 'config' or $content !== 'notification') echo ' element_hidden';?>">
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


<?php if ($authority_arr['operate_config_community']): ?>
        <div>

          <div class="normal border_color_6" id="menu_card" data-page="1" data-group="config" data-content="basic">
            <div class="left"><i class="material-icons">settings</i></div>
            <div class="right">コミュニティ基本設定</div>
            <div class="selected_icon<?php if ($group !== 'config' or $content !== 'basic') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>

          <div class="normal border_color_7" id="menu_card" data-page="1" data-group="config" data-content="community">
            <div class="left"><i class="material-icons">settings</i></div>
            <div class="right">コミュニティ追加設定</div>
            <div class="selected_icon<?php if ($group !== 'config' or $content !== 'community') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>
<?php endif; ?>

<?php if ($authority_arr['administrator']): ?>
          <div class="normal border_color_8" id="menu_card" data-page="1" data-group="config" data-content="authority_read">
            <div class="left"><i class="material-icons">settings</i></div>
            <div class="right">コミュニティ閲覧権限</div>
            <div class="selected_icon<?php if ($group !== 'config' or $content !== 'authority_read') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>

          <div class="normal border_color_9" id="menu_card" data-page="1" data-group="config" data-content="authority_operate">
            <div class="left"><i class="material-icons">settings</i></div>
            <div class="right">コミュニティ操作権限</div>
            <div class="selected_icon<?php if ($group !== 'config' or $content !== 'authority_operate') echo ' element_hidden';?>">
              <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
              </div>
            </div>
          </div>

          <div class="normal border_color_10" id="menu_card" data-page="1" data-group="config" data-content="delete">
            <div class="left"><i class="material-icons">settings</i></div>
            <div class="right">コミュニティ削除</div>
            <div class="selected_icon<?php if ($group !== 'config' or $content !== 'delete') echo ' element_hidden';?>">
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
<?php endif; ?>

      </div>
<?php endif; ?>


      <div class="<?php if ($group !== 'help') echo 'element_hidden';?>" id="menu_help" style="margin: 0 0 30px 0;">
<?=$code_help_menu?>
      </div>


    </div>

  </nav>


<?php else: // メニュー スマホ・タブレット版 ?>


  <nav id="slideMenu" class="slideMenu">

    <div class="title">Menu</div>

    <div class="<?php if ($group !== 'bbs') echo 'element_hidden';?>" id="menu_bbs" style="padding: 0 10px 0 10px;">

<?php if ($authority_arr['operate_announcement']): ?>
      <div class="margin_bottom_20">
        <button type="submit" class="btn btn-default ladda-button left_menu_button" data-style="expand-right" data-spinner-color="#000000" id="submit_create_announcement" data-existence="<?=$announcement_existence?>" onclick="GAMEUSERS.uc.modalReadFormAnnouncement(this, <?=$community_no?>, null)" style="width: 100%"><span class="glyphicon glyphicon-pencil"> <span class="ladda-label">告知を作成する</span></button>
      </div>
<?php endif; ?>

<?php if ($authority_arr['operate_bbs_thread']): ?>
      <div class="margin_bottom_20">
        <button type="submit" class="btn btn-default ladda-button left_menu_button" data-style="expand-right" data-spinner-color="#000000" onclick="modalReadFormBbsCreateThread(this, <?=$community_no?>, 'uc')" style="width: 100%"><span class="glyphicon glyphicon-pencil"> <span class="ladda-label">スレッド作成</span></button>
      </div>
<?php endif; ?>

<?php if ($authority_arr['read_bbs'] and $code_bbs_thread_list): ?>
<?=$code_bbs_thread_list?>
<?php else: ?>
      <div id="bbs_thread_list_box"></div>
<?php endif; ?>

    </div>


<?php if ($authority_arr['read_member']): ?>
    <div class="<?php if ($group !== 'member') echo 'element_hidden';?>" id="menu_member" style="padding: 0 10px 0 10px;">
      <div class="box" id="menu_card" data-page="1" data-group="member" data-content="index">
        <div class="left"><i class="material-icons">group</i></div>
        <div class="right"><span class="selected">メンバー</span></div>
      </div>
    </div>
<?php endif; ?>


    <div class="<?php if ($group !== 'data') echo 'element_hidden';?>" id="menu_data" style="padding: 0 10px 0 10px;">
      <div class="box" id="menu_card" data-page="1" data-group="data" data-content="index">
        <div class="left"><i class="material-icons">info_outline</i></div>
        <div class="right"><span class="selected">データ</span></div>
      </div>
    </div>


<?php if ($authority_arr['operate_send_all_mail']): ?>
    <div class="<?php if ($group !== 'notification') echo 'element_hidden';?>" id="menu_notification">
      <div class="box" id="menu_card" data-page="1" data-group="notification" data-content="index">
        <div class="left"><i class="material-icons">settings_remote</i></div>
        <div class="right"><span class="selected">通知</span></div>
      </div>
    </div>
<?php endif; ?>


<?php if ($authority_arr['member']): ?>
    <div class="<?php if ($group !== 'config') echo 'element_hidden';?>" id="menu_config">

      <div class="box" id="menu_card" data-page="1" data-group="config" data-content="index">
        <div class="left"><i class="material-icons">settings</i></div>
        <div class="right"><span class="<?php if (($group === 'config' and $content === 'index') or $group !== 'config') echo 'selected';?>">プロフィール設定</span></div>
      </div>

      <div class="box" id="menu_card" data-page="1" data-group="config" data-content="notification">
        <div class="left"><i class="material-icons">settings</i></div>
        <div class="right"><span class="<?php if ($group === 'config' and $content === 'notification') echo 'selected';?>">通知設定</span></div>
      </div>

<?php if ($authority_arr['operate_config_community']): ?>
      <div class="box" id="menu_card" data-page="1" data-group="config" data-content="basic">
        <div class="left"><i class="material-icons">settings</i></div>
        <div class="right"><span class="<?php if ($group === 'config' and $content === 'basic') echo 'selected';?>">コミュニティ基本設定</span></div>
      </div>

      <div class="box" id="menu_card" data-page="1" data-group="config" data-content="community">
        <div class="left"><i class="material-icons">settings</i></div>
        <div class="right"><span class="<?php if ($group === 'config' and $content === 'community') echo 'selected';?>">コミュニティ追加設定</span></div>
      </div>
<?php endif; ?>

<?php if ($authority_arr['administrator']): ?>
      <div class="box" id="menu_card" data-page="1" data-group="config" data-content="authority_read">
        <div class="left"><i class="material-icons">settings</i></div>
        <div class="right"><span class="<?php if ($group === 'config' and $content === 'authority_read') echo 'selected';?>">コミュニティ閲覧権限</span></div>
      </div>

      <div class="box" id="menu_card" data-page="1" data-group="config" data-content="authority_operate">
        <div class="left"><i class="material-icons">settings</i></div>
        <div class="right"><span class="<?php if ($group === 'config' and $content === 'authority_operate') echo 'selected';?>">コミュニティ操作権限</span></div>
      </div>

      <div class="box" id="menu_card" data-page="1" data-group="config" data-content="delete">
        <div class="left"><i class="material-icons">settings</i></div>
        <div class="right"><span class="<?php if ($group === 'config' and $content === 'delete') echo 'selected';?>">コミュニティ削除</span></div>
      </div>
<?php endif; ?>

    </div>
<?php endif; ?>


    <div class="<?php if ($group !== 'help') echo 'element_hidden';?>" id="menu_help" style="padding: 0 10px 0 10px;">
<?=$code_help_menu?>
    </div>


  </nav>


<?php endif; ?>


<?php if ( ! AGENT_TYPE): // コンテンツ PC版 ?>


  <article class="content<?php if ($group !== 'bbs') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_bbs_index">

<?=$code_announcement?>
<?=$code_bbs?>

    <div style="margin: 30px 0 0 0;">
<?=$code_social?>
    </div>

  </article>


<?php if ($authority_arr['read_member']): ?>
  <article class="content<?php if ($group !== 'member') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_member_index">
<?=$code_member?>
  </article>
<?php endif; ?>


  <article class="content<?php if ($group !== 'data') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_data_index">
<?=$code_data?>
  </article>


<?php if ($authority_arr['operate_send_all_mail']): ?>
  <div class="content<?php if ($group !== 'notification') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_notification_index">
<?=$code_notification?>
  </div>
<?php endif; ?>


<?php if ($authority_arr['member']): ?>
  <div class="content<?php if ($group !== 'config' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_config_index">
<?=$code_config_profile?>
  </div>


  <div class="content<?php if ($group !== 'config' or $content !== 'notification') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_config_notification">
<?=$code_config_nofitication?>
  </div>


<?php if ($authority_arr['operate_config_community']): ?>
  <div class="content<?php if ($group !== 'config' or $content !== 'basic') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_config_basic">
<?=$code_config_basic?>
  </div>


  <div class="content<?php if ($group !== 'config' or $content !== 'community') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_config_community">
<?=$code_config_community?>
  </div>


  <div class="content<?php if ($group !== 'config' or $content !== 'authority_read') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_config_authority_read">
<?=$code_config_authority_read?>
  </div>


  <div class="content<?php if ($group !== 'config' or $content !== 'authority_operate') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_config_authority_operate">
<?=$code_config_authority_operate?>
  </div>


  <div class="content<?php if ($group !== 'config' or $content !== 'delete') echo ' element_hidden';?>" style="margin: 0 20px 25px 5px" id="content_config_delete">
<?=$code_config_delete?>
  </div>
<?php endif; ?>
<?php endif; ?>

  <div class="content<?php if ($group !== 'help') echo ' element_hidden';?>" style="margin: 0 20px 10px 0" id="content_help_index">
    <div class="feed_card_box" id="help"><?=$code_help?></div>
  </div>


<?php else: // コンテンツ スマホ・タブレット版  ?>


  <div class="wrapper_s">

    <article class="content_s<?php if ($group !== 'bbs') echo ' element_hidden';?>" style="margin: 0 0 0 5px" id="content_bbs_index">

<?=$code_announcement?>
<?=$code_bbs?>

    </article>


<?php if ($authority_arr['read_member']): ?>
    <article class="content_s<?php if ($group !== 'member') echo ' element_hidden';?>" style="margin: 0 0 20px 5px" id="content_member_index">
<?=$code_member?>
    </article>
<?php endif; ?>


    <article class="content_s<?php if ($group !== 'data') echo ' element_hidden';?>" style="margin: 0 0 20px 5px" id="content_data_index">
<?=$code_data?>
    </article>


<?php if ($authority_arr['operate_send_all_mail']): ?>
    <div class="content_s<?php if ($group !== 'notification') echo ' element_hidden';?>" style="margin: 0 0 20px 5px" id="content_notification_index">
<?=$code_notification?>
    </div>
<?php endif; ?>


<?php if ($authority_arr['member']): ?>
    <div class="content_s<?php if ($group !== 'config' or $content !== 'index') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_config_index">
<?=$code_config_profile?>
    </div>


    <div class="content_s<?php if ($group !== 'config' or $content !== 'notification') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_config_notification">
<?=$code_config_nofitication?>
    </div>


<?php if ($authority_arr['operate_config_community']): ?>
    <div class="content_s<?php if ($group !== 'config' or $content !== 'basic') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_config_basic">
<?=$code_config_basic?>
    </div>


    <div class="content_s<?php if ($group !== 'config' or $content !== 'community') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_config_community">
<?=$code_config_community?>
    </div>


    <div class="content_s<?php if ($group !== 'config' or $content !== 'authority_read') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_config_authority_read">
<?=$code_config_authority_read?>
    </div>


    <div class="content_s<?php if ($group !== 'config' or $content !== 'authority_operate') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_config_authority_operate">
<?=$code_config_authority_operate?>
    </div>


    <div class="content_s<?php if ($group !== 'config' or $content !== 'delete') echo ' element_hidden';?>" style="margin: 0 0 10px 0" id="content_config_delete">
<?=$code_config_delete?>
    </div>
<?php endif; ?>
<?php endif; ?>


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
