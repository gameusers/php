<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
<?=$meta?>
<?php echo Asset::css($css_arr); ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<?php if (isset($manifest)) : ?>
<link rel="manifest" href="<?=URI_BASE?>manifest.json">
<?php endif; ?>
<?=$original_js?>
</head>
<body>

<?=$header?>

<?=$main?>

<?=$footer?>

<?php echo Asset::js($js_arr); ?>

</body>
</html>
