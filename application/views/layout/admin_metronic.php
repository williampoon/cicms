<!DOCTYPE html>
<html>
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>{block name="title"}OA{/block}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/public/metronic/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/public/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/public/metronic/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/public/metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/public/metronic/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/public/metronic/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/public/metronic/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="/public/metronic/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="/public/metronic/pages/css/login-3.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
    {block name="header"}{/block}
</head>
<!-- END HEAD -->
<body class="{block name='body-class'}{/block}">
{block name="content"}{/block}
    <!--[if lt IE 9]>
        <script src="/public/metronic/global/plugins/respond.min.js"></script>
    <script src="/public/metronic/global/plugins/excanvas.min.js"></script>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="/public/metronic/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/public/metronic/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/public/metronic/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="/public/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="/public/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="/public/metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="/public/metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="/public/metronic/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/public/metronic/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="/public/metronic/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/public/metronic/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/public/metronic/pages/scripts/login.min.js" type="text/javascript"></script>
    <?php if (isset($js_file)) { ?>
    <script type="text/javascript" src="<?php echo js_base_dir(); ?>require/bootup.js"></script>
    <?php
        $target_file = isset($js_file) ? js_dir() . $js_file . '.js' : '';
        $path        = $target_file ? FCPATH . ltrim($target_file, DS) : '';
        if (is_file($path)) {
    ?>
    <script type="text/javascript">
    new BootUp([
        '<?=js_base_dir();?>require/require.js',
        '<?=js_base_dir();?>require/config.js',
        '<?=$target_file;?>'
    ], {
        version: <?=(ENVIRONMENT === 'production' ? date('Ymd') : time());?>,
        success: function () {
            require({
                baseUrl: '<?=js_base_dir();?>',
                urlArgs: '<?=(ENVIRONMENT === 'production' ? date('Ymd') : time());?>'
            });
        },
        fresh: <?=(ENVIRONMENT === 'production' ? 'false' : 'true');?>
    });
    </script>
    <?php }
    }?>
{block name="footer"}{/block}
</body>
</html>