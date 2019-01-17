
<div class="box box-info">
  <div class="box-header with-border">
    <button type="button" class="btn btn-info" onclick="add()">添加</button>
    <!-- <h3 class="box-title">菜单管理</h3>
    <div class="box-tools pull-right">
      Buttons, labels, and many other things can be placed here!
      <button type="button" class="btn btn-info" onclick="add()">添加</button>
    </div> -->
  </div>
  <div class="box-body" style="width: 100%;">
    <table id="nodes" class="table">
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
  <div class="box-footer">

  </div>
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
                  <option value="0">/</option>
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
    if (pid == 0) {
      return '';
    }

    var str = '';
    for (var i = 2; i < level; i++) {
      str += '&nbsp;&nbsp;&nbsp;│&nbsp;';
    }
    str += '&nbsp;&nbsp;&nbsp;├─&nbsp;';

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
    columnDefs: [
      {targets: 0, data: "sort"},
      {
        targets: 1,
        data: "name",
        render: function(data, type, row, meta) {
          return node_prefix(row.pid, row.level) + data;
        }
      },
      {
        targets: 2, 
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
        targets: 3, 
        data: "icon",
        render: function(data, type, row, meta) {
          return '<i class="' + data + '"></i>';
        }
      },
      {targets: 4, data: "comment"},
      {
        targets: 5,
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
        targets: -1,
        data: null,
        render: function(data, type, row, meta) {
          return '<button type="button" class="btn btn-xs btn-info" onclick="edit(this)">编辑</button> '
            + '<button class="btn btn-xs btn-danger">删除</button>';
        }
      }
    ],
  });

  table.on('draw', function (e, settings) {
    $('select[name="pid"]').children('option[value!=0]').remove();

    var nodes = table.ajax.json().data;
    for (var i = 0; i < nodes.length; i++) {
      var node = nodes[i];
      $('select[name="pid"]').append('<option value="' + node.id + '">' + node_prefix(node.pid, node.level) + node.name + '</option>');
    }
  });

  /*$('#nodes tbody').on('click', 'button', function() {
    var data = table.row($(this).parents('tr')).data();
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
    // $('select[name="pid"]').children('option[value=' + data.pid + ']').prop('disabled', true);
    $('select[name="pid"]').children('option[value=' + data.id + ']').prop('disabled', true);
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

  // 解析AJAX complete返回的结果
  function parseAjaxComplete(xhr) {
    // 网络错误
    if (xhr.status == 0) {
      alert('网络错误');
      return false;
    }
    // 请求失败
    else if (xhr.status != 200) {
      alert(xhr.statusText);
      return false;
    }

    var data = xhr.responseJSON;
    // 自定义错误
    if (!data || data.code != 0) {
      alert(data.message);
      return false;
    }

    return data.data;
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
