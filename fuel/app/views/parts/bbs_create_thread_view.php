  <aside class="bbs_create_thread_box">

    <div class="panel panel-default" id="collapse_bbs_thread_list_box">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#bbs_create_thread_box" aria-expanded="false">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> BBSスレッド作成
          </a>
        </h4>
      </div>
      
      <div id="bbs_create_thread_box" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body">

<?php

$view = View::forge('parts/form_common_view');
$view->set_safe('app_mode', $app_mode);
$view->set_safe('uri_base', $uri_base);
$view->set_safe('login_user_no', $login_user_no);
$view->set_safe('datetime_now', $datetime_now);
$view->set_safe('profile_arr', $profile_arr);
$view->set_safe('online_limit', $online_limit);
$view->set_safe('anonymity', $anonymity);
$view->set_safe('func_name', $func_name);
$view->set_safe('func_argument_arr', $func_argument_arr);
echo $view->render();

?>
      
        </div>
      </div>

    </div>

  </aside>

