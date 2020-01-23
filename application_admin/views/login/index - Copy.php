<script type="text/javascript">
$(document).ready(function($){
	$("#frm_login").validationEngine();
});
</script>

<div class="container">
  <form id="frm_login" class="form-signin" action="<?php echo $do_login;?>" method="post">
    <h2 class="form-signin-heading">sign in now</h2>
    <div class="login-wrap">
        <input type="text" id="username" name="username" class="form-control validate[required]" placeholder="User Name">
        <input type="password" id="password" name="password" class="form-control validate[required]" placeholder="Password">
        <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>

    </div>
  </form>
</div>
