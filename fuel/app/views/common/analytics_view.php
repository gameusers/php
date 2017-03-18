<?php if (Fuel::$env == 'production'): ?>
<?php
$ga_dimension_value_1 = (USER_NO) ? 'Login' : 'Not_Login';
$ga_user_id_code = (USER_NO) ? ", { 'userId': 'UserNo_" . USER_NO . "' }" : null;
?>
<?php if (empty($app_mode)): ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65840111-1', 'auto'<?=$ga_user_id_code?>);
  ga('set', 'dimension1', '<?=$ga_dimension_value_1?>');
<?php if (AD_BLOCK): ?>
  ga('set', 'dimension2', 'Administrator');
<?php endif; ?>
  ga('send', 'pageview');

</script>
<?php else: ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65903811-1', 'auto'<?=$ga_user_id_code?>);
  ga('set', 'dimension1', '<?=$ga_dimension_value_1?>');
<?php if (AD_BLOCK): ?>
  ga('set', 'dimension2', 'Administrator');
<?php endif; ?>
  ga('send', 'pageview');

</script>
<?php endif; ?>
<?php endif; ?>
