<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>菜单管理</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"> -->
  <!-- <link rel="stylesheet" href="/src/fonts/font-awesome.min.css"> -->
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"> -->
  <!-- Theme style -->
  <!-- <link rel="stylesheet" href="/src/css/AdminLTE.min.css"> -->
  <!-- <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css"> -->
  <!-- <link rel="stylesheet" href="/src/css/skins/skin-black-light.min.css"> -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
<link rel="stylesheet" href="<?=asset('/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- <link rel="stylesheet" href="<?=asset('/plugins/datatables/jquery.dataTables.css')?>"> -->

  <script src="<?=asset('/dist/vendor.js')?>"></script>
</head>

<body class="hold-transition skin-black-light sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>CI</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin</b>CI</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the messages -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <!-- User Image -->
                        <img src="<?=asset('/src/img/user2-160x160.jpg')?>" class="img-circle" alt="User Image">
                      </div>
                      <!-- Message title and timestamp -->
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <!-- The message -->
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
                <!-- /.menu -->
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- /.messages-menu -->

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- Inner Menu: contains the notifications -->
                <ul class="menu">
                  <li><!-- start notification -->
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                  <!-- end notification -->
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks Menu -->
          <li class="dropdown tasks-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- Inner menu: contains the tasks -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <!-- Task title and progress text -->
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <!-- The progress bar -->
                      <div class="progress xs">
                        <!-- Change the css width attribute to simulate progress -->
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="<?=asset('/src/img/user2-160x160.jpg')?>" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">Alexander Pierce</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="<?=asset('/src/img/user2-160x160.jpg')?>" class="img-circle" alt="User Image">

                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="#" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?=asset('/src/img/user2-160x160.jpg')?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <?php if (is_array($tree)) {foreach ($tree as $node) {?>
        <li class="treeview">
          <a href="<?=empty($node['children']) ? $node['url'] : '#';?>">
            <i class="<?=$node['icon'] ? $node['icon'] : 'fa fa-link';?>"></i> <span><?=$node['name'];?></span>
            <?php if (!empty($node['children'])) {?>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            <?php }?>
          </a>
          <?php if (!empty($node['children'])) {?>
          <?=subtree($node['children']);?>
          <?php }?>
        </li>
        <?php }}?>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <h1>
        Page Header
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      
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
    <table id="nodes" class="table table-striped table-bordered">
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


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2016 <a href="#">Company</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="<?=asset('/plugins/jQuery/jquery-2.2.3.min.js')?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?=asset('/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?=asset('/src/js/common/config.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?=asset('/src/js/common/app.min.js')?>"></script>
<!-- <script src="<?=asset('/dist/vendor.js')?>"></script> -->
<!-- <script src="<?=asset('/dist/admin_index.js')?>"></script> -->
<!-- <script src="<?=asset('/src/js/common/jquery.pjax.js')?>"></script> -->
<script type="text/javascript">
$(function(){
  // $(document).pjax('.sidebar-menu a', 'section[class="content"]')
});
</script>

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

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
