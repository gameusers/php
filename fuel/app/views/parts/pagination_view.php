<?php

// --------------------------------------------------
//   リンクURL作成
// --------------------------------------------------

// if (empty($url))
// {
//   $url = 'javascript:void(0)';
// }


// --------------------------------------------------
//   全ページ数計算
// --------------------------------------------------

$sum = floor($total / $limit);
if ($total % $limit > 0) $sum++;


// --------------------------------------------------
//   関数の引数作成
// --------------------------------------------------

$argument = '';
if (count($argument_arr) > 0)
{
	$argument = ',' . implode(',', $argument_arr);
}


// --------------------------------------------------
//   最初に戻るボタン
// --------------------------------------------------

$first_onclick = ' onclick="' . $function_name . '(1' . $argument . ')"';

if ($page == 1)
{

	$code_prev = '  <li class="disabled"><a href="#" data-invalid-link="true">&laquo;</a></li>' . "\n";
}
else
{
  $temp_url = (isset($url)) ? $url : '#';
	$code_prev = '  <li><a href="' . $temp_url . '" class="btn btn-default ladda-button" data-style="slide-right" data-spinner-color="#000000" onclick="' . $function_name . '(this, 1' . $argument . ')" data-invalid-link="true">&laquo;</a></li>' . "\n";
}


// --------------------------------------------------
//   ナンバーボタン
// --------------------------------------------------

// ここの- 2の数字を変えると、現在のページ以前へのリンクが貼られる
$page_first = $page - 2;
if (($sum - $times + 1) < $page) $page_first = $sum - $times + 1;
if ($page_first < 1) $page_first = 1;

// echo '$page';
// \Debug::dump($page);
//
// echo '$sum';
// \Debug::dump($sum);
//
// echo '$times';
// \Debug::dump($times);
//
// echo '$page_first';
// \Debug::dump($page_first);


$code_number = '';

for ($i = 0; $i < $times; $i++)
{
	//\Debug::dump($i);
	$page_number = $i + $page_first;

	if ($page_number == $page)
	{
		$code_number .= '  <li class="active"><a href="#" data-invalid-link="true">' . $page_number . '</a></li>' . "\n";
	}
	else if ($sum >= $page_number)
	{
    $url_number = ($page_number != 1) ? '/' . $page_number : null;
    $temp_url = (isset($url)) ? $url . $url_number : '#';
		$code_number .= '  <li><a href="' . $temp_url . '" class="btn btn-default ladda-button" data-style="slide-right" data-spinner-color="#000000" id="' . $function_name . '' . $page_number . '" onclick="' . $function_name . '(this, ' . $page_number . $argument . ')" data-invalid-link="true"><span class="ladda-label">' . $page_number . '</span></a></li>' . "\n";
	}
}


// --------------------------------------------------
//   最後まで進むボタン
// --------------------------------------------------

if ($page >= $sum)
{
	$code_next = '  <li class="disabled"><a href="#" data-invalid-link="true">&raquo;</a></li>' . "\n";
}
else
{
  $temp_url = (isset($url)) ? $url . '/' . $sum : '#';
	$code_next = '  <li><a href="' . $temp_url . '" class="btn btn-default ladda-button" data-style="slide-right" data-spinner-color="#000000" onclick="' . $function_name . '(this, ' . $sum . $argument . ')" data-invalid-link="true">&raquo;</a></li>' . "\n";
}

?>
<ul class="pagination pagination_box" data-total="<?=$total?>">
<?=$code_prev?>
<?=$code_number?>
<?=$code_next?>
</ul>
