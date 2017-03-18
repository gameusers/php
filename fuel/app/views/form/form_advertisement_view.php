<?php

/*
* 必要なデータ
* string / $amazon_tracking_id / AmazonのトラッキングID
* array / $code_form_advertisement_list / データ
*
* オプション
*/


?>


<?php if ( ! Auth::member(100)) : ?>
<h2 id="heading_black">Amazon トラッキングID</h2>

<div class="panel panel-default" id="config_advertisement">
  <div class="panel-body">

    <div class="clearfix" id="config_advertisement_amazon">
      <p>
      Game Usersで作成できるWikiに広告を掲載することができます。<br><br>AmazonのトラッキングIDを登録すると、このページの下部にあるような、ゲーム関連の商品がスライドしながら表示される広告を利用することができるようになります。トラッキングIDの例） amazon-22
      </p>

      <div class="input-group padding_bottom_20"><div class="input-group-addon">トラッキングID</div><input type="text" class="form-control" id="tracking_id" maxlength="30" value="<?=$amazon_tracking_id?>"></div>

      <div id="alert"></div>

      <div class="form_common_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.player.saveAmazonTrackingId(this)"><span class="ladda-label">登録する</span></button></div>
    </div>

  </div>
</div>
<?php endif; ?>


<h2 id="heading_black">広告コードの登録</h2>

<div class="panel panel-default" id="config_advertisement">
  <div class="panel-body">

    <p class="margin_bottom_10">

広告コードを登録すると、Wikiからそのコードを呼び出すことができるようになります。またWiki設定でデフォルト広告に設定すると、Wikiのすべてのページに設定した広告を表示することができるようになります。<br><br>

『広告名』　はWikiから呼び出すときに必要になります。1文字以上、20文字以内。利用できる文字は半角英数字とハイフン( - )アンダースコア( _ )です。例）ad_1<br><br>

『広告コード』　にはアフィリエイト提供会社（Google Adsense、Amazonアソシエイトなど）から取得できる広告コードを貼り付けてください。PCでアクセスしたとき、スマートフォンでアクセスしたとき、それぞれ違う広告を表示することができます。例）PCの場合は横幅の大きな広告を表示、スマートフォンの場合は横幅の小さな広告を表示。<br><br>

『コメント欄』　は自分用に広告について解説を残しておきたい場合などに使用してください。<br><br>

『自分がアクセスした時は表示しない』　のチェックボックスは、広告の誤クリックを防止するためにあります。クリック型広告を掲載する場合はチェックしておきましょう。Game Usersにログインしている間、チェックした広告は自分に対して表示されません。<br><br>

広告はGame Users運営によって確認され、アダルト広告や安全性が確認できないコードは掲載が認められないことがあります。確認済みの広告は「未承認」と書かれた部分が「承認済み」に変わります。掲載が認められなかった場合はコメント欄にその理由が記載されます。<br><br>


<strong>Wikiへの広告の貼り方</strong>

<br><br>Wikiのページを編集して広告を貼りたい部分に　#gu_ad(広告名)　このコードを貼り付けてください。例えば、広告名がad_1の場合は　#gu_ad(ad_1)　となります。プレイヤーページのWiki設定で、デフォルト広告に設定すると、Wikiの全ページに自動的に広告が掲載されます。<br><br>


<strong>Google Adsenseについて</strong>

<br><br>Wikiには初期状態で3つのAdsense広告（コンテンツ向け AdSense ユニット）が掲載されています。そのうちの2つをユーザーが好きな広告に置き換えることができますが、残りのひとつはそのまま表示され続けます。Googleが定めた『広告の配置に関するポリシー』では、コンテンツ向け AdSense ユニットは最大で3つまでしか掲載することができないため、ユーザーが掲載できるAdsense広告（コンテンツ向け AdSense ユニット）は2つまでになります。Adsenseを利用する場合は、配置可能な広告数をオーバーしないように気をつけてください。<br><br>

<a href="https://support.google.com/adsense/answer/1346295?hl=ja#Ad_limit_per_page">Google Adsense 1 ページに配置可能な広告の上限</a>

    </p>


    <div class="clearfix" id="config_advertisement_form_box">
<?=$code_form_advertisement_list?>
    </div>


  </div>
</div>
