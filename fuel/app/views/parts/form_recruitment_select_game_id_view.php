<?php

/*
 * 必要なデータ
 * array / $view_arr
 * string / $data_id_hardware_no_id / 選択してるIDをJavascriptに伝えるための文字列
 * string / $code_pagination / ページャーのコード
 * 
 * オプション
 * 
 */

if (count($view_arr) > 0)
{
	//var_dump($data_id_hardware_no_id);
	//$label_number = mt_rand();
	
	echo '          <div class="gc_recruitment_form_select_id_box" id="gc_recruitment_form_select_id_box" data-selected_id_arr="' . $data_id_hardware_no_id . '">' . "\n\n";
	
	foreach ($view_arr as $key => $value)
	{
		
		echo '            <div class="gc_recruitment_id_list">' . "\n";
		echo '              <label class="font_weight_normal">' . "\n";
		echo '                <span class="gc_recruitment_form_checkbox_select_id"><input type="checkbox" id="id_select_checkbox_' . ($key + 1) . '" data-hardware_no="' . $value['hardware_no'] . '" data-id="' . $value['id'] . '"' . $value['checked'] . '></span>' . "\n";
		echo '                <span class="label label-danger gc_recruitment_id_label">' . $value['hardware_name'] . '</span>' . "\n";
		echo '                <span class="gc_recruitment_id">' . $value['id'] . '</span>' . "\n";
		echo '              </label>' . "\n";
		echo '            </div>' . "\n\n";
		
	}
	
	echo $code_pagination . "\n\n";
	
	echo '          </div>' . "\n\n";
	
}

?>
