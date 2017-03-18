<?php
return array(
	'_root_'  => 'index/index',  // The default route
	'_404_'   => 'top/404',    // The main 404 route

	'in/(:any)'      => 'index/index/$1', 	// トップページ
	'pl/(:any)'      => 'pl/index/$1', 	// プレイヤーページ
	'gc/(:any)'      => 'gc/index/$1', 	// ゲームコミュニティ
	'uc/(:any)'      => 'uc/index/$1', 	// ユーザーコミュニティ
);
