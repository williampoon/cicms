{extends file="admin"}

{block name="title"}{/block}

{block name="header"}{/block}

{block name="content"}
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Horizontal Form</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal">
      <div class="box-body">
        <div class="form-group">
          <label class="col-sm-2 control-label">父节点：</label>
          <div class="col-sm-8">
            <select class="form-control">
              <option value="0">/</option>
              <?php if(is_array($nodes)) { foreach($nodes as $item) {?>
              <option value="<?=$item['id']?>"><?=$item['name']?></option>
              <?php }}?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">名称：</label>
          <div class="col-sm-8">
            <input type="email" name="name" class="form-control" placeholder="">
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-2 control-label">URL：</label>
          <div class="col-sm-8">
            <input type="password" name="url" class="form-control" placeholder="">
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-2 control-label">图标：</label>
          <div class="col-sm-8">
            <input type="password" name="icon" class="form-control" placeholder="">
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-2 control-label">状态：</label>
          <label class="col-sm-1"><input type="radio" name="status" value="0" checked="">禁用</label>
          <label class="col-sm-1"><input type="radio" name="status" value="1" checked="checked">启用</label>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-2 control-label">备注：</label>
          <div class="col-sm-8">
            <input type="password" name="comment" class="form-control" placeholder="">
          </div>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <button type="submit" class="btn btn-info pull-right">保存</button>
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
</div>
{/block}

{block name="footer"}
{/block}
