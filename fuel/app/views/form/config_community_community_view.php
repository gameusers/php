<?php

/*
  必要なデータ

  オプション
*/

?>

<div id="config_community">

  <h2 id="heading_black">追加設定</h2>

  <div class="panel panel-default">
    <div class="panel-body">

      <p class="margin_bottom_20 padding_bottom_20 border_bottom_dashed">コミュニティの追加設定が行えます。</p>


      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">

        <p class="font_weight_bold">オープンコミュニティ</p>
        <p>コミュニティの更新内容を公開するか、公開しないかの設定です。オープンを選ぶと、告知や掲示板の書き込みがGame Usersのトップページにフィードとして掲載されます。身内だけで情報交換をしたい場合などはクローズドを選んでください。</p>

        <div class="radio">
          <label>
            <input type="radio" name="open" value="1"<?php if ($open) echo ' checked'; ?>> オープン
          </label>
        </div>

        <div class="radio">
          <label>
            <input type="radio" name="open" value=""<?php if ( ! $open) echo ' checked'; ?>> クローズド
          </label>
        </div>

      </div>


      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">

        <p class="font_weight_bold">コミュニティ参加</p>
        <p>誰でもすぐに参加できる設定、コミュニティの管理者、またはモデレーターが承認後参加できる設定、どちらかを選ぶことができます。フレンドのみ、ギルドメンバーのみなど、決まったメンバーでコミュニティを運営したい場合は、承認後に参加を選んでください。</p>

        <div class="radio">
          <label>
            <input type="radio" name="participation_type" id="participation_type1" value="1"<?php if ($config_arr['participation_type'] == 1) echo ' checked'; ?>> 誰でも参加
          </label>
        </div>

        <div class="radio">
          <label>
            <input type="radio" name="participation_type" id="participation_type2" value="2"<?php if ($config_arr['participation_type'] == 2) echo ' checked'; ?>> 承認後に参加
          </label>
        </div>

      </div>


      <div class="form-group margin_bottom_20 padding_bottom_20 border_bottom_dashed">
        <p class="font_weight_bold">オンライン扱いになる時間</p>
        <p>Game Usersでは、コミュニティメンバーが当サイトに最近アクセスした時間を表示することができます。<br><br>例えば、オンライン扱いになる時間を24と設定すると、24時間以内にアクセスしたユーザーは、このコミュニティ内ではオンライン扱いになり、アクセスした時間が表示されます。以前のアクセスから24時間以上経ったユーザーは、オフラインとして表示されます。こちらの設定で最大一週間（168時間）以内のアクセスまでオンライン扱いにすることができます。</p>
        <input type="number" class="form-control" id="online_limit" min="1" max="168" placeholder="オンライン扱いになる時間" value="<?=$config_arr['online_limit']?>">
      </div>

      <div class="form-group margin_bottom_20 padding_bottom_10 border_bottom_dashed">
        <p class="font_weight_bold">ななしでの投稿</p>
        <p>ユーザーがななしで掲示板のスレッドを立てたり、掲示板のコメントを投稿することができるようになります。気軽に書き込んで欲しい場合などに、ななしを認めておくといいかもしれません。</p>
        <div class="checkbox"><label><input type="checkbox" id="anonymity"<?php if ($config_arr['anonymity']) echo ' checked'; ?>> 認める</label></div>
      </div>


      <div id="alert"></div>

      <div class="form_submit_button"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.uc.saveConfigCommunity(this, <?=$community_no?>)"><span class="ladda-label">送信する</span></button></div>

    </div>
  </div>

</div>
