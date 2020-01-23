$(function(){
    //alert(123);
    homecategory();
    function homecategory(){
      var pageUrl=$('#pageUrl').val();
      $.ajax({
        type:'GET',
        url:pageUrl+'page/homecategory',
        success:function(msg){
          if(msg!=''){
            $('#home-category').html('');
            $('#home-category').html(msg);
            
          }
        }
      });
    }
    jQuery.validator.addMethod("specialChars", function( value, element ) {
        var regex = new RegExp("^[0-9a-zA-Z \b]+$");
        var key = value;

        if (!regex.test(key)) {
           return false;
        }
        return true;
    }, "Special Character does not allow");
    $("form[name='sign-in-form']").validate({
    
      // Specify validation rules
      rules: {
        email:{
          required: true,
          email: true
        },
        password:{
          required: true
        }
      },
      // Specify validation error messages
      messages: { },
      submitHandler: function(form) {
        var data=$("form[name='sign-in-form']").serialize();
        do_customer_login(data);
      }
    });

    $("form[name='change-password-form']").validate({
    
      // Specify validation rules
      rules: {        
        current_password:{
          required: true
        },
        password:{
          minlength : 4,
          required: true
        },
        retype_password:{          
          required: true,
          minlength : 6,
          equalTo : "#cpassword"
        }
      },
      // Specify validation error messages
      messages: { },
      submitHandler: function(form) {
        var data=$("form[name='change-password-form']").serialize();       
        do_customer_password_change(data);
      }
    });

});

function do_customer_password_change(formData){ 
        $.ajax({
          type:'POST',
          url: $( '#change-password-form' ).attr('action'),
          data:formData,          
          success:function(msg){            
            var response=$.parseJSON(msg);
            if(response.error==0){
               $('#change-password-form')[0].reset();
               messagealert('Success',response.msg,'success');
               location.reload();
            }else{
               messagealert('Error',response.msg,'error');
            }
          },
          error: function () {            
            messagealert('Error','Technicle issue , Please try later','error');
          }
        });
}

function do_customer_login(formData){
	$.ajax({
          type:'POST',
          url:$( '#sign-in-form' ).attr('action'),
          data:formData,
          beforeSend:function(){
            $('#makeLogin').button('loading');
          },
          success:function(msg){
			 // console.log(msg);
            $('#makeLogin').button('reset');
            var response=$.parseJSON(msg);
            if(response.error==0){
                $('#register').modal('hide');
    			      $('#sign-in').modal('hide');
                $('#sign-in-form')[0].reset();
                messagealert('Success','Login Successfully','success');
				        location.reload();
				
            }else{
            	$('#makeLogin').button('reset');
                messagealert('Error','You have eneterd invalid email or password','error');
            }
          },
          error: function () {
            $('#makeLogin').button('reset');
            messagealert('Error','You have eneterd invalid email or password','error');
          }
        });
}
/**************************Customer Login Start***************************/
$(document).on('click','.make-sign-in',function(){
    $('#register').modal('hide');
    $('#f-pwd').modal('hide');
    $('#sign-in').modal('show');
});
/**************************Customer Login End*****************************/
/**************************Customer Register Start***************************/
$(document).on('click','.make-register',function(){
    $('#sign-in').modal('hide');
    $('#f-pwd').modal('hide');
    $('#register').modal('show');
});
$(function(){
	var pageUrl=$('#pageUrl').val();
	$("form[name='register-form']").validate({
        
      // Specify validation rules
      rules: {
        username:{
          required: true,
          specialChars: true
        },
        email:{
          required: true,
          email: true
        },
        phone:{
          required: true,
          number: true
        },
        first_name:{
          required: true
        },
        last_name:{
          required: true
        },
        gender:{
          required: true
        },
        password: {
            required: true,
            minlength : 6,
        },
        confirm_password: {
             required: true,
             minlength : 6,
             equalTo : "#password"
        }
      },
      // Specify validation error messages
      messages: {
        email:{
            remote: "This email id is already exist"
        },
        username:{
            remote: "This username is already exist"
        },
        phone:{
            remote: "This phone number is already exist"
        },
        password: {
            required: "Please provide a password.",
            minlength: "Your password must be at least 6 characters long."
          },
        cpassword: " Enter Confirm Password Same as Password",
      },
      submitHandler: function(form) {
        //form.submit();
        var data=$("form[name='register-form']").serialize();
        do_customer_register(data);
      }
    });
});

function do_customer_register(formData){
       
        var btnname='makeRegister';
        $.ajax({
          type:'POST',
          url: $( '#register-form' ).attr('action'),
          data:formData,
          beforeSend:function(){
            $('#'+btnname).button('loading');
          },
          success:function(msg){
            $('#'+btnname).button('reset');
            var response=$.parseJSON(msg);
            if(response.error==0){
               $('#register-form')[0].reset();
               messagealert('Success',response.msg,'success');
			   setTimeout(function(){ location.reload(); }, 3000);
               
            }else{
               messagealert('Error',response.msg,'error');
            }
          },
          error: function () {
            $('#'+btnname).button('reset');
            messagealert('Error','Technicle issue , Please try later','error');
          }
        });
      }
/**************************Customer Login End*****************************/
/**************************Customer Forget Password***************************/
$(document).on('click','.make-f-pwd',function(){
    $('#sign-in').modal('hide');
    $('#register').modal('hide');
    $('#f-pwd').modal('show');
});

$(function(){
	$("form[name='f-pwd-form']").validate({
    
      // Specify validation rules
      rules: {
        email:{
          required: true,
          email: true
        }
      },
      // Specify validation error messages
      messages: {
      },
      submitHandler: function(form) {
        var formData=$("form[name='f-pwd-form']").serialize();
        $("#errorMsgforgotpass").html("");
        $.ajax({
          type:'POST',
          url:$( '#f-pwd-form' ).attr('action'),
          dataType:'Json',
          data:formData,
          beforeSend:function(){
            $("#makefpwd").button('loading');
          },
          success:function(data){
            console.log(data);
            if(data.error==1){
              $("#errorMsgforgotpass").html(data.message);
            }else{
              $('#f-pwd').modal('hide');
              messagealert('Success',data.message,'success');
            }
          },
          complete:function(){
           $("#makefpwd").button('reset');
          }
        })
      }
    });
});
/**************************Customer Login End*****************************/
/**************************Customer Dashboard Start*****************************/
$(document).on('click','#p_info_click_enable',function(){
  $("input.p_info").attr("disabled", false);
  $(this).attr('id','p_info_click_disbale');
  $(this).text('Cancel');
  $("#profileEdit :input").prop("disabled", false);
  $("input[name=email]").prop("disabled", true);
  $("input[name=mobile]").prop("disabled", true);
  $("#profileEdit :select").prop("disabled", false);
  
});
$(document).on('click','#p_info_click_disbale',function(){
  $("input.p_info").attr("disabled", true);
  $(this).attr('id','p_info_click_enable');
  $(this).text('Edit');
  $("#profileEdit :input").prop("disabled", true);
  $("#profileEdit :select").prop("disabled", false);
});

$(document).on('click','#p_info_update',function(){
  var $this=$(this);
  var first_name = $('input[name="p_first_name"]').val();
  var last_name = $('input[name="p_last_name"]').val();
  var gender=$('input[name="p_gender"]:checked').val();
  if (typeof gender === 'undefined' || typeof last_name === 'undefined' || typeof first_name === 'undefined'){
    return false;
  }
  if (typeof gender === '' || typeof last_name === '' || typeof first_name === ''){
    return false;
  }
  var pageUrl=$('#pageUrl').val();
  $.ajax({
    type:'POST',
    url:pageUrl+'action/updateProfie',
    data:{'first_name':first_name,'last_name':last_name,'gender':gender,'submit':'submit'},
    beforeSend:function(){
      $this.button('loading');
    },
    success:function(msg){
      $this.button('reset');
      var response=$.parseJSON(msg);
      if(response.error==0){
         messagealert('Success',response.msg,'success');
      }else{
         messagealert('Error',response.msg,'error');
      }
    },
    error: function () {
      $this.button('reset');
      messagealert('Error','Technicle issue , Please try later','error');
    }
  });
});
/**************************Customer Dashboard End***************************/

/********************Login Check*****************************/

function loginCheck(){
  var pageUrl=$('#pageUrl').val();
  $.ajax({
    type:'POST',
    url:pageUrl+'product/logincheck',
    data:{},
    success:function(msg){
      if(msg=='false'){
        $('.make-sign-in').trigger('click');
      }
    },
    error: function () {
      
    }
  });
}

/********************Login Check End *************************/

/*************************WishList**************************************/
$(document).on('click','.dowishlist',function(){
  var $this=$(this);
  var product_id=$this.data('id');
  if(typeof product_id === 'undefined'){
    messagealert('Error',"This product has been not listed for long",'error');
    return false;
  }
  var status=1;
  if($this.hasClass('fa-heart-o')){
    status=0;
  }
  var pageUrl=$('#pageUrl').val();
  $.ajax({
    type:'POST',
    url:pageUrl+'product/dowishlist',
    data:{'status':status,'product_id':product_id},
    success:function(msg){
      var response=$.parseJSON(msg);
      if(response.error==0){
         $this.removeClass('fa-heart-o');
         $this.removeClass('fa-heart');
         $this.addClass(response.report);
         messagealert('Success',response.msg,'success');
      }else if(response.error==2){
        $('.make-sign-in').trigger('click');
      }else{
         messagealert('Error',response.msg,'error');
      }
    },
    error: function () {
      messagealert('Error','Technicle issue , Please try later','error');
    }
  });
});

$(document).on('click','.delWishlist',function(){
  if (confirm("Are you sure want to remove from wishlist?")) {
      var $this=$(this);
      var wishlist_id=$this.data('id');
      //alert(WishList_id);
      var pageUrl=$('#pageUrl').val();
      $.ajax({
        type:'POST',
        url:pageUrl+'product/deletewishlist',
        data:{'wishlist_id':wishlist_id},
        success:function(msg){
          var response=$.parseJSON(msg);
          if(response.error==0){
             location.reload();
          }else if(response.error==2){
            $('.make-sign-in').trigger('click');
          }else{
             messagealert('Error',response.msg,'error');
          }
        },
        error: function () {
          messagealert('Error','Technicle issue , Please try later','error');
        }
      });
  }
  return false;
});
/*************************End Wishlist**********************************/