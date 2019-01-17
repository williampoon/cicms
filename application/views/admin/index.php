{extends file="admin"}

{block name="title"}首页{/block}

{block name="header"}{/block}

{block name="content"}
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">服务器</h3>
    <div class="box-tools pull-right">
      <!-- Buttons, labels, and many other things can be placed here! -->
      <!-- Here is a label for example -->
      <span class="label label-primary">Label</span>
    </div>
  </div>
  <div class="box-body">
    <div class="row">
      <div class="col-sm-1">系统: </div>
      <div class="col-sm-11"><?=$info['system'];?></div>
      <div class="col-sm-1">服务器: </div>
      <div class="col-sm-11"><?=$info['server'];?></div>
      <div class="col-sm-1">数据库: </div>
      <div class="col-sm-11"><?=$info['database'];?></div>
      <div class="col-sm-1">PHP: </div>
      <div class="col-sm-11"><?=$info['php'];?></div>
    </div>
  </div>
  <div class="box-footer">

  </div>
</div>
{/block}
