{extends file="layouts/admin_metronic.php"}

{block name="title"}{/block}

{block name="header"}
<link rel="stylesheet" href="/public/metronic/global/plugins/datatables/datatables.min.css" />
{/block}

{block name="content"}
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN BORDERED TABLE PORTLET-->
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-server font-blue"></i>
                    <span class="caption-subject font-blue sbold uppercase">系统</span>
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                        <a class="btn blue btn-outline sbold" data-toggle="modal" data-title="新增" href="#modal-update"> 新增 </a>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table id="dt-system" class="table table-hover table-light">
                    <thead>
                        <tr class="uppercase">
                            <th> # </th>
                            <th> 名称 </th>
                            <th> 地址 </th>
                            <th> 操作 </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->

        <div class="modal fade" id="modal-update" tabindex="-2" role="basic" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title"> 添加 </h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" id="form-update">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        <span class="required" aria-required="true"> * </span> 名称:
                                    </label>
                                    <div class="col-md-8">
                                        <input name="name" type="text" class="form-control input-inline input-medium" placeholder="名称" autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        <span class="required" aria-required="true"> * </span> 地址:
                                    </label>
                                    <div class="col-md-8">
                                        <input name="addr" type="text" class="form-control input-inline input-medium" placeholder="地址">
                                    </div>
                                </div>
                                <input type="hidden" name="id" />
                                <input type="hidden" name="url" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal"> 取消 </button>
                        <button type="submit" class="btn green" id="button-update"> 保存 </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade bs-modal-sm" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title"> 删除 </h4>
                    </div>
                    <div class="modal-body"> 确认删除吗？
                        <form class="form-horizontal" role="form" id="form-delete">
                            <div class="form-body">
                                <input type="hidden" name="id" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal"> 取消 </button>
                        <button type="submit" class="btn red" id="button-delete"> 删除 </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>
</div>
{/block}

{block name="footer"}
    <script type="text/javascript" src="/public/metronic/global/scripts/datatable.js"></script>
    <script type="text/javascript" src="/public/metronic/global/plugins/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="/public/metronic/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#dt-system').DataTable({
                'paging':   true,
                "pageLength": 20,
                'ordering': false,
                'lengthChange': false,
                'searching': false,
                // 'info': false,
                'processing': true,
                'language': {
                    'processing': '正在加载...',
                },
                'serverSide': true,
                'ajax': {
                    'url': '/system/show',
                    'type': 'get',
                    'dataSrc': function(json) {
                        var html = $('#new_column').html();
                        for (let i = 0, len = json.data.length; i < len; i++) {
                            json.data[i]['opts'] = html;
                        }
                        return json.data;
                    },
                },
                'columns': [
                    {'data': 'id'},
                    {'data': 'name'},
                    {'data': 'url'},
                    {'data': 'opts'},
                ],
                'columnDefs': [{
                    'targets': -1,
                    'render': function (data, type, row, meta) {
                        return '' +
                        '<a class="btn btn-sm blue" data-id="' + row.id + '" data-toggle="modal" data-title="编辑" href="#modal-update"> 编辑 <i class="fa fa-edit"></i></a>' +
                        '<a class="btn btn-sm red" data-id="' + row.id + '" data-toggle="modal" href="#modal-delete"> 删除 <i class="fa fa-times"></i></a>';
                    },
                }],
            });

            $('#modal-update').on('show.bs.modal', function (event) {
                var $target = $(event.relatedTarget);
                var title = $target.data('title');
                var id = $target.data('id') || 0;
                var url = id ? '/system/update' : '/system/add';

                var $modal = $(this);
                var $form = $('#form-update');
                $modal.find('.modal-title').text(title);
                $form.find('[name="id"]').val(id);
                $form.find('[name="url"]').val(url);
                $form.find('[autofocus]').focus();
                if (id) {
                    var data = table.row($target.parents('tr')).data();
                    $form.find('[name="name"]').val(data.name);
                    $form.find('[name="addr"]').val(data.url);
                }
            });

            // modal隐藏时清空表单
            $('#modal-update').on('hide.bs.modal', function (event) {
                $('#form-update').trigger('reset');
            });

            // 编辑
            $('#button-update').on('click', function() {
                var $form = $('#form-update');

                var url = $form.find('[name="url"]').val();
                var data = $form.serialize();
                $.post(url, data, function(data, textStatus, jqXHR) {
                    if (data && !data.code) {
                        $('#modal-update').modal('hide');
                        table.ajax.reload();
                    } else {
                        alert(data.message);
                    }
                });
            });

            $('#modal-delete').on('show.bs.modal', function (event) {
                var $target = $(event.relatedTarget);
                var $form = $('#form-delete');

                var data = table.row($target.parents('tr')).data();
                $(this).find('.modal-title').text('删除' + data.name);
                $form.find('[name="id"]').val(data.id);
            });

            // 删除
            $('#button-delete').on('click', function() {
                var data = $('#form-delete').serialize();
                $.post('/system/delete', data, function(data, textStatus, jqXHR) {
                    if (data && !data.code) {
                        $('#modal-delete').modal('hide');
                        table.ajax.reload();
                    } else {
                        alert(data.message);
                    }
                });
            });

        });
    </script>
{/block}
