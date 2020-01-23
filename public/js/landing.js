
$(function(){
  $("form[name='mobile_recharge_frm']").submit(function(e){
        e.preventDefault();
    });
	$("form[name='mobile_recharge_frm']").validate({
      rules: {
        recharge_mobile_number:{
          required:true,
          number:true,
          minlength: 10
        },
        recharge_mobile_operator:{
          required: true         
        },
        recharge_mobile_amount:{
          required: true,
          number: true,
          digits: true
        }
      },      
      messages: {       
                
        },
      submitHandler: function(form) {              
        var formData=$("form[name='mobile_recharge_frm']").serialize();
         $.ajax({
          type:'POST',
          url: $( '#mobile_recharge_frm' ).attr('action'),
          data:formData,          
          success:function(response){           
            if(response.status){               
               messagealert('Success',response.message,'success');
               $('#mobile_recharge_frm').reset();              
            }else{
               messagealert('Error',response.message,'error');
            }
          },
          error: function () {            
            messagealert('Error','Technicle issue , Please try later','error');
          }
        });
      }
    });  
});