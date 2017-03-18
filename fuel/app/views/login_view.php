<?php

/*
 * 必要なデータ
 * string / $content_id / コンテンツID
 * string / $language / 言語
 * string / $uri_base
 * string / $code_alert / アラートコード
 * 
 * オプション
 * boolean / $app_mode / アプリの場合
 */

// --------------------------------------------------
//   インスタンス作成
// --------------------------------------------------

//$original_code_basic = new Original\Code\Basic();
//if (isset($app_mode)) $original_code_basic->app_mode = $app_mode;
// $redirect_type = Session::get('redirect_type');
		// $redirect_id = Session::get('redirect_id');
// 		
		// var_dump($redirect_type, $redirect_id);

?>

    <div class="container content_box" id="<?=$content_id?>">

<?=$code_alert?>

      	<div class="btn-group login_change_category_button">
      	  <button type="button" class="btn btn-default" id="change_category_login1_button"><?php echo __('login_view_category_title_1'); ?></button>
      	  <button type="button" class="btn btn-default" id="change_category_login2_button"><?php echo __('login_view_category_title_2'); ?></button>
      	  <?php if( ! APP_MODE) : ?><button type="button" class="btn btn-default" id="change_category_registration_button"><?php echo __('login_view_category_title_3'); ?></button><?php endif; ?>
      	</div>


        <div id="login1">
        
      	<h3><?php echo __('login_view_category_title_1'); ?></h3>
        <p class="explanation"><?php echo __('login_view_category_explanation_1'); ?></p>

<?php if(isset($code_user_terms_1)) : ?>
        <div class="margin_bottom_30">
<?=$code_user_terms_1?>
        </div>
<?php endif; ?>

<?php if ($language == 'ja'): ?>
        
        <a href="<?=$uri_base?>login/auth/twitter" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/twitter.png" border="0" class="form_login_auth_image" /></a>
        
        <a href="<?=$uri_base?>login/auth/openid/yahoo_co_jp" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/yahoo_japan.png" border="0" class="form_login_auth_image" /></a>
        
        <a href="<?=$uri_base?>login/auth/google" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/google.png" border="0" class="form_login_auth_image" /></a>
        
        <a href="<?=$uri_base?>login/auth/openid/biglobe" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/biglobe.png" border="0" class="form_login_auth_image" /></a>
        
        <a href="<?=$uri_base?>login/auth/openid/livedoor" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/livedoor.png" border="0" class="form_login_auth_image" /></a>
        
        <a href="<?=$uri_base?>login/auth/openid/aol" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/aol.png" border="0" class="form_login_auth_image" /></a>
        
        
        <div class="form_login_hatena">
          <label for="auth_hatena">はてなでログイン</label>
          <input type="text" class="form-control form_input_login_hatena" id="auth_hatena" placeholder="はてなのIDを入力してください">
          <div class="form_submit_button"><button type="submit" class="btn btn-success" id="hatena_button">はてなのログインページへ</button></div>
        </div>
        
<?php else: ?>

        <a href="<?=$uri_base?>login/auth/twitter" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/twitter.png" border="0" class="form_login_auth_image" /></a>

        <a href="<?=$uri_base?>login/auth/google" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/google.png" border="0" class="form_login_auth_image" /></a>

        <a href="<?=$uri_base?>login/auth/openid/yahoo_com" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/yahoo.png" border="0" class="form_login_auth_image" /></a>
        
        <a href="<?=$uri_base?>login/auth/openid/aol" id="login_social_button"><img src="<?=$uri_base?>assets/img/login/button/aol.png" border="0" class="form_login_auth_image" /></a>

<?php endif; ?>
        </div>
        
        
        <div id="login2">
        
        <h3><?php echo __('login_view_category_title_2'); ?></h3>
        <p class="login_second_explanation" id="explanation_login"><?php echo __('login_view_category_explanation_2'); ?></p>
        
        <form action="<?=$uri_base?>login/redirect" class="" id="form_login2" accept-charset="utf-8" method="post">
          <input type="text" class="form-control form_input_login_id" name="login_username" id="login_username" placeholder="ID" maxlength="25">
          <input type="password" class="form-control form_input_login_password" name="login_password" id="login_password" placeholder="<?php echo __('password'); ?>" maxlength="32">
          
<?php if(isset($code_user_terms_2)) : ?>
          <div class="margin_top_20 margin_bottom_20">
<?=$code_user_terms_2?>
          </div>
<?php endif; ?>

          <div class="margin_top_20" id="alert_login2"></div>

          <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_login2"><span class="ladda-label"><?php echo __('login'); ?></span></button></div>
        </form>
        
        </div>
        

<?php if( ! APP_MODE) : ?>
        <div id="registration">
        
        <h3><?php echo __('login_view_category_title_3'); ?></h3>
        <p class="login_second_explanation" id="explanation_registration"><?php echo __('login_view_category_explanation_3'); ?></p>
        
        <input type="text" class="form-control form_input_login_id" name="registration_username" id="registration_username" placeholder="ID" maxlength="25">
        <input type="password" class="form-control form_input_login_password" name="registration_password" id="registration_password" placeholder="<?php echo __('password'); ?>" maxlength="32">
        <input type="password" class="form-control form_input_login_password" name="registration_password_verification" id="registration_password_verification" placeholder="<?php echo __('re_password'); ?>" maxlength="32">

<?php if(isset($code_user_terms_3)) : ?>
        <div class="margin_top_20 margin_bottom_20">
<?=$code_user_terms_3?>
        </div>
<?php endif; ?>

        <div class="margin_top_20" id="alert_registration"></div>

        <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_registration"><span class="ladda-label"><?php echo __('register'); ?></span></button></div>
        
        </div>
<?php endif; ?>

    </div>