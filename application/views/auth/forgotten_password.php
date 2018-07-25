<div class="company-wrapper">

    <div class="company-body">

      <h2>Password Reset</h2>

      <h5>We'll email you instructions on how to reset your password.</h5>

      <?php if (! empty($message)) : ?>
      <div id="message">
        <?php echo $message; ?>
      </div>
      <?php endif; ?>

      <form class="form company-form" method="POST" name="frmauth" id="frmauth" action="<?php echo current_url()?>">

        <div class="form-group">
          <label for="forgot_password_identity" class="placeholder-hidden">Your Email</label>
          <input type="text" class="form-control" id="identity" name="forgot_password_identity" placeholder="Username or E-mail" tabindex="1">
        </div> <!-- /.form-group -->

        <?php if (isset($captcha)) :?>
          <div class="form-group">
              <label for="login-captcha" class="placeholder-hidden">Validate</label>
              <?php echo $captcha;?>
          </div> <!-- /.form-group -->
        <?php endif;?>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary btn-block btn-lg" tabindex="2">
            Reset Password &nbsp; <i class="fa fa-refresh"></i>
          </button>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <a href="<?php echo site_url('auth/login')?>"><i class="fa fa-angle-double-left"></i> &nbsp;Back to Login</a>
        </div> <!-- /.form-group -->

        <input type="hidden" name="act" value="reset_password">
      </form>

    </div> <!-- /.company-body -->

  </div> <!-- /.company-wrapper -->

  <script type="text/javascript">
  // document ready
  jQuery(document).ready(function(){
    var validator = jQuery("#frmauth").validate({
      rules: {
        forgot_password_identity: "required"
      },
      messages: {
        forgot_password_identity: "Enter Username or E-mail"
      },
      errorPlacement: function(error, element) {
        error.insertAfter(element.parent().find('label:first'));
      },
      errorElement: 'em',
      errorClass: 'login_error'
    });
  });
</script>