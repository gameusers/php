<?php

/*
必要なデータ
array / $game_data_arr
boolean / $administrator / 管理者かどうか
オプション
*/


//\Debug::dump($game_data_arr);
//exit();


if ($administrator)
{

  // --------------------------------------------------
  //   インスタンス作成
  // --------------------------------------------------

  $original_func_common = new \Original\Func\Common();
  $model_game = new \Model_Game();


  // --------------------------------------------------
  //    ハードウェア
  // --------------------------------------------------

  $db_hardware_arr = $model_game->get_hardware_register_game('ja');


  // --------------------------------------------------
  //    ジャンル
  // --------------------------------------------------

  $db_genre_arr = $model_game->select_data_genre();


  //\Debug::dump($db_hardware_arr, $db_genre_arr);

}

?>

<?php foreach ($game_data_arr as $key => $value): ?>

<?php

// --------------------------------------------------
//   ゲーム名　フォームをdisabledにするかどうか
// --------------------------------------------------

$disabled_name = ($value['name_fixed']) ? ' disabled' : null;
if ($administrator) $disabled_name = null;


// --------------------------------------------------
//   バージョン or 新規登録
// --------------------------------------------------

$code_ver = ($value['game_no'] != 'new') ? 'Ver. ' . $value['renewal_date'] : '新規登録';
$code_history = null;

if (isset($value['history_no']))
{
  $code_history = ($value['history_no'] + 1) . ' / ' . ($value['history_count']);
}
else if ($value['game_no'] != 'new')
{
  $code_history = 'NEW';
}

?>

<div class="margin_bottom_20 padding_bottom_20 border_bottom_dashed clearfix" id="game_no_<?=$value['game_no']?>">


  <?php if ($administrator): // 運営の場合 ?>
  <div class="game_data_form_version">
    <span class="label label-warning font_weight_normal"><?=$code_ver?></span>
    <span class="label label-default font_weight_normal"><?=$code_history?></span>
    <span class="label label-danger font_weight_normal">Approval <?=$value['approval']?></span>
    <span class="label label-danger font_weight_normal">User No <?=$value['user_no']?></span>
  </div>
<?php elseif ($value['game_no'] == 'new'): ?>
  <div class="game_data_form_version">
    <span class="label label-warning font_weight_normal"><?=$code_ver?></span>
  </div>
  <?php endif; ?>


  <input type="text" class="form-control margin_bottom_10" id="name" maxlength="100" placeholder="ゲーム名" value="<?=$value['name']?>"<?=$disabled_name?>>
  <input type="text" class="form-control margin_bottom_10" id="subtitle" maxlength="100" placeholder="サブタイトル" value="<?=$value['subtitle']?>"<?=$disabled_name?>>



  <?php if ($administrator): // 運営の場合 ?>
  <div class="panel-group margin_top_20" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_bacis_<?=$value['game_no']?>" aria-expanded="false">
            基本データ
          </a>
        </h4>
      </div>
      <div id="collapse_bacis_<?=$value['game_no']?>" class="panel-collapse collapse" style="padding: 10px" role="tabpanel">

        <div class="clearfix margin_bottom_30" role="form">

          <div class="form-inline margin_bottom_20">
            <?php for ($i=0; $i < 20; $i++): ?>
            <div class="form-group">
              <input type="text" class="form-control margin_bottom_5" id="similarity_<?=$i?>" value="<?=$value['similarity_' . $i][0]?>">
            </div>
            <?php endfor; ?>
          </div>


          <div class="input-group margin_bottom_10">
            <span class="input-group-addon">ID</span>
            <input type="text" class="form-control" id="id" maxlength="50" value="<?=$value['id']?>">
          </div>

          <div class="input-group margin_bottom_10">
            <span class="input-group-addon">カナ</span>
            <input type="text" class="form-control" id="kana" maxlength="50" value="<?=$value['kana']?>">
          </div>

          <div class="input-group">
            <span class="input-group-addon">Twitter Hashtag Ja</span>
            <input type="text" class="form-control" id="twitter_hashtag_ja" maxlength="50" value="<?=$value['twitter_hashtag_ja']?>">
          </div>

        </div>


        <div class="form-group" id="image">
          <p class="form_common_image_movie_explanation">【サムネイル アップロード （.jpgのみ）】<br>ヒーローイメージと同時にアップロードしないこと</p>

          <?php if (isset($value['thumbnail'])): // サムネイル削除フォーム ?>
          <div class="form_common_image_box">
            <div class="form_common_image"><img src="<?=URI_BASE?>assets/img/game/<?=$value['game_no']?>/thumbnail.jpg?<?=strtotime($value['renewal_date'])?>" width="100" height="100"></div>
            <div class="form_common_image_delete_checkbox"><input type="checkbox" id="thumbnail_delete"> 削除</div>
          </div>
          <?php endif; ?>

          <div class="margin_top_10"><input type="file" class="form_common_image_file" name="thumbnail" id="thumbnail"></div>
        </div>

      </div>
    </div>
  </div>



  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_additional_<?=$value['game_no']?>" aria-expanded="false">
            追加データ
          </a>
        </h4>
      </div>
      <div id="collapse_additional_<?=$value['game_no']?>" class="panel-collapse collapse" style="padding: 10px" role="tabpanel">

        <div class="form-inline margin_bottom_20">

          <?php for ($i=1; $i <= 5; $i++): ?>
          <div class="margin_bottom_10">
            <select class="form-control" id="hardware_<?=$i?>">
              <option value="">ハードウェア</option>
            <?php
            foreach ($db_hardware_arr as $key3 => $value3)
            {
              $hardware_selected = (isset($value['hardware'][($i - 1)]) and $value3['hardware_no'] === $value['hardware'][($i - 1)]) ? ' selected' : null;

              echo '      <option value="' . $value3['hardware_no'] . '"' . $hardware_selected . '>' . $value3['abbreviation'] . '</option>' . "\n";
            }
            ?>
            </select>

            <input type="date" class="form-control" id="release_date_<?=$i?>" placeholder="発売日 <?=$i?>" value="<?php echo $value['release_date_' . $i];?>" size="50"><br>
          </div>
          <?php endfor; ?>
          <!-- <p class="margin_top_10">発売日入力例）2015-01-01 00:00:00</p> -->

        </div>



        <div class="form-inline margin_bottom_20">
        <?php for ($i=1; $i <= 5; $i++): ?>
        <select class="form-control" id="genre_<?=$i?>">
          <option value="">ジャンル</option>
        <?php
        foreach ($db_genre_arr as $key4 => $value4)
        {
          $genre_selected = (isset($value['genre'][($i - 1)]) and $value4['genre_no'] === $value['genre'][($i - 1)]) ? ' selected' : null;

          echo '      <option value="' . $value4['genre_no'] . '"' . $genre_selected . '>' . $value4['name'] . '</option>' . "\n";
        }
        ?>
        </select>
        <?php endfor; ?>
        </div>


        <div class="margin_top_20 margin_bottom_20"><input type="number" class="form-control" id="players_max" placeholder="プレイヤー最大人数" value="<?=$value['players_max']?>" min="1"></div>

        <?php

        $developer_list = null;
        $code_developer = null;

        if (count($value['developer']) > 0)
        {

          $temp_arr = [];

          foreach ($value['developer'] as $key5 => $value5)
          {
            // \Debug::dump($value5);
            $code_developer .= '<div class="original_label_game bgc_lightseagreen cursor_pointer" id="developer_no_' . $value5['developer_no'] . '" onclick="GAMEUSERS.common.deleteDeveloper(this, ' . $value5['developer_no'] .  ')">' . $value5['name'] . ' / ' . $value5['studio'] . '</div>';

            array_push($temp_arr, $value5['developer_no']);
          }

          $developer_list = '[' . implode(',', $temp_arr) . ']';

        }

        ?>

        <div class="" id="scrollable-dropdown-menu">
          <input type="text" class="form-control typeahead" id="developer" placeholder="開発">
          <div class="clearfix" id="developer_list" data-list="<?=$developer_list?>"><?=$code_developer?></div>
        </div>

      </div>
    </div>
  </div>



  <?php if (isset($value['thumbnail'], $value['hero_image_arr'])): // ヒーローイメージアップロード　サムネイルがすでにアップロードされている場合にフォームを表示する ?>
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_hero_image_<?=$value['game_no']?>" aria-expanded="false">
            ヒーローイメージ
          </a>
        </h4>
      </div>
      <div id="collapse_hero_image_<?=$value['game_no']?>" class="panel-collapse collapse" role="tabpanel">
        <div class="hero_image_edit">
          <div class="box"><div class="upload"><input type="file" id="image_1"></div></div>
          <div class="box"><div class="upload"><input type="file" id="image_2"></div></div>
          <div class="box"><div class="upload"><input type="file" id="image_3"></div></div>
        </div>

        <div class="hero_image_edit">
          <?php foreach ($value['hero_image_arr'] as $key2 => $value2): ?>
          <?php $random_num = mt_rand(1, 1000000000);?>
          <div class="box">
            <?php if ($value2['on_off']): ?>
            <div class="image"><img src="<?=URI_BASE?>assets/img/u/<?=$value2['image_id']?>_s.jpg?<?=$random_num?>" width="100"></div>
            <div class="delete"><input type="checkbox" id="image_delete_<?=$value2['image_id']?>" data-id="<?=$value2['image_id']?>"> 削除</div>
            <?php endif; ?>
            <div class="upload"><input type="file" id="image_<?=$value2['image_id']?>"></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>


  <div class="panel-group margin_top_20" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_link_<?=$value['game_no']?>" aria-expanded="false">
            リンク
          </a>
        </h4>
      </div>
      <div id="collapse_link_<?=$value['game_no']?>" class="panel-collapse collapse" style="padding: 10px" role="tabpanel">

<?php //\Debug::dump($value['link_arr']); ?>
        <p class="margin_bottom_20">削除するときはタイプを空にしないで、URLだけを空にする</p>

        <?php for ($i=1; $i <= 20; $i++): ?>

        <div class="form-inline margin_bottom_5">

          <?php
          $link_name = $value['link_arr'][$i - 1]['name'] ?? null;
          $link_url = $value['link_arr'][$i - 1]['url'] ?? null;

          $selected_1 = $selected_2 = $selected_3 = $selected_4 = $selected_5 = $selected_6 = null;

          if (isset($value['link_arr'][$i - 1]))
          {
            $temp_arr = $value['link_arr'][$i - 1];
            if ($temp_arr['type'] === 'Official') $selected_1 = ' selected';
            else if ($temp_arr['type'] === 'Twitter') $selected_2 = ' selected';
            else if ($temp_arr['type'] === 'Facebook') $selected_3 = ' selected';
            else if ($temp_arr['type'] === 'YouTube') $selected_4 = ' selected';
            else if ($temp_arr['type'] === 'Steam') $selected_5 = ' selected';
            else if ($temp_arr['type'] === 'etc') $selected_6 = ' selected';
          }
          ?>

          <select class="form-control" id="type_<?=$i?>">
            <option value="">タイプ</option>
            <option value="Official"<?=$selected_1?>>公式</option>
            <option value="Twitter"<?=$selected_2?>>Twitter</option>
            <option value="Facebook"<?=$selected_3?>>Facebook</option>
            <option value="YouTube"<?=$selected_4?>>YouTube</option>
            <option value="Steam"<?=$selected_5?>>Steam</option>
            <option value="etc"<?=$selected_6?>>etc.</option>
          </select>

          <?php
          $selected_1 = $selected_2 = null;

          if (isset($value['link_arr'][$i - 1]))
          {
            $temp_arr = $value['link_arr'][$i - 1];
            if ($temp_arr['country'] === 'Japan') $selected_1 = ' selected';
            else if ($temp_arr['country'] === 'America') $selected_2 = ' selected';
          }
          ?>

          <select class="form-control" id="country_<?=$i?>">
            <option value="">国</option>
            <option value="Japan"<?=$selected_1?>>日本</option>
            <option value="America"<?=$selected_2?>>アメリカ</option>
          </select>

          <input type="text" class="form-control" id="name_<?=$i?>" placeholder="リンク名" value="<?=$link_name?>">
        </div>

        <input type="url" class="form-control margin_bottom_20" id="url_<?=$i?>" placeholder="URL" value="<?=$link_url?>">

        <?php endfor; ?>



      </div>
    </div>
  </div>


  <div class="margin_bottom_30"><input type="checkbox" id="first_bbs_thread"> 交流スレッド作成</div>

  <?php endif; // 運営の場合終わり ?>

  <div id="alert"></div>

  <?php if ($value['game_no'] != 'new' and ! $disabled_name): ?>
  <div class="game_data_form_submit"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.index.saveGameData(this, <?=$value['game_no']?>)"><span class="ladda-label">更新する</span></button></div>
  <?php elseif ( ! $disabled_name): ?>
  <div class="game_data_form_submit"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.index.saveGameData(this, null)"><span class="ladda-label">登録する</span></button></div>
  <?php endif; ?>

  <?php if ($value['game_no'] != 'new'): ?>
  <div class="game_data_form_submit"><a href="<?=URI_BASE . 'gc/' . $value['id']?>" class="btn btn-default">ゲームページへ移動</a></div>
  <?php endif; ?>

<?php

// if ($value['history_count'] == 0)
// {
//
// }
// else if (isset($value['history_no']))
// {
//
//   if ($value['history_no'] + 1 == $value['history_count'])
//   {
//     if ($value['history_no'] == 0)
//     {
//       echo '                <div class="game_data_form_submit_pager"><button type="submit" class="btn btn-default ladda-button" data-style="expand-right" data-spinner-color="#000000" id="submit_newer" onclick="GAMEUSERS.index.readGameData(this, ' . $value['game_no'] . ', null)"><span class="ladda-label">新しい更新</span></button></div>' . "\n";
//     }
//     else
//     {
//       echo '                <div class="game_data_form_submit_pager"><button type="submit" class="btn btn-default ladda-button" data-style="expand-right" data-spinner-color="#000000" id="submit_newer_' . ($value['history_no'] - 1) . '" onclick="GAMEUSERS.index.readGameData(this, ' . $value['game_no'] . ', ' . ($value['history_no'] - 1) . ')"><span class="ladda-label">新しい更新</span></button></div>' . "\n";
//     }
//   }
//   else if ($value['history_no'] == 0)
//   {
//     echo '                <div class="game_data_form_submit_pager"><button type="submit" class="btn btn-default ladda-button" data-style="expand-right" data-spinner-color="#000000" id="submit_older_' . ($value['history_no'] + 1) . '" onclick="GAMEUSERS.index.readGameData(this, ' . $value['game_no'] . ', ' . ($value['history_no'] + 1) . ')"><span class="ladda-label">古い更新</span></button></div>' . "\n";
//     echo '                <div class="game_data_form_submit_pager"><button type="submit" class="btn btn-default ladda-button" data-style="expand-right" data-spinner-color="#000000" id="submit_newer" onclick="GAMEUSERS.index.readGameData(this, ' . $value['game_no'] . ', null)"><span class="ladda-label">新しい更新</span></button></div>' . "\n";
//   }
//   else
//   {
//     echo '                <div class="game_data_form_submit_pager"><button type="submit" class="btn btn-default ladda-button" data-style="expand-right" data-spinner-color="#000000" id="submit_older_' . ($value['history_no'] + 1) . '" onclick="GAMEUSERS.index.readGameData(this, ' . $value['game_no'] . ', ' . ($value['history_no'] + 1) . ')"><span class="ladda-label">古い更新</span></button></div>' . "\n";
//     echo '                <div class="game_data_form_submit_pager"><button type="submit" class="btn btn-default ladda-button" data-style="expand-right" data-spinner-color="#000000" id="submit_newer_' . ($value['history_no'] - 1) . '" onclick="GAMEUSERS.index.readGameData(this, ' . $value['game_no'] . ', ' . ($value['history_no'] - 1) . ')"><span class="ladda-label">新しい更新</span></button></div>' . "\n";
//   }
// }
// else
// {
//   echo '                <div class="game_data_form_submit_pager"><button type="submit" class="btn btn-default ladda-button" data-style="expand-right" data-spinner-color="#000000" id="submit_older_0" onclick="GAMEUSERS.index.readGameData(this, ' . $value['game_no'] . ', 0)"><span class="ladda-label">古い更新</span></button></div>' . "\n";
// }

?>

</div>

<?php endforeach; ?>
