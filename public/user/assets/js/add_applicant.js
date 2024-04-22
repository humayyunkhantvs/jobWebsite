$(document).ready(function(){
    $(document).on('submit', '#applyjobForm', function(e){
        e.preventDefault();
    
        $('.all_errors').empty();
        var obj = $(this);
        var formData = new FormData(this);

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
        $.ajax({
            url: "http://127.0.0.1:8000/api/apply_job",
            type: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(data){
                if(data.response == true){
                swal(data.message);
                $('#apply_job').modal('hide');
                $('.modal-backdrop').remove();
                // $('#apply_job').modal('hide', { backdrop: 'static', keyboard: false });
                }
                if(data.errors){
                errors(data.errors)
                }
            }
    })
})

$(document).on('click', '.bck_btn', function(e){
  e.preventDefault();
  $("#details-sectn").hide();
  $("#job-filters").show();
  $("#job-posting").show();

})

    //errors function
    function errors(arr = ''){
      $.each(arr, function( key, value ) {
      $('.'+key+'_error').html(value[0]);
      });
      return false;
  }
})