<?php $this->load->view('header');?>
<div class='mainInfo'>

      <h2><?php echo $title ?></h2>
      
      <div id="infoMessage"><?php if(!empty($message)) echo $message;?></div>
      
    <?php echo form_open('auth/' . $action . '_user');?>

      <div class="form-group">
            <label>First Name:</label>
            <?php echo form_input($first_name, $first_name, ' class="form-control"');?>
      </div>
      
      <div class="form-group">
            <label>Last Name:</label>
            <?php echo form_input($last_name, $last_name, ' class="form-control"');?>
      </div>

      <div class="form-group">
            <label>Company Name:</label>
            <?php echo form_input($company, $company, ' class="form-control"');?>
      </div>

      <div class="form-group">
            <label>Email:</label>
            <?php echo form_input($email, $email, ' class="form-control"');?>
      </div>

      <?php if ($action == 'create'): ?>
            <div class="form-group">
                  <label>Password:</label>
                  <?php echo form_input($password, $password, ' class="form-control"');?>
            </div>

            <div class="form-group">
                  <label>Confirm Password:</label>
                  <?php echo form_input($password_confirm, $password_confirm, ' class="form-control"');?>
            </div>
      <?php endif; ?>
                       
      <div class="form-group">
            <?php echo form_submit('submit', $title);?>
      </div>

      
    <?php echo form_close();?>

</div>
<?php $this->load->view('footer');?>