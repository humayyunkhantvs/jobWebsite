$(document).ready(function(){
    $('#addteamform').on('submit', function(e){
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
                    swal(data.message);
                    $('#add_team').modal('hide');
                    $('#teams_table').DataTable().ajax.reload();
                $('#teams_table').DataTable().ajax.reload();

                }
                if(data.errors){
                errors(data.errors)
                }
            }
    })
})
})

$(document).on('click', '.editteam', function(){
    let btn_object = $(this);
    let url = btn_object.attr('rel'); 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.ajax({
        url: url,
        type: "GET",
        dataType: 'json',
        success: function(response){
            console.log(response);
            if(response.result =='SUCCESS'){
                $('#edit_team').modal('show');
                $('#edit_team #team_id').val(response.data.id);
                $('#edit_team #name').val(response.data.name);
                $('#edit_team #email').val(response.data.email);
             
            }
        }
    })
})


$(document).on('submit', '#updateteamform', function(e){
     e.preventDefault();
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
            swal(data.message);
            $('#edit_team').modal('hide');
            $('#teams_table').DataTable().ajax.reload();
            }
            if(data.errors){
            errors(data.errors)
            }
        }

    })
})

// delete job

$(document).on('click', '.deleteteam', function(){
    let btn_object = $(this);
    let url = btn_object.attr('rel');

    Swal.fire({
        title: 'Are you sure you want to delete this sub account?',
        text: 'You will not be able to recover this sub account!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            let token = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(data){
                    if(data.response == true){
                        alert(data.message);
                        $('#teams_table').DataTable().ajax.reload();
                    }
                }
            });
        }
    });
});


// $(document).on('click', '.deleteteam', function(){
//     let btn_object = $(this);
//     let url = btn_object.attr('rel');

//     let token = $('meta[name="csrf-token"]').attr('content');
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': token
//         }
//     });
//     $.ajax({
//         url: url,
//         type: "GET",
//         dataType: 'json',
//         success: function(data){
//             if(data.response == true){
//                 alert(data.message);
//                 $('#teams_table').DataTable().ajax.reload();
//             }
//         }
//     })
// })

//update password
$(document).on('submit', '#reset1', function(e){
    e.preventDefault();
    alert("1213")
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
           if(data.response === true){
           swal(data.resp_msg);
           }
           if(data.errors){
           errors(data.errors_messages)
           }
       }

   })
})





function sendResetLink(email) {
    // Prevent the default action (i.e., page reload)
    event.preventDefault();
    let token = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': token
        }
    });
    // Make an AJAX request
    $.ajax({
        url: '/forgot_password_reset',
        method: 'POST',
        data: { email: email },
        success: function(response) {
            // Handle the success response
            console.log(response);
            // Show a success alert (you might want to customize this part)
            swal('Reset link sent to ' + email);
        },
        error: function(error) {
            // Handle the error response
            console.error(error);
        }
    });
}


// datatable js starts
$('#teams_table').dataTable({
    processing: true,
    serverSide: true,
    ordering: true,
    searching: true,
    ajax: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/team_datatable',
        type: 'POST',
        data: function(d){
            d._token = $("input[name=_token]").val();
            // d.category = $("#category_filter").val();
        }
    },
    columns: [
        { data: 'name' }, 
        { data: 'email' },
        { data: 'reset_password'},
        { data: 'action'}

    ],
    columnDefs: [
        { targets: [1], orderable: false},
        { targets: [1], searchable: false},
    ],
    order: [
        [0, 'DESC']
    ]
});
//errors function
function errors(arr = ''){
    $.each(arr, function( key, value ) {
    $('.'+key+'_error').html(value[0]);
    });
    return false;
}