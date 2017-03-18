<?php

/*
 * 必要なデータ
 * array / $notification_data_arr / データ
 *
 * オプション
 */


// ON OFF
if ($notification_data_arr['on_off'])
{
	$config_notification_on = ' checked';
	$config_notification_off = null;
}
else
{
	$config_notification_on = null;
	$config_notification_off = ' checked';
}

// On Off ブラウザー
$config_notification_on_off_browser = null;
if ($notification_data_arr['on_off_browser']) $config_notification_on_off_browser = ' checked';

// On Off アプリ
$config_notification_on_off_app = null;
if ($notification_data_arr['on_off_app']) $config_notification_on_off_app = ' checked';

// On Off メール
$config_notification_on_off_mail = null;
if ($notification_data_arr['on_off_mail']) $config_notification_on_off_mail = ' checked';

?>

<h2 id="heading_black">通知のオンオフ</h2>

<div class="panel panel-default" id="config_notification">
  <div class="panel-body">

    <p class="margin_bottom_20">Game Usersからの通知に関する設定です。ブラウザ（Google Chrome と Firefox）、アプリまたはメールで通知を受信することができます。</p>

    <div class="player_config_notification_data_app_select_box">

      <p class="font_weight_bold">受信設定</p>
      <p>Game Usersからの通知を受信する場合は「ON」、受信しない場合は「OFF」を選択してください。ここは通知に関する一番大元の設定になります。OFFにした場合、Game Usersからのすべての通知・メールが受信できなくなります。</p>

      <label class="radio-inline">
        <input type="radio" name="config_notification_on_off" id="config_notification_on" value="on"<?=$config_notification_on?>> ON
      </label>
      <label class="radio-inline">
        <input type="radio" name="config_notification_on_off" id="config_notification_off" value="off"<?=$config_notification_off?>> OFF
      </label>

    </div>

    <div class="player_config_notification_data_app_select_box">

      <p class="font_weight_bold">通知を受信する方法</p>
      <p>通知はブラウザ、アプリ、メールで受けることが可能です。ここでチェックされた方法を利用して通知が受信されます。<br><font color="red">※ ブラウザ・アプリ・メールを登録しただけでは通知は受信できません。ここで必ずチェックを行ってください。</font><br><br>ブラウザ・アプリの場合、通知の頻度はあがり、メールの場合は、募集掲示板で自分の投稿に返信が来たとき、参加しているコミュニティから通知の一斉送信を受けたときのみ受信できます。</p>

      <div class="padding_top_5 padding_bottom_5">
        <label class="checkbox-inline">
          <input type="checkbox" id="config_notification_on_off_browser"<?=$config_notification_on_off_browser?>> ブラウザ
        </label>

        <label class="checkbox-inline">
          <input type="checkbox" id="config_notification_on_off_app"<?=$config_notification_on_off_app?>> アプリ
        </label>

        <label class="checkbox-inline">
          <input type="checkbox" id="config_notification_on_off_mail"<?=$config_notification_on_off_mail?>> メール
        </label>
      </div>

    </div>


    <div id="alert" class="margin_top_20"></div>

    <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_email" onclick="GAMEUSERS.player.saveConfigNotification(this)"><span class="ladda-label">送信する</span></button></div>

  </div>
</div>



<?php if (isset($notification_data_arr['browser_info'])): ?>
<h2 id="heading_black">ブラウザ選択</h2>

<div class="panel panel-default" id="config_notification">
  <div class="panel-body">

    <p class="margin_bottom_20">Game Usersから通知を受けるブラウザを選択してください。3つまで選択することが可能です。現在、通知を受けられるブラウザは 最新版のGoogle Chrome と Firefox のみです。それ以外のブラウザでは通知を受信できません。<br><br>例えばPC、タブレット、スマートフォンで使用している3つのブラウザを登録しておくと、すべてのデバイスで通知を受信できるようになります。<br><br>ログイン後、ブラウザで自分のプレイヤーページ（このページ）にアクセスすると、。ブラウザ情報が以下の一覧に自動的に登録されます。一覧に登録できるブラウザは最大で10件まで。新しいブラウザで通知を受けたい時に、すでに10件登録されてしまっている場合は、不要なブラウザを一覧から削除してからこのページを更新してください。</p>

    <div class="player_config_notification_data_save_mail_box">
      <table class="table table-striped">
        <thead>
          <tr>
            <th style="text-align:center">選択</th>
            <th>ブラウザ情報</th>
            <th style="text-align:center">削除</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($notification_data_arr['browser_info'] as $key => $value): ?>
<?php

$ckecked_browser = null;

if (is_array($notification_data_arr['receive_browser']))
{
	if (in_array($key, $notification_data_arr['receive_browser'])) $ckecked_browser = ' checked';
}

?>
					<tr id="tr_<?=$key?>">
						<td style="text-align:center">
							<input type="checkbox" id="config_notification_receive_browser" value="<?=$key?>"<?=$ckecked_browser?>>
						</td>
						<td>
							<?=$value['user_agent']?><br>（ブラウザ登録日 ： <?=$value['regi_date']?>）
						</td>
						<td style="text-align:center">
							<input type="checkbox" id="config_notification_browser_delete" value="<?=$key?>">
						</td>
					</tr>
<?php endforeach; ?>
				</tbody>
			</table>
		</div>

    <div id="alert" class="margin_top_20"></div>

    <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.player.saveConfigNotification(this)"><span class="ladda-label">送信する</span></button></div>

  </div>
</div>
<?php endif; ?>



<?php if (isset($notification_data_arr['device_info'])): ?>
	<h2 id="heading_black">アプリ デバイス選択</h2>

  <div class="panel panel-default" id="config_notification">
    <div class="panel-body">

			<p class="margin_bottom_20">複数のデバイス（スマホ・タブレットなど）にアプリをインストールした場合は、どのデバイスで通知を受信するか、こちらで選択することができます。<br><br>Game Users アプリでログインすると、使用しているデバイスが一覧に登録されます。不要なデバイスが一覧に存在する場合は、削除のチェックボックスをチェックしてから送信ボタンを押すと削除されます。登録できるデバイスは最大で10件までです。</p>

<div class="player_config_notification_data_save_mail_box">
	<table class="table table-striped">
		<thead>
			<tr>
				<th style="text-align:center">選択</th>
				<th>デバイス名</th>
				<th style="text-align:center">削除</th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($notification_data_arr['device_info'] as $key => $value): ?>
<?php $ckecked_app_device = ($notification_data_arr['receive_device'] == $key) ? ' checked' : null; ?>
			<tr id="tr_<?=$key?>">
				<td style="text-align:center">
					<input type="radio" name="config_notification_receive_device" id="config_notification_receive_device" value="<?=$key?>"<?=$ckecked_app_device?>>
				</td>
				<td>
					<?=$value['name']?><br>（デバイス登録日 ： <?=$value['regi_date']?>）
				</td>
				<td style="text-align:center">
					<input type="checkbox" id="config_notification_app_device_delete" value="<?=$key?>">
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>

      <div id="alert" class="margin_top_20"></div>

      <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.player.saveConfigNotification(this)"><span class="ladda-label">送信する</span></button></div>

    </div>
  </div>
<?php endif; ?>



<h2 id="heading_black">携帯メール登録</h2>

<div class="panel panel-default" id="config_notification">
  <div class="panel-body">

    <p class="margin_bottom_20">Game Usersからの通知をメールで受ける場合は、こちらでメールアドレスを登録してください。</p>

    <div class="player_config_notification_data_save_mail_box">

      <p>すぐに通知が伝わる携帯などのアドレスを登録してください（メールを携帯に転送できる方は転送元になるメールアドレスでもOKです）　登録すると入力したメールアドレスの方に確認メールが届きます。24時間以内に表示されているURLにアクセスして登録を完了してください。<br><br>ガラケーや古い携帯を利用している場合は、送られてきたURLにアクセスできないことがあります。その場合はURLをパソコンなどに転送してアクセスしてみてください。他の端末からアクセスしても登録したメールアドレスは有効になります。<br><br>メールは mail@gameusers.org こちらのアドレスから届きます。ドメイン指定をされている方は @gameusers.org を受信できるように設定してください。<br><br>登録を解除する場合はフォームを空欄にして送信してください。<br><br>※ドメイン指定の失敗などで確認メールを受信できなかった場合は、24時間後に再登録すると確認メールがもう一度送信されます。</p>

      <div class="form-group">
        <input type="text" class="form-control" id="email" placeholder="携帯メール" value="<?=$email?>">
      </div>

      <div id="alert_email"></div>

      <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit_email" onclick="GAMEUSERS.player.saveEmail(this)"><span class="ladda-label">送信する</span></button></div>

    </div>

  </div>
</div>
