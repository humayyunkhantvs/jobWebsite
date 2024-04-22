<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/png" href="./sph-logo.ico">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title><?php echo $job['title']; ?></title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
   {{--
      <link rel="stylesheet" href="{{ asset('user/assets/css/style.css')}}">
   --}}
   <link rel="stylesheet" href="{{asset('assets/css/style.css') }}">
   <script type="application/ld+json">
      {
         "@context": "https://schema.org",
         "@type": "JobPosting",
         "title": "<?php echo $job['title']; ?>",
         "description": "<?php echo substr($job['description'], 0, 350); ?>",
         "baseSalary": "<?php echo $job['salary']; ?>",
         "industry": "<?php echo $job['category']; ?>",
         "occupationalCategory": "<?php echo $job['category']; ?>",
         "salaryCurrency": "USD",
         "jobLocation": {
            "@type": "Place",
            "address": {
               "@type": "PostalAddress",
               "addressLocality": "<?php echo $job['address']; ?>",
               "addressCountry": "<?php echo $job['country']; ?>"
            }
         }
      }
   </script>
   <style>
      .all_errors {
         color: red;
      }

      .btn-primary:focus {
         color: #fff;
         background-color: #849c3d !important;
         border-color: #849c3d !important;
         box-shadow: none !important;
      }

      .job-detail-form {
         background-color: #D9D9D9;
         height: 50px;
         padding: 13px;
         margin-top: 12px;
         margin-bottom: 8px;
      }
   </style>
</head>

<body>


   <section id="hero-section" class="my-3">
      <nav class="navbar navbar-expand-md navbar-dark" style="background-color: {{ !empty($backgroundcolor) ? $backgroundcolor : '#849c3d' }};">
         <div class="container-fluid">
            <a class="navbar-brand" href="javascript:void(0)">
               @if(!empty($imageName))
               <!-- If $imageName is not empty or null, display the uploaded image -->
               <img src="{{ asset('upload/' . $imageName) }}" id="bg_img">
               @else
               <!-- If $imageName is empty or null, display the default image -->
               <img src="{{ asset('assets/images/bg.png') }}" id="bg_img">
               @endif
            </a>
            <div class="collapse navbar-collapse">
               <ul class="navbar-nav mx-auto">
                  <li class="nav-item">
                     <a class="nav-link active" href="javascript:void(0)">Find jobs by category, location or title.</a>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
   </section>

   <section class="my-2" id="job-details">
      <div id="<?php echo $job['id']; ?>" class="job-details container p-5">
         <div class="mb-5">
            <button type="button" class="btn float-end text-white" data-bs-toggle="modal" data-bs-target="#apply_job" style="background-color: {{ !empty($backgroundcolor) ? $backgroundcolor : '#849c3d' }};">
               Apply For This Job
            </button>
         </div>
         <!-- <a href="javascript:void(0)" class="bck_btn btn btn-primary">Back to Jobs</a> -->

         <div class="job-detail-form">
            <h6 class="fw-bold">BASIC JOB DETAILS</h6>
         </div>
         <p class="mt-4">
            <strong><span>Title:</span></strong>
            <span><?php echo $job['title']; ?></span>
         </p>
         <p>
            <strong><span>Category:</span></strong>
            <span><?php echo $job['category_name']; ?></span>
         </p>
         <p>
            <strong><span>Job Type:</span></strong>
            <span><?php echo $job['type']; ?></span>
         </p>
         <p>
            <strong><span>Salary:</span></strong>
            <span><?php echo $job['salary']; ?></span>
         </p>
         <p class="mb-5">
            <strong><span>Application Deadline:</span></strong>
            <span><?php echo date('m/d/y', strtotime($job['deadline'])); ?></span>
         </p>
         <div class="job-detail-form">
            <h6 class="fw-bold">LOCATION DETAILS</h6>
         </div>
         <p class="mt-4">
            <strong><span>Company:</span></strong>
            <span><?php echo    $job['company']; ?></span>
         </p>
         <p>
            <strong><span>Address:</span></strong>
            <span><?php echo    $job['address']; ?></span>
         </p>

         <p>
            <strong><span>City:</span></strong>
            <span><?php echo    $job['city']; ?></span>
         </p>
         <p>
            <strong><span>State:</span></strong>
            <span><?php echo    $job['state']; ?></span>
         </p>
         <p class="mb-5">
            <strong><span>Zip:</span></strong>
            <span><?php echo    $job['zip']; ?></span>
         </p>
         <div class="job-detail-form">
            <h6 class="fw-bold">JOB STATUS</h6>
         </div>
         <p class="mt-4">
            <strong><span>Status:</span></strong>
            <span><?php echo    $job['job_status']; ?></span>
         </p>

         <p class="mb-5">
            <strong><span>Publish Date:</span></strong>
            <span><?php echo date('m/d/y', strtotime($job['published_at'])); ?></span>
         </p>
         <!-- <p>
               <strong><span>Education:</span></strong>
            <ul>
               <li>
                  <span>Required: Nurse Practitioner: Masters or Doctorate degree from accredited Nursing Program. Physician Assistant: Bachelor or Masters degree from accredited PA Program. </span>
               </li>
               <li>
                  <span>Preferred: None</span>
               </li>
            </ul>
            </p> -->
         <p>
         <div class="job-detail-form">
            <h6 class="fw-bold">JOB DESCRIPTION</h6>
         </div>
         <span class="m-4"><?php echo    $job['description']; ?></span>
         </p>

         <button type="button" class="btn float-end text-white" data-bs-toggle="modal" data-bs-target="#apply_job" style="background-color: {{ !empty($backgroundcolor) ? $backgroundcolor : '#849c3d' }};">
            Apply For This Job
         </button>
      </div>
   </section>  
   <!-- Button trigger modal -->
   <!-- Modal -->
   <div class="modal fade" id="apply_job" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-xl">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">User Details</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form id="applyjobForm" action="{{route('apply_job')}}" method="POST" enctype="multipart/form-data">

                  <input type="hidden" name="job_id" value="{{$job['id']}}">
                  <input type="hidden" name="job_title" value="{{$job['title']}}">
                  <input type="hidden" name="user_id" value="{{$job['user_id']}}">
                  <div class="form-group row">
                     <div class="col-md-6">
                        <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="first_name" name="first_name" placeholder="First Name">
                        <div class="first_name_error all_errors"></div>
                     </div>
                     <div class="col-md-6">
                        <label class="col-form-label">Last Name <span class="text-danger">*</span></label>
                        <div class="form-group ">
                           <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control">
                           <div class="last_name_error all_errors"></div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row">
                     <div class="col-md-6">
                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="form-group">
                           <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                           <div class="email_error all_errors"></div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <label class="col-form-label">Phone Number<span class="text-danger"></span></label>
                        <div class="form-group ">
                           <input type="phone" name="phone" id="phone" class="form-control" placeholder="Phone Number">
                           <div class="phone_error all_errors"></div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row">
                     <div class="col-md-6">
                        <label class="col-form-label">DOB <span class="text-danger"></span></label>
                        <input class="form-control" type="date" id="dob" name="dob">
                        <div class="dob_error all_errors"></div>
                     </div>
                     <div class="col-md-6">
                        <label class="col-form-label">Address <span class="text-danger">*</span></label>
                        <div class="form-group">
                           <input class="form-control" type="text" id="address" name="address" placeholder="Address">
                           <div class="address_error all_errors"></div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row">
                     <div class="col-md-6">
                        <label class="col-form-label">City <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="city" name="city" placeholder="City">
                        <div class="city_error all_errors"></div>
                     </div>
                     <div class="col-md-6">
                        <label class="col-form-label">State<span class="text-danger"></span></label>
                        <div class="form-group">
                           <select name="state" id="state" class="form-control">
                              <option value="">Select State</option>
                              @foreach (config('states') as $abbreviation => $stateName)
                              <option value="{{ $stateName }}">{{ $stateName }}</option>
                              @endforeach
                           </select>
                           <div class="state_error all_errors"></div>
                        </div>
                     </div>
                  </div>
                     <div class="form-group row">
                        <div class="col-md-6">
                           <label class="col-form-label">Zip Code <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" id="zip_code" name="zip_code" placeholder="Zip Code">
                           <div class="zip_code_error all_errors"></div>
                        </div>
                        <div class="col-md-6">
                           <label class="col-form-label">Resume<span class="text-danger">*</span></label>
                           <input class="form-control" type="file" id="cv" name="cv">
                           <div class="cv_error all_errors"></div>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col">
                           <label class="col-form-label">Cover Letter <span class="text-danger">*</span></label>
                           <div class="col">
                              <textarea id="cover_letter" name="cover_letter" class="form-control" rows="5"></textarea>
                              {{--
                              <textarea class="summernote" name="description"></textarea>
                              --}}
                              <div class="cover_letter_error all_errors"></div>
                           </div>
                        </div>
                     </div>
                     <div class="text-center py-3">
                        <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">Save</button>&nbsp;&nbsp;
                     </div>
                     {{--
                     <x-forms.tinymce-editor/>
                     --}}
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>



   <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   <script src="https://cdn.tiny.cloud/1/eh83qrdiozahqd9f0coxhcr6icqz5xst3qtlykm0lm85fdu1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
   <script src="{{ asset('user/assets/js/add_applicant.js?v=11')}}"></script>
   <!-- <script type="text/javascript" src="assets/js/script.js?v=f"></script> -->


</body>

</html>