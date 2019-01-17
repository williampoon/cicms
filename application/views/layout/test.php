<!DOCTYPE html>
<html>
<head>
    <title>{block name="title"}Test{/block}</title>
    {block name="header"}{/block}
</head>
<body>
{block name="content"}{/block}
<?php if (isset($js_file)) {
    ?>
<script type="text/javascript" src="<?php echo js_base_dir(); ?>require/bootup.js"></script>
<?php

    $obj_file = isset($js_file) ? js_dir() . $js_file . '.js' : '';
    $path     = $obj_file ? FCPATH . ltrim($obj_file, DS) : '';
    $path     = '';
    if (is_file($path)) {

        ?>
<script type="text/javascript">
new BootUp([
    '<?=js_base_dir();?>require/require.js',
    '<?=js_base_dir();?>require/config.js',
    '<?=$obj_file;?>'
], {
    version: <?=(ENVIRONMENT == 'production' ? date('Ymd') : time());?>,
    success: function () {
        require({
            baseUrl: '<?=js_base_dir();?>',
            urlArgs: '<?=(ENVIRONMENT == 'production' ? date('Ymd') : time());?>'
        });
    },
    fresh: <?=(ENVIRONMENT == 'production' ? 'false' : 'true');?>

});
</script>
<?php }
}?>
{block name="footer"}{/block}
</body>
</html>
