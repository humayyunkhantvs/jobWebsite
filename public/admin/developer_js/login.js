$(document).ready(function(){
    $('#login-form').on('submit', function(e){
        e.preventDefault();
        $('.all_errors').empty();
        var obj = $(this);
        var formData = new FormData(this);
        $.ajax({   
            url: obj.attr('action'),
            type: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(data){
                if(data.response == true){
                  window.location.href = data.redirect;
                }
                if(data.errors){
                    // $('.error_div').html(data.message);
                    errors(data.errors);
                }
                if(data.wrong_errors){
                      $('.error_div').html(data.message);
                }

            }
          
            
        })
    })
})


//signup user
$(document).ready(function(){
    $('#signup_form').on('submit', function(e){
    e.preventDefault();
        $('.all_errors').empty();
        var obj = $(this);
        var formData = new FormData(this);
        $.ajax({
            url: obj.attr('action'),
            type: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(data){
                
                if(data.response == true){
               alert(data.message);
               window.location.href = data.redirect;
                }
                if(data.errors){
                errors(data.errors)
                }
            },
            error: function(error){
                console.log(error);
                errors(error.errors)
            }
        })
    })
})


//update profile
$(document).ready(function(){
    $('#updateuserform').on('submit', function(e){
        e.preventDefault();
        
        $('.all_errors').empty();
        var obj = $(this);
        var formData = new FormData(this);
        $.ajax({   
            url: obj.attr('action'),
            type: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(data){
                console.log(data)
                if(data.response == true){
                 swal(data.message)
                 $('#update_profile').modal('hide');
                 $('#updateuserform')[0].reset();
                }
                if(data.errors){
                  
                    errors(data.errors);
                }
                if(data.old_password_errors){
                      $('.error_div').html(data.old_password_errors);
                }

            }
          
            
        })
    })
})
//errors function
function errors(arr = ''){
$.each(arr, function( key, value ) {
$('.'+key+'_error').html(value[0]);
});
return false;
}