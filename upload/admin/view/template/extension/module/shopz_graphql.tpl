<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-carousel" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error['permission'])){ ?>
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_permission; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } elseif($error && count($error)>0) {?>
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-message"><?php echo $entry_mobile_provider; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mobile_config_mobile_provider" value="<?php echo $mobile_config_mobile_provider; ?>" placeholder="<?php echo $entry_mobile_provider; ?>" id="input-message" class="form-control" />
              <?php if(isset($error['mobile_provider']) && !empty($error['mobile_provider'])){  ?>
                <div class="text-danger"><?php echo $error['mobile_provider']; ?></div>
                <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mobile_username"><?php echo $entry_mobile_username; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mobile_config_mobile_username" value="<?php echo $mobile_config_mobile_username; ?>" placeholder="<?php echo $entry_mobile_username; ?>" id="input-mobile_username" class="form-control" />
              <?php if(isset($error['mobile_username']) && !empty($error['mobile_username'])){  ?>
                <div class="text-danger"><?php echo $error['mobile_username']; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mobile_password"><?php echo $entry_mobile_password; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mobile_config_mobile_password" value="<?php echo $mobile_config_mobile_password; ?>" placeholder="<?php echo $entry_mobile_password; ?>" id="input-mobile_password" class="form-control" />
              <?php if(isset($error['mobile_password']) && !empty($error['mobile_password'])){  ?>
                <div class="text-danger"><?php echo $error['mobile_password']; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mobile_sendername"><?php echo $entry_mobile_sendername; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mobile_config_mobile_sendername" value="<?php echo $mobile_config_mobile_sendername; ?>" placeholder="<?php echo $entry_mobile_sendername; ?>" id="input-mobile_sendername" class="form-control" />
              <?php if(isset($error['mobile_sendername']) && !empty($error['mobile_sendername'])){  ?>
              <div class="text-danger"><?php echo $error['mobile_sendername']; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mobile_message_template_otp"><?php echo $entry_mobile_message_template_otp; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mobile_config_mobile_message_template_otp" value="<?php echo $mobile_config_mobile_message_template_otp; ?>" placeholder="<?php echo $entry_mobile_message_template_otp; ?>" id="input-mobile_message_template_otp" class="form-control" />
              <?php if(isset($error['mobile_message_template_otp']) && !empty($error['mobile_message_template_otp'])){  ?>
              <div class="text-danger"><?php echo $error['mobile_sendername']; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-message_template_forgot"><?php echo $entry_mobile_message_template_forgot; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mobile_config_mobile_message_template_forgot" value="<?php echo $mobile_config_mobile_message_template_forgot; ?>" placeholder="<?php echo $entry_mobile_message_template_forgot; ?>" id="input-mobile_message_template_forgot" class="form-control" />
              <?php if(isset($error['mobile_message_template_forgot']) && !empty($error['mobile_message_template_forgot'])){  ?>
              <div class="text-danger"><?php echo $error['mobile_message_template_forgot']; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="mobile_config_status" id="input-status" class="form-control">
                <?php if($mobile_config_status){ ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>