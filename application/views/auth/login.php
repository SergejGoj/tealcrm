



<div class="company-wrapper">

    <div class="company-body">

      <h3>Welcome back to TealCRM.</h3>

      <h5>Please sign in to get access.</h5>

      <?php if (! empty($message)) : ?>
      <div id="message">
        <?php echo $message; ?>
      </div>
      <?php endif; ?>

      <form class="form company-form" method="POST" name="frmauth" id="frmauth" action="<?php echo current_url()?>">

        <div class="form-group">
          <label for="login_identity" class="placeholder-hidden">Username</label>
          <input type="text" class="form-control" id="identity" name="login_identity" placeholder="Username or E-mail" tabindex="1"autofocus>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <label for="login_password" class="placeholder-hidden">Password</label>
          <input type="password" class="form-control" id="password" name="login_password" placeholder="Password" tabindex="2">
        </div> <!-- /.form-group -->

        <?php if (isset($captcha)) :?>
          <div class="form-group">
              <label for="login-captcha" class="placeholder-hidden">Validate</label>
              <?php echo $captcha;?>
          </div> <!-- /.form-group -->
        <?php endif;?>

        <div class="form-group clearfix">
          <div class="pull-left">
            <label class="checkbox-inline">
            <input type="checkbox" class="" id="remember_me" name="remember_me" value="1" tabindex="3"> <small>Remember me</small>
            </label>
          </div>

          <div class="pull-right">
            <small><a href="<?php echo site_url('auth/forgotten_password')?>">Forgot Password?</a></small>
          </div>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-lg" tabindex="4" style="background-color:#297979">
            Sign in &nbsp; <i class="fa fa-play-circle"></i>
          </button>
        </div> <!-- /.form-group -->

        <input type="hidden" name="act" value="login_user">

      </form>


    </div> <!-- /.company-body -->

    <div class="company-footer">
    </div> <!-- /.company-footer -->

  </div> <!-- /.company-wrapper -->

  <script type="text/javascript">
  // document ready
  jQuery(document).ready(function(){
    var validator = jQuery("#frmauth").validate({
      rules: {
        login_identity: "required",
        login_password: "required"
      },
      messages: {
        login_identity: "Enter Username or E-mail",
        login_password: "Enter Password"
      },
      errorPlacement: function(error, element) {
        error.insertAfter(element.parent().find('label:first'));
      },
      errorElement: 'em',
      errorClass: 'login_error'
    });
  });
</script>