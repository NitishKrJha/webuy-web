<h3 class="text-center m-t-0 m-b-15"> <a href="index.html" class="logo"><img src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/images/logo.png"></a></h3>
<h4 class="text-muted text-center m-t-0"><b>Sign In</b></h4>
<ul class="parsley-errors-list filled error" ><li class="parsley-required"><?php echo $errmsg;?></li></ul>

<form class="form-horizontal m-t-20" action="<?php echo $do_login;?>" method="post" name="loginForm" ng-app="loginApp" ng-controller="loginCntrl" novalidate>
    <div class="form-group">
        <div class="col-xs-12">
            <input class="form-control" type="text" name="username" class="form-control" placeholder="Username" ng-model="username" required>
            <?php echo form_error('email'); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12">
            <input type="password" name="password" class="form-control" placeholder="Password" ng-model="password" ng-minlength="5"  required/>
		  	<?php echo form_error('password'); ?>
        </div>
    </div>
    
    <div class="form-group text-center m-t-40">
        <div class="col-xs-12">
            <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit" ng-disabled="loginForm.username.$invalid || loginForm.password.$invalid">Log In</button>
        </div>
    </div>
    <div class="form-group m-t-30 m-b-0" style="display: none;">
        <div class="col-sm-7"> <a href="pages-recoverpw.html" class="text-muted"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a></div>
        <div class="col-sm-5 text-right"> <a href="pages-register.html" class="text-muted">Create an account</a></div>
    </div>
</form>
<script>
var app = angular.module('loginApp',[]);
app.controller('loginCntrl',function($scope){});
</script>
