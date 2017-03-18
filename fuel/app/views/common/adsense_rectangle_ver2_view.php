<?php if (Fuel::$env == 'production' and ! AD_BLOCK): ?>
<aside class="clearfix">

<aside class="ad_adsense_rectangle">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- GU レクタングル -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-8883929243875711"
     data-ad-slot="1930071119"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</aside>
<?php else: ?>
<img src="<?=URI_BASE?>assets/img/common/adsense_sample_300x250.png" width="300" height="250">
<?php endif; ?>
