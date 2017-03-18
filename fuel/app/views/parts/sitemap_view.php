<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

   <url>
      <loc>https://gameusers.org/</loc>
      <lastmod><?=$datetime_now?></lastmod>
      <changefreq>daily</changefreq>
      <priority>1.0</priority>
   </url>

<?php foreach ($db_game_community_arr as $key => $value): ?>
   <url>
      <loc>https://gameusers.org/gc/<?=$value['id']?></loc>
<?php
$datetime = new \DateTime($value['sort_date']);
$datetime_w3c = $datetime->format(\DateTime::W3C);
?>
      <lastmod><?=$datetime_w3c?></lastmod>
      <changefreq>weekly</changefreq>
      <priority>0.7</priority>
   </url>

<?php endforeach; ?>

<?php foreach ($db_user_community_arr as $key => $value): ?>
   <url>
      <loc>https://gameusers.org/uc/<?=$value['community_id']?></loc>
<?php
$datetime = new \DateTime($value['sort_date']);
$datetime_w3c = $datetime->format(\DateTime::W3C);
?>
      <lastmod><?=$datetime_w3c?></lastmod>
      <changefreq>weekly</changefreq>
      <priority>0.7</priority>
   </url>

<?php endforeach; ?>

<?php foreach ($db_users_arr as $key => $value): ?>
   <url>
      <loc>https://gameusers.org/pl/<?=$value['user_id']?></loc>
<?php
$datetime = new \DateTime($value['renewal_date']);
$datetime_w3c = $datetime->format(\DateTime::W3C);
?>
      <lastmod><?=$datetime_w3c?></lastmod>
      <changefreq>weekly</changefreq>
      <priority>0.5</priority>
   </url>

<?php endforeach; ?>

<?php foreach ($db_wiki_arr as $key => $value): ?>
   <url>
      <loc>https://gameusers.org/wiki/<?=$value['wiki_id']?>/</loc>
      <changefreq>daily</changefreq>
      <priority>0.7</priority>
   </url>

<?php endforeach; ?>

</urlset>
