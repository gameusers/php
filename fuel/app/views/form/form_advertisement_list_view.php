<?php

/*
 * 必要なデータ
 * array / $db_advertisement_arr / データ
 * integer / $limit / フォームを表示する件数
 * string / $amazon_tracking_id / AmazonのトラッキングID
 * boolean / $all / 全体を表示するか一部を表示するか　（ページャーを作っていないため、今のところ意味がない）
 * 
 * オプション
 */

?>


<?php for ($i=0; $i < $limit; $i++): ?>

<?php

$advertisement_no = (isset($db_advertisement_arr[$i]['advertisement_no'])) ? $db_advertisement_arr[$i]['advertisement_no'] : 'null';

$label = '<span class="label label-default">未承認</span>';

if (isset($db_advertisement_arr[$i]['approval']))
{
	if ($db_advertisement_arr[$i]['approval'] == 1)
	{
		$label = '<span class="label label-warning">承認済み</span>';
	}
	else if ($db_advertisement_arr[$i]['approval'] == 2)
	{
		$label = '<span class="label label-default">掲載不可</span>';
	}
}

//$label = (isset($db_advertisement_arr[$i]['approval'])) ? '<span class="label label-warning">承認済み</span>' : '<span class="label label-default">未承認</span>';
$name = (isset($db_advertisement_arr[$i]['name'])) ? $db_advertisement_arr[$i]['name'] : null;
$code = (isset($db_advertisement_arr[$i]['code'])) ? $db_advertisement_arr[$i]['code'] : null;
$code_sp = (isset($db_advertisement_arr[$i]['code_sp'])) ? $db_advertisement_arr[$i]['code_sp'] : null;
$comment = (isset($db_advertisement_arr[$i]['comment'])) ? $db_advertisement_arr[$i]['comment'] : null;
$hide_myself_checked = (isset($db_advertisement_arr[$i]['hide_myself'])) ? ' checked' : null;


$ad_default_wiki_1_selected = null;
$ad_default_wiki_2_selected = null;

if (isset($db_advertisement_arr[$i]['ad_default']))
{
	if ($db_advertisement_arr[$i]['ad_default'] === 'wiki_1')
	{
		$ad_default_wiki_1_selected = ' selected';
	}
	else if ($db_advertisement_arr[$i]['ad_default'] === 'wiki_2')
	{
		$ad_default_wiki_2_selected = ' selected';
	}
}

// 運営用
$approval_1_selected = null;
$approval_2_selected = null;

if (isset($db_advertisement_arr[$i]['approval']))
{
	//\Debug::dump($db_advertisement_arr[$i]['approval']);
	if ($db_advertisement_arr[$i]['approval'] == 1)
	{
		$approval_1_selected = ' selected';
	}
	else if ($db_advertisement_arr[$i]['approval'] == 2)
	{
		$approval_2_selected = ' selected';
	}
}

//$approval_checked = (isset($db_advertisement_arr[$i]['approval'])) ? ' checked' : null;
$regi_date = (isset($db_advertisement_arr[$i]['regi_date'])) ? $db_advertisement_arr[$i]['regi_date'] : null;
$renewal_date = (isset($db_advertisement_arr[$i]['renewal_date'])) ? $db_advertisement_arr[$i]['renewal_date'] : null;
$user_no = (isset($db_advertisement_arr[$i]['user_no'])) ? $db_advertisement_arr[$i]['user_no'] : null;


//\Debug::dump($advertisement_no);

// 運営の場合、新規追加用のフォームは表示しない
if (Auth::member(100) and empty($db_advertisement_arr[$i]['advertisement_no']))
{
	//echo 'aaaaaaaaaa';
	break;
}

?>

    <div class="form-group clearfix padding_top_30" id="ad_box" data-advertisement_no="<?=$advertisement_no?>">
      
      <div class="player_form_label_approval" id="label_approval"><?=$label?></div>
      <div class="input-group margin_bottom_10"><div class="input-group-addon">広告名</div><input type="text" class="form-control" id="name" maxlength="20" value="<?=$name?>"></div>
      <div class="margin_bottom_10"><textarea class="form-control" id="code" rows="3" maxlength="1000" placeholder="広告コード"><?=$code?></textarea></div>
      <div class="margin_bottom_10"><textarea class="form-control" id="code_sp" rows="3" maxlength="1000" placeholder="広告コード / スマートフォン用　（未記入でもOK）"><?=$code_sp?></textarea></div>
      <div class="margin_bottom_10"><textarea class="form-control" id="comment" rows="3" maxlength="1000" placeholder="コメント欄　（未記入でもOK）"><?=$comment?></textarea></div>
<?php
/*
      <div class="margin_bottom_20">
        <select class="form-control" id="ad_default">
          <option value="">デフォルト広告設定</option>
          <option value="wiki_1"<?=$ad_default_wiki_1_selected?>>◆ Wiki ページ上部</option>
          <option value="wiki_2"<?=$ad_default_wiki_2_selected?>>◆ Wiki ページ下部</option>
        </select>
      </div>
*/
?>
      <div class="margin_bottom_20"><input type="checkbox" id="hide_myself"<?=$hide_myself_checked?>> 自分がアクセスした時は表示しない</div>



<?php if (Auth::member(100)): ?>
      <div class="margin_bottom_20 font_weight_bold">登録日 ： <?=$regi_date?><br>更新日 ： <?=$renewal_date?><br>USER_NO　:　<?=$user_no?></div>
      <div class="margin_bottom_20">
        <select class="form-control" id="approval">
          <option value="">未承認</option>
          <option value="1"<?=$approval_1_selected?>>承認する</option>
          <option value="2"<?=$approval_2_selected?>>掲載不可</option>
        </select>
      </div>
<?php endif; ?>

      <div id="alert"></div>

      <div id="test_box"></div>
      <div id="test_box_sp"></div>

      <div class="form_common_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.player.saveAdvertisement(this)"><span class="ladda-label">登録する</span></button></div>
      <div class="form_common_submit_left"><button type="submit" class="btn btn-success ladda-button" data-style="expand-right" id="submit" onclick="GAMEUSERS.player.showAdvertisement(this)"><span class="ladda-label">広告を表示する</span></button></div>

    </div>
    
<?php endfor; ?>



<?php

// --------------------------------------------------
//   ページャー
// --------------------------------------------------

if ($pagination_total > $pagination_limit)
{
	echo '    <div class="padding_top_20">' . "\n";
	$view_pagination = View::forge('parts/pagination_view');
	$view_pagination->set_safe('page', $pagination_page);
	$view_pagination->set_safe('total', $pagination_total);
	$view_pagination->set_safe('limit', $pagination_limit);
	$view_pagination->set_safe('times', $pagination_times);
	$view_pagination->set_safe('function_name', $pagination_function_name);
	$view_pagination->set_safe('argument_arr', $pagination_argument_arr);
	echo $view_pagination->render();
	echo '    </div>' . "\n";
}

?>
