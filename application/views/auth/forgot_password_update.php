<div class="company-wrapper">

    <div class="company-body">

      <h2>Password Reset</h2>

      <h5>Enter your new password twice below and then you will be prompted to login again.</h5>

      <?php if (! empty($message)) : ?>
      <div id="message">
        <?php echo $message; ?>
      </div>
      <?php endif; ?>

      <form class="form company-form" method="POST" name="frmauth" id="frmauth" action="<?php echo current_url()?>">

        <div class="form-group">
          <label for="new_password" class="placeholder-hidden">New Password</label>
          <input type="text" class="form-control" id="new_password" name="new_password" placeholder="New Password" tabindex="1">
        </div> <!-- /.form-group -->

        <div class="form-group">
          <label for="confirm_new_password" class="placeholder-hidden">Your Email</label>
          <input type="text" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm New Password" tabindex="2">
        </div> <!-- /.form-group -->

        <?php if (isset($captcha)) :?>
          <div class="form-group">
              <label for="login-captcha" class="placeholder-hidden">Validate</label>
              <?php echo $captcha;?>
          </div> <!-- /.form-group -->
        <?php endif;?>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary btn-block btn-lg" tabindex="3">
            Reset Password &nbsp; <i class="fa fa-refresh"></i>
          </button>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <a href="<?php echo site_url('auth/login')?>"><i class="fa fa-angle-double-left"></i> &nbsp;Back to Login</a>
        </div> <!-- /.form-group -->

        <input type="hidden" name="act" value="change_forgotten_password">
      </form>

    </div> <!-- /.company-body -->

  </div> <!-- /.company-wrapper -->

  <script type="text/javascript">
  // document ready
  jQuery(document).ready(function(){
    var validator = jQuery("#frmauth").validate({
      rules: {
        new_password: {required: true, rangelength: [8,16]},
        confirm_new_password: {required : true, equalTo: '#new_password'},
      },
      messages: {
        new_password: {required: "Enter new password", rangelength: $.validator.format("Please enter a password between {0} and {1} characters long.")},
        confirm_new_password: {required :"Confirm password", equalTo: 'Confirm new password does not match password'},
      },
      errorPlacement: function(error, element) {
        error.insertAfter(element.parent().find('label:first'));
      },
      errorElement: 'em',
      errorClass: 'login_error'
    });
  });
</script>