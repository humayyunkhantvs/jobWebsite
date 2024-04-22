    $(document).ready(function(){
       $('#addjobForm').on('submit', function(e){
                e.preventDefault();
            
                $('.all_errors').empty();
                var obj = $(this);
                var formData = new FormData(this);

                // Get the content of the description textarea
                // var description = $('#mytextarea').val();
                // Append the description to the formData
                // formData.append('description', description);
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
                        $("#addjobForm")[0].reset();
                        $('#add_job').modal('hide');
                        $('#jobs_table').DataTable().ajax.reload();
                        }
                        if(data.errors){
                        errors(data.errors)
                        }
                    }
            })
        })
    

        // edit job
        $(document).on('click', '.editjob', function(){
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
                        $('#edit_job').modal('show');
                        $('#edit_job #job_id').val(response.data.id);
                        $('#edit_job #edit_title').val(response.data.title);
                        $('#edit_job #edit_status option[value="'+response.data.job_status+'"]').prop('selected',true);
                        $('#edit_job #edit_type option[value="'+response.data.type+'"]').prop('selected',true);
                        $('#edit_job #edit_email').val(response.data.email);
                        $('#edit_job #edit_website').val(response.data.website);
                        $('#edit_job #edit_phone').val(response.data.phone);
                        $('#edit_job #edit_company').val(response.data.company);
                        $('#edit_job #edit_category_id option[value="'+response.data.category_id+'"]').prop('selected',true);
                        $('#edit_job #edit_country').val(response.data.country);
                        $('#edit_job #edit_address').val(response.data.address);
                        $('#edit_job #edit_city').val(response.data.city);
                        $('#edit_job #edit_state option[value="'+response.data.state+'"]').prop('selected',true);
                        $('#edit_job #edit_zip').val(response.data.zip);
                        $('#edit_job #edit_salary').val(response.data.salary);
                        $('#edit_job #edit_deadline').val(response.data.deadline);
                        $('#edit_job #edit_published_date').val(response.data.published_at);
                        $('#edit_job #edit_mytextarea').val(response.data.description);
                        tinymce.init({
                            selector: '#edit_mytextarea',
                            plugins: [
                                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                                'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
                                'media', 'table', 'emoticons', 'template', 'help'
                            ],
                            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                                'forecolor backcolor emoticons | help',
                        });
                        
                       
                        tinymce.get('edit_mytextarea').setContent(response.data.description);  
                    }
                }
            })
        })

        // updatejob

        $(document).on('submit', '#updatejobForm', function(e){
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
                    $("#updatejobForm")[0].reset();
                    $('#edit_job').modal('hide');
                    $('#jobs_table').DataTable().ajax.reload();
                    }
                    if(data.errors){
                    errors(data.errors)
                    }
                }
            })
        })
    

        // delete job
        $(document).on('click', '.deletejob', function(){
            let btn_object = $(this);
            let url = btn_object.attr('rel');

            Swal.fire({
                title: ' Are you sure you want to delete this job?',
                text: 'You will not be able to recover this job!',
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
                                swal(data.message);
                                $('#jobs_table').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });
        })

        $(document).ready(function(){
            $('#adminFilter').change(function(){
                var adminId = $(this).val();
                if(adminId != '') {
                    // AJAX request to fetch users associated with selected admin
                    $.ajax({
                        url: '/get-users-by-admin/' + adminId,
                        type: 'GET',
                        success: function(response) {
                            $('#userFilter').empty();
                            $('#userFilter').append('<option value="">All Users</option>');
                            $.each(response.users, function(index, user) {
                                $('#userFilter').append('<option value="' + user.id + '">' + user.name + '</option>');
                            });
                            $('#userFilterContainer').show(); // Show the user filter dropdown
                        }
                    });
                } else {
                    $('#userFilter').empty();
                    $('#userFilterContainer').hide(); // Hide the user filter dropdown if no admin is selected
                }
            });
            $('#adminFilter').change(function(){
                // Update jobs table when admin is selected
                $('#jobs_table').DataTable().ajax.reload();
            });
            // Update jobs table on user filter change
            $('#userFilter').change(function () {
                $('#jobs_table').DataTable().ajax.reload();
            });
        });
        
        
        
        // jobs datatable js starts
        $('#jobs_table').dataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/job_datatable',
                type: 'POST',
                data: function(d){
                    d._token = $("input[name=_token]").val();
                    // Add job status filter value
                    d.admin_id = $('#adminFilter').val();
                    d.user_id = $('#userFilter').val();
                     d.job_status = $('#jobFilterstatus').val();
                
                }
            },
            columns: [
                { data: 'title' },
                { data: 'description' },
                { data: 'published_at' },
                { data: 'company' },
                // { data: 'category' },
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

        $('#jobFilterstatus').change(function () {
            $('#jobs_table').DataTable().ajax.reload();
        });

        //categories section start

        //add category
        $(document).on('submit', '#addcategoryForm', function(e){
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
                        $("#addcategoryForm")[0].reset();
                        $('#add_category').modal('hide');
                        $('#categories_table').DataTable().ajax.reload();
                    }
                    if(data.errors){
                        errors(data.errors)
                    }
                }
            })

            
        })

        // edit category
        $(document).on('click', '.editcategory', function(){
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
                        $('#edit_category').modal('show');
                        $('#edit_category #category_id').val(response.data.id);
                        $('#edit_category #name').val(response.data.name);
                    
                    }
                }
            })
        })

        // update category
        $(document).on('submit', '#updatecategoryForm', function(e){
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
                        $("#updatecategoryForm")[0].reset();
                        $('#edit_category').modal('hide');
                        $('#categories_table').DataTable().ajax.reload();
                    }
                    if(data.errors){
                        errors(data.errors)
                    }
                }
            })
        })

        // delete category
        $(document).on('click', '.deletecategory', function(){
            let btn_object = $(this);
            let url = btn_object.attr('rel');
            
            Swal.fire({
                title: 'Are you sure you want to delete this category?',
                text: 'You will not be able to recover this category!',
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
                                swal(data.message); // Using swal() instead of Swal.fire()
                                $('#categories_table').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });
        });
        

        //category datatable js starts
        $('#categories_table').dataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/category_datatable',
                type: 'POST',
                data: function(d){
                    d._token = $("input[name=_token]").val();

                }
            },
            columns: [
                { data: 'name' },
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
    
        //template section start
        //add template
        $(document).on('submit', '#addtemplateForm', function(e){
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
                        $("#addtemplateForm")[0].reset();
                        $('#add_template').modal('hide');
                        $('#template_table').DataTable().ajax.reload();
                    }
                    if(data.errors){
                        errors(data.errors)
                    }
                }
            })

            
        })

        // edit template
        $(document).on('click', '.edittemplate', function(){
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
                        $('#edit_template').modal('show');
                        $('#edit_template #template_id').val(response.data.id);
                        $('#edit_template #edit_title').val(response.data.title);
                       // Get the dynamic base URL
                        var baseUrl = window.location.protocol + '//' + window.location.host;
                        var imageUrl = baseUrl + '/upload/' + response.data.image;
                        var currentImagePreview = $('#current_image_preview');
                        currentImagePreview.attr('src', imageUrl);
    
                        $('#edit_image').on('change', function () {
                            var newImageUrl = URL.createObjectURL(this.files[0]);
                            currentImagePreview.attr('src', newImageUrl);
                        });
                    }
                }
            })
        })

 


        // update template
        $(document).on('submit', '#updatetemplateForm', function(e){
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
                        $("#updatetemplateForm")[0].reset();
                        $('#edit_template').modal('hide');
                        $('#template_table').DataTable().ajax.reload();
                    }
                    if(data.errors){
                        errors(data.errors)
                    }
                }
            })
        })

        // delete template

        $(document).on('click', '.deletetemplate', function(){
            let btn_object = $(this);
            let url = btn_object.attr('rel');
        
            Swal.fire({
                title: 'Are you sure you want to delete this template?',
                text: 'You will not be able to recover this template!',
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
                                swal(data.message);
                                $('#template_table').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });
        });
        

        // $(document).on('click', '.deletetemplate', function(){
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
        //                 swal(data.message);
        //                 $('#template_table').DataTable().ajax.reload();
        //             }
        //         }
        //     })
        // })

        //template datatable js starts
        $('#template_table').dataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/template_datatable',
                type: 'POST',
                data: function(d){
                    d._token = $("input[name=_token]").val();

                }
            },
            columns: [
                { data: 'title' },
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
        //template section end
        // settings section start
        $('#addsettingForm').on('submit', function(e){
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
                    $("#addsettingsForm")[0].reset();
                    $('#add_settings').modal('hide');
                    $('#setting_table').DataTable().ajax.reload();
                    }
                    if(data.errors){
                    errors(data.errors)
                    }
                }
        })
        })

        //get Settings
      
          // update setting
          $(document).on('submit', '#updatesettingForm', function(e){
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
                       
                    }
                    if(data.response == false){
                        swal(data.message);
                       
                    }
                    if(data.errors){
                        errors(data.errors)
                    }
                }
            })
        })


        // Applicants datatable

        $('#applicants_table').dataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/applicants_datatable',
                type: 'POST',
                data: function(d){
                    d._token = $("input[name=_token]").val();
                   
                    d.category_id = $('#jobFilter').val();
                    // d.job_status = $('#jobStatusFilter').val();
                    // d.user_id = $('$userfilter').val();
                
                }
            },
            columns: [
                { data: 'first_name' },
                { data: 'last_name' },
                { data: 'email' },
                { data: 'action'},
        
            ],
            // columnDefs: [
            //     { targets: [1], orderable: false},
            //     { targets: [1], searchable: false},
            // ],
            order: [
                [0, 'DESC']
            ]
        }); 

        // Add change event for job filter
        $('#jobFilter').change(function () {
            $('#applicants_table').DataTable().ajax.reload();
        });

        //  // Handle click event for the "View" button
         $(document).on('click', '.view-applicant', function () {
            var applicant_url = $(this).attr("rel");

           
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  
                },
                url: applicant_url, 
                type: 'GET',
                success: function (response) {
                
                    // $('#applicantModal .modal-body').html(response);
                    $("#job_title").val(response.data.job_title);
                    $("#first_name").val(response.data.first_name);
                    $("#last_name").val(response.data.last_name);
                    $("#phone").val(response.data.phone);
                    $("#email").val(response.data.email);
                    $("#address").val(response.data.address);
                    $("#dob").val(response.data.dob);
                    $("#city").val(response.data.city);
                    $("#state").val(response.data.state);
                    $("#zip_code").val(response.data.zip_code);
                    $("#cover_letter").val(response.data.cover_letter);
                    // $("#cv").val(response.data.cv);
                    // $("#id").val(response.data.id);
                    // $("#update-description").val(response.description);
                    // Show the modal
                    // $('#applicantModal').modal('show');
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        });

        // $(document).ready(function() {
        //     $('#get_custom_field').submit(function(e) {
        //         e.preventDefault(); // Prevent the default form submission
                
        //         $.ajax({
        //             url: '/get_ghl_customfields',
        //             type: 'GET',
        //             dataType: 'json',
        //             success: function(response) {
        //                 console.log(response.message);
        //                 // Handle success response
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error('AJAX request failed:', status, error);
        //                 // Handle error
        //             }
        //         });
        //     });
        // });
        

        
        $('#save_custom_field').on('submit', function(e){  
            e.preventDefault();
        
            $('.all_errors').empty();
            var obj = $(this);
            var formData = new FormData(this);
            $.ajax({
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  
                // },
                url: obj.attr('action'),
                type: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data){
                    if(data.response == true){
                    swal(data.message);
                    // $("#save_custom_field")[0].reset();
                    $('#form_container').load(window.location.href + ' #save_custom_field');
                    // $('#add_settings').modal('hide');
                    // $('#setting_table').DataTable().ajax.reload();
                    }
                    if(data.error){
                        swal(data.error);
                    }
                    if(data.errors){
                    errors(data.errors)
                    }
                },
               
        })
        });
        

        // $(document).ready(function () {
        //     $('#downloadButton').click(function () {
        //         var imageId = 20; // Replace with the actual image ID
    
        //         $.ajax({
        //             url: '/download/' + imageId,+
        //             type: 'GET',
        //             success: function () {
        //                 // The image will be downloaded
        //                 alert('image downloaded');
        //             },
        //             error: function (xhr, status, error) {
        //                 console.error(xhr.responseText);
        //             }
        //         });
        //     });
        // });

         // delete setting
        //  $(document).on('click', '.deletesetting', function(){
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
        //                 swal(data.message);
        //                 $('#setting_table').DataTable().ajax.reload();
        //             }
        //         }
        //     })
        // })
       
        //settings section end
    
        //errors function
        function errors(arr = ''){
            $.each(arr, function( key, value ) {
            $('.'+key+'_error').html(value[0]);
            });
            return false;
        }
        //tinymce editor
        tinymce.init({
            selector: '#mytextarea',
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
                'media', 'table', 'emoticons', 'template', 'help'
              ],
              toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons | help',
          });

          document.addEventListener('focusin', (e) => {
            if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
              e.stopImmediatePropagation();

            }
          });

          //append html to hashcode textarea
          var db_hash = $("#db_hash").val();
         
          var htmlContent = `<!DOCTYPE html>
          <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="icon" type="image/png" href="assets/images/sph-logo.ico">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
                <title>Job Posting</title>
                <link rel="stylesheet" href="https://powrserver2.com/assets/css/style.css">
                <script>
                var db_hash = "${db_hash}";
                </script>
            </head>

            <body>
            <div id="error"></div>
          <div id="main_html1"></div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
                <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
                <script type="text/javascript" src="https://powrserver2.com/assets/js/script.js?v=121"></script>
            </body>

        </html>`;
          // Set the default value to the textarea
          $("#hash_code").val(htmlContent);
          //copy code button
          $("#copy_code").on("click", function (e) {
           e.preventDefault();
          
            var hashCode = $("#hash_code").val();

           
            var tempTextarea = $("<textarea>").val(hashCode).appendTo(document.body);

          
            tempTextarea.select();
            document.execCommand("copy");

          
            tempTextarea.remove();

         
            swal("Hash code copied to clipboard");
        });
})