<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
<?php 
    if (isset($css_files)) {
        foreach($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
        <?php endforeach; 
     }
     if (isset($js_files)) {
         foreach($js_files as $file): ?>
    <script src="<?php echo $file; ?>"></script>
        <?php endforeach;
    } ?>
  <link rel="stylesheet" type="text/css" href="/assets/fms-endpoint/css/fms-endpoint.css" />
  <link rel="stylesheet" type="text/css" href="/assets/fms-endpoint/css/print.css" media="print" />

  <?php if (config_item('cobrand_name')) { ?>
  	<link rel="stylesheet" type="text/css" href="/assets/cobrands/<?php echo config_item('cobrand_name'); ?>/style.css" />
  <?php } ?>
</head>
<body id="fmse-admin">
  <div class="fmse-header">
    <h1>
      <?php echo config_item('organisation_name'); ?>
    </h1>
    <div class="fmse-nav">
      <ul>
        <?php if ($auth->logged_in()) { ?>
          <li><a href='<?php echo site_url('admin/')?>'>Home</a></li> 
          <li><a href='<?php echo site_url('admin/reports')?>'>Reports detail</a></li>
          <li><a href='<?php echo site_url('admin/reports_csv')?>'>Export CSV</a></li>
          <li><a href='<?php echo site_url('admin/request_updates')?>'>Updates</a></li>
          <li><a href='<?php echo site_url('admin/categories')?>'>Categories</a></li>
          <li><a href='<?php echo site_url('admin/category_attributes')?>'>Category Definitions</a></li>          
          <?php if ($auth->is_admin()) { ?>
            <li><a class="admin-link" href='<?php echo site_url('admin/statuses')?>'>Statuses</a></li>
            <li><a class="admin-link" href='<?php echo site_url('admin/settings')?>'>Settings</a></li>
            <li><a class="admin-link" href='<?php echo site_url('admin/api_keys')?>'>API keys</a></li>
            <li><a class="admin-link" href='<?php echo site_url('admin/open311_clients')?>'>Clients</a></li>
            <li><a class="admin-link" href='<?php echo site_url('auth/')?>'>Users</a></li>
          <?php } ?>
          <li id="current-user-nav"><?php if ($auth->is_admin()) {echo("&nbsp;admin&nbsp;");} ?><a class="user" href="<?php echo site_url('/auth/change_password')?>" title="change password"><?php echo $current_user_data->email ?></a><a href='<?php echo site_url('auth/logout')?>'>Logout</a>
          </li>
        <?php } else { ?>
          <li></li>
          <?php if ($this->uri->uri_string() != '/auth/login' ) { ?>
            <li style="float:right;"><a href='<?php echo site_url('auth/login')?>'>Login</a></li>
          <?php } ?>
        <?php }  ?>
		<li class="help-link">
	    	<a href='<?php echo site_url('admin/help')?>' class="fmse-mysoc">help</a>
		</li>
      </ul>
    </div>
  </div>
  <div class="fmse-content">
  	<?php if ($auth->logged_in() && config_item('announcement_html')) { ?>
		<div class="fmse-announcement">
			<?php echo config_item('announcement_html'); ?>
		</div>
	<?php } ?>
