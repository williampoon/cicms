<link rel="stylesheet" href="<?=asset('/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- <link rel="stylesheet" href="<?=asset('/plugins/datatables/jquery.dataTables.css')?>"> -->

<div class="box box-default collapsed-box" style="margin-bottom: 0">
  <div class="box-header with-border">
    <h3 class="box-title">菜单管理</h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <button type="button" class="btn btn-info" onclick="add()">添加</button>
  </div>
  <!-- /.box-body -->
  <div class="box-footer">
    <form action="#" method="post">
      <div class="input-group">
        <input type="text" name="message" placeholder="Type Message ..." class="form-control">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary btn-flat">Send</button>
            </span>
      </div>
    </form>
  </div>
  <!-- /.box-footer-->
</div>

<div class="box box-default">
  <!-- <div class="box-header with-border">
    <h3 class="box-title">菜单管理</h3>
    <div class="box-tools pull-right">
      Buttons, labels, and many other things can be placed here!
      <button type="button" class="btn btn-info" onclick="add()">添加</button>
    </div>
  </div> -->
  <div class="box-body" style="width: 100%;">
    <table id="nodes" class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
            <th>排序</th>
            <th>名称</th>
            <th>URL</th>
            <th>图标</th>
            <th>备注</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- <div class="box-footer">
  </div> -->
</div>

<!-- 添加/编辑框 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">菜单</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="nodeForm">
          <div class="box-body">
            <div class="form-group">
              <label class="col-sm-2 control-label">父菜单：</label>
              <div class="col-sm-8">
                <select name="pid" class="form-control">
                  <option value="1">根节点</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">名称：</label>
              <div class="col-sm-8">
                <input type="text" name="name" class="form-control" data-validation="required" placeholder="">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">URL：</label>
              <div class="col-sm-8">
                <input type="text" name="url" class="form-control" data-validation="length" data-validation-length="1-255" placeholder="">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">图标：</label>
              <div class="col-sm-8">
                <input type="text" name="icon" class="form-control" placeholder="">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">状态：</label>
              <label class="col-sm-2"><input type="radio" name="status" value="1"> 启用</label>
              <label class="col-sm-2"><input type="radio" name="status" value="0"> 禁用</label>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">排序：</label>
              <div class="col-sm-8">
                <input type="text" name="sort" class="form-control" value="0" placeholder="">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">备注：</label>
              <div class="col-sm-8">
                <input type="text" name="comment" class="form-control" placeholder="">
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <input type="hidden" name="id" value="" />
            <input type="hidden" id="type" value="" />
            <button type="button" id="cancel" class="btn btn-default pull-right" style="margin: 0 10px;" data-dismiss="modal">取消</button>
            <button type="submit" id="submit" class="btn btn-info pull-right">保存</button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
  </div>
</div>

<script src="<?=asset('/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=asset('/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<script src="<?=asset('/plugins/formvalidator/jquery.form-validator.min.js')?>"></script>

<script type="text/javascript">
  function node_prefix(pid, level) {
    let str = '';
    if (pid == 0) {
      return str;
    }

    let space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    for (var i = 3; i < level; i++) {
      str += space;
    }
    str += space;

    return str;
  }

  var table = $('#nodes').DataTable({
    dom: '',
    select: false,
    searching: false,
    ordering: false,
    paging: false,
    autoWidth: false,
    language: {
      emptyTable: '暂无菜单',
      loadingRecords: '数据加载中',
    },
    ajax: '/node/tree',
    columns: [
      {data: "sort"},
      {
        data: "name",
        render: function(data, type, row, meta) {
          let prefix = row.children ? '<i class="fa fa-fw fa-plus-square-o toggle" data-status="1"></i>' : '<i class="fa fa-fw"></i>';
          let icon = '<i class="' + (row.icon ? row.icon : 'fa fa-link') + '"></i>&nbsp;';
          return prefix + node_prefix(row.pid, row.level) + data;
        }
      },
      {
        data: "url",
        render: function(data, type, row, meta) {
          var raw = data;
          if (data.length > 50) {
            data = data.substring(0, 50) + '......';
          }
          return '<span title="' + raw + '">' + data + '</span>';
        }
      },
      {
        data: "icon",
        render: function(data, type, row, meta) {
          return '<i class="' + data + '"></i>';
        }
      },
      {data: "comment"},
      {
        data: "status",
        render: function(data, type, row, meta) {
          if (data == 0) {
            return '<span class="label label-default">禁用</span>';
          } else {
            return '<span class="label label-success">启用</span>';
          }
        }
      },
      {
        // targets: -1,
        data: null,
        render: function(data, type, row, meta) {
          if (row.pid == 0) {
            return '';
          }
          return '<button type="button" class="btn btn-xs btn-info" onclick="edit(this)">编辑</button> '
            + '<button class="btn btn-xs btn-danger" onclick="del(this)">删除</button>';
        }
      }
    ],
    initComplete: function(e, settings) {
      // 添加/编辑框内的父菜单
      $('select[name="pid"]').children('option').remove();
      var nodes = table.ajax.json().data;
      for (var i = 0; i < nodes.length; i++) {
        var node = nodes[i];
        $('select[name="pid"]').append('<option value="' + node.id + '">' + node_prefix(node.pid, node.level + 1) + node.name + '</option>');
      }

      /*var api = this.api();
      api.$('i.toggle').click(function() {
        console.log(api.row(this).data());
      });*/
    }
  });

  // 处理展开/收缩
  $('#nodes tbody').on('click', 'i.toggle', function() {
    let td = $(this).parent('td');
    let cell = table.cell(td).index();
    let click_row = table.row(cell.row).data();
    let status = $(this).data('status');
    console.log(status);

    for (let i = cell.row + 1; i < table.data().length; i++) {
      let row = table.row(i);
      let cur_row = row.data();
      let cur_node = row.node();
      // console.log(cur_row.level, click_row.level);return;
      // 遇到同级节点或者到达边界就返回
      if (cur_row.level == click_row.level || !row) {
        return;
      }
      if (status) {
        // 隐藏子节点
        if (cur_row.level > click_row.level) {
          $(cur_node).hide();
          $(cur_node).data('status', 0);
        }
      } else {
        // 显示子节点
        if (cur_row.level == click_row.level + 1) {
          $(cur_node).show();
          $(cur_node).data('status', 1);
        }
      }
    }

    $(this).data('status', status == 1 ? 0 : 1);
  });

  /*table.on('draw', function (e, settings) {
    $('select[name="pid"]').children('option').remove();

    var nodes = table.ajax.json().data;
    for (var i = 0; i < nodes.length; i++) {
      var node = nodes[i];
      $('select[name="pid"]').append('<option value="' + node.id + '">' + node_prefix(node.pid, node.level + 1) + node.name + '</option>');
    }
  });*/

  // 添加
  function add() {
    document.getElementById("nodeForm").reset()

    $('#myModalLabel').text('添加');
    $('#type').val('add');
    $('input[name="id"]').val(0);
    $('input[name="status"][value=1]').prop('checked', true);
    $('select[name="pid"]').children().removeAttr('disabled');

    $('#myModal').modal('show');
  }

  // 编辑
  function edit(obj) {
    document.getElementById("nodeForm").reset()

    $('#myModalLabel').text('编辑');
    $('#type').val('edit');
    var data = table.row($(obj).parents('tr')).data();
    $('select[name="pid"]').val(data.pid);
    $('select[name="pid"]').children().removeAttr('disabled');
    $('select[name="pid"]').children('option[value=' + data.pid + ']').prop('disabled', true);
    $('input[name="id"]').val(data.id);
    $('input[name="name"]').val(data.name);
    $('input[name="url"]').val(data.url);
    $('input[name="icon"]').val(data.icon);
    // $('input[name="status"]').val([data.status]);
    $('input[name="status"][value=' + data.status + ']').prop('checked', true);
    $('input[name="sort"]').val(data.sort);
    $('input[name="comment"]').val(data.comment);

    $('#myModal').modal('show');
  }

  // 删除
  function del(obj) {
    var data = table.row($(obj).parents('tr')).data();
    if (!confirm('确认删除：[' + data.name + ']？')) {
      return ;
    }

    $.ajax('/node/del', {
      type: 'post',
      data: {id: data.id},
      dataType: 'json',
      cache: false,
      complete: function(xhr, text) {
        var data = parseAjaxComplete(xhr);
        if (data === false) {
          return;
        }

        table.ajax.reload();
      },
    });
  }

  $.validate({
    form: '#nodeForm',
    onSuccess: function($form) {
      var type = $('#type').val();
      var url = type == 'add' ? '/node/add' : '/node/edit';

      $.ajax(url, {
        type: 'post',
        data: $form.serialize(),
        dataType: 'json',
        cache: false,
        complete: function(xhr, text) {
          var data = parseAjaxComplete(xhr);
          if (data === false) {
            return;
          }

          $('#myModal').modal('hide');
          table.ajax.reload();
        },
      });

      return false;
    }
  });
</script>