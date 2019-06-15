<?php if (isset($left_nodes) && is_array($left_nodes)) { foreach ($left_nodes as $node) { ?>
<li class="treeview">
    <a class="leaf-menu" data-url="<?php echo empty($node['children']) ? $node['url'] : '#';?>">
    <i class="<?php echo $node['icon'] ? $node['icon'] : 'fa fa-link'?>"></i>
    <span><?php echo $node['name'];?></span>
    <?php if (!empty($node['children'])) { ?>
    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    <?php } ?>
    </a>
    <?php if (!empty($node['children'])) { echo logic('Node')->subtree($node['children']); } ?>
</li>
<?php } ?>
<script>
    // 切换主内容
    $('.leaf-menu').click(function() {
        let url = $(this).data('url');
        if (url == '#') {
            return;
        }

        $.ajax(url, {
            type: 'get',
            data: {},
            dataType: 'json',
            complete: function(xhr, text) {
                $('.content').html(xhr.responseText);
            },
        });
    });
</script>
<?php } ?>