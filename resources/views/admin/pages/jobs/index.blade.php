@extends('admin.layouts.main')
@section('main-container')
<style>
   .all_errors {
      color: red;
   }

   .tox.tox-tinymce-aux {
      z-index: 1000000000 !important;
   }

   .job-detail-form {
      background-color: #D9D9D9;
      height: 50px;
      padding: 18px;
      margin-top: 12px;
      margin-bottom: 8px;
   }

   .btn-save-size {
      padding: 15px 25px !important;
      font-size: 16px !important;
   }

   .job-save-btn {
      width: 50% !important;
      font-weight: bold;
   }
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
   <div class="content container-fluid">
      <!-- Page Header -->
      <div class="crms-title row bg-white mb-4">
         <div class="col">
            <h3 class="page-title">
               <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="la la-table"></i>
               </span> <span>Jobs </span>
            </h3>
         </div>
         <div class="col text-end">
            <ul class="breadcrumb bg-white float-end m-0 ps-0 pe-0">
               <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
               <li class="breadcrumb-item active">Jobs Dashboard</li>
            </ul>
         </div>
      </div>
      <div class="mb-3">
         <label for="jobFilterstatus" class="form-label">Filter by Job Status:</label>
         <select id="jobFilterstatus" class="form-select">
            <option value="">All Jobs</option>
            @php
            // Extract unique job statuses from $jobs array
            $uniqueJobStatuses = $jobs->pluck('job_status')->unique();
            @endphp
            @foreach($uniqueJobStatuses as $status)
            <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
         </select>
      </div>
      @if($userRole == 'superadmin')
      <div class="mb-3">
         <label for="adminFilter" class="form-label">Filter by Admin:</label>
         <select id="adminFilter" class="form-select">
            <option value="">All Admins</option>
            @foreach($adminUsers as $adminUser)
            <option value="{{ $adminUser->id }}">{{ $adminUser->name }}</option>
            @endforeach
         </select>
      </div>

      <!-- Add new dropdown for users -->
      <div class="mb-3" id="userFilterContainer" style="display:none;">
         <label for="userFilter" class="form-label">Filter by User:</label>
         <select id="userFilter" class="form-select">
            <!-- Options will be dynamically populated based on selected admin -->
         </select>
      </div>
      @endif

      <div class="row mb-5">
         <div class="col-lg-12">
            <!-- Use Blade directive to check user role and show "New Job" button accordingly -->
            @unless($userRole == 'superadmin')
            <li class="list-inline-item">
               <button class="add btn btn-gradient-primary font-weight-bold text-white todo-list-add-btn btn-rounded" id="add-job" data-bs-toggle="modal" data-bs-target="#add_job">New Job</button>
            </li>
            @endunless
         </div>
      </div>
      <!-- /Page Header -->
      <table id="jobs_table" class="table table-striped table-bordered table-hover">
         <thead>
            <tr>
               <th>Title</th>
               <th>Description</th>
               <th>Publish Date</th>
               <th>Company</th>
               {{-- <th>Category</th> --}}

               <th>Action </th>
            </tr>
         </thead>
      </table>
      <!-- Modal -->
      <div class="modal right fade" id="add_job" tabindex="-1" role="dialog" aria-modal="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title text-center">Add Job</h4>
                  <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-md-12">
                        <form id="addjobForm" action="{{route('add_job')}}">
                           @csrf
                           <div class="job-detail-form">
                              <h5 class="fw-bold">BASIC JOB DETAILS</h5>
                           </div>
                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Title <span class="text-danger">*</span></label>
                                 <input class="form-control" type="text" id="title" name="title" placeholder="Job Title">
                                 <div class="title_error all_errors"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Category <span class="text-danger">*</span></label>
                                 <select class="form-control" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                 </select>
                                 <div class="category_error all_errors"></div>
                              </div>


                           </div>
                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Salary <span class="text-danger"></span></label>
                                 <input class="form-control" type="number" id="salary" name="salary">
                                 <div class="salary_error all_errors"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Type <span class="text-danger"></span></label>
                                 <div class="form-group">
                                    <select name="type" id="type" class="form-control">
                                       <option value="full">Full</option>
                                       <option value="parttime">Part Time</option>
                                       <option value="internship">Internship</option>
                                       <option value="temporary">Temporary</option>
                                    </select>
                                    <div class="type_error all_errors"></div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Deadline <span class="text-danger"></span></label>
                                 <input class="form-control" type="date" id="deadline" name="deadline">
                                 <div class="deadline_error all_errors"></div>
                              </div>
                              <!-- <div class="col-md-6">
                              <label class="col-form-label">Email <span class="text-danger"></span></label>
                              <div class="form-group">
                                 <input type="email" name="email" id="email" class="form-control">
                                 <div class="email_error all_errors"></div>
                              </div>
                           </div> -->
                           </div>

                           <!-- <div class="form-group row">
                           <div class="col-md-6">
                              <label class="col-form-label">Website <span class="text-danger"></span></label>
                              <input class="form-control" type="text" id="website" name="website" >
                              <div class="website_error all_errors"></div>
                           </div>
                           <div class="col-md-6">
                              <label class="col-form-label">Phone <span class="text-danger"></span></label>
                              <div class="form-group">
                                 <input type="text" name="phone" id="phone" class="form-control">
                                 <div class="phone_error all_errors"></div>
                              </div>
                           </div>
                        </div> -->
                           <div class="job-detail-form">
                              <h5 class="fw-bold">LOCATION DETAILS</h5>
                           </div>
                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Company <span class="text-danger">*</span></label>
                                 <div class="form-group">
                                    <input type="text" name="company" id="company" class="form-control">
                                    <div class="company_error all_errors"></div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Address <span class="text-danger"></span></label>
                                 <input class="form-control" type="text" id="address" name="address">
                                 <div class="address_error all_errors"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">City <span class="text-danger"></span></label>
                                 <input class="form-control" type="text" id="city" name="city">
                                 <div class="city_error all_errors"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">State <span class="text-danger"></span></label>
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
                                 <label class="col-form-label">Zip <span class="text-danger"></span></label>
                                 <input class="form-control" type="text" id="zip" name="zip">
                                 <div class="zip_error all_errors"></div>
                              </div>

                           </div>
                           <div class="job-detail-form">
                              <h5 class="fw-bold">JOB STATUS</h5>
                           </div>
                           <div class="form-group row">

                              <div class="col-md-6">
                                 <label class="col-form-label">Status <span class="text-danger"></span></label>
                                 <div class="form-group">
                                    <select name="status" class="form-control">
                                       <option value="published">Published</option>
                                       <option value="draft">Draft</option>
                                       <!-- <option value="closed">Closed</option> -->
                                    </select>
                                    <div class="status_error all_errors"></div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Published At <span class="text-danger"></span></label>
                                 <input class="form-control" type="date" id="published_date" name="published_date" min="{{ now()->toDateString() }}" max="{{ now()->addYear()->toDateString() }}">
                                 <div class="published_date_error all_errors"></div>
                              </div>
                           </div>
                           <div class="job-detail-form">
                              <h5 class="fw-bold">JOB DESCRIPTION</h5>
                           </div>
                           <div class="form-group row">

                              <!-- <label class="col-form-label">Description <span class="text-danger"></span></label> -->
                              <div class="col">
                                 <textarea id="mytextarea" name="description"></textarea>

                                 <div class="description_error all_errors"></div>
                              </div>
                              <!-- <div class="col">
                              <label class="col-form-label">Description <span class="text-danger"></span></label>
                              <textarea id="edit_mytextarea" name="description"></textarea>
                              <div class="description_error all_errors"></div>
                           </div> -->

                           </div>
                           <div class="text-center py-3">
                              <button type="submit" class="border-0 btn btn-primary btn-save-size btn-gradient-primary btn-rounded btn-lg job-save-btn">Save</button>&nbsp;&nbsp;
                           </div>

                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <!-- modal-content -->
         </div>
         <!-- modal-dialog -->
      </div>
      <!-- modal -->
      {{-- edit modal  --}}
      <div class="modal right fade" id="edit_job" tabindex="-1" role="dialog" aria-modal="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title text-center">Edit Job</h4>
                  <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-md-12">
                        <form id="updatejobForm" action="{{route('update_job')}}">
                           @csrf
                           <input type="hidden" id="job_id" name="id">
                           <div class="job-detail-form">
                              <h5 class="fw-bold">BASIC JOB DETAILS</h5>
                           </div>
                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Title <span class="text-danger">*</span></label>
                                 <input class="form-control" type="text" id="edit_title" name="title" placeholder="Job Title">
                                 <div class="title_error all_errors"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Category <span class="text-danger">*</span></label>
                                 <select class="form-control" id="edit_category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                 </select>
                                 <div class="category_error all_errors"></div>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Salary <span class="text-danger"></span></label>
                                 <input class="form-control" type="number" id="edit_salary" name="salary">
                                 <div class="salary_error all_errors"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Type <span class="text-danger"></span></label>
                                 <div class="form-group">
                                    <select name="type" id="edit_type" class="form-control">
                                       <option value="full">Full</option>
                                       <option value="parttime">Part Time</option>
                                       <option value="internship">Internship</option>
                                       <option value="temporary">Temporary</option>
                                    </select>
                                    <div class="type_error all_errors"></div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Deadline <span class="text-danger"></span></label>
                                 <input class="form-control" type="date" id="edit_deadline" name="deadline">
                                 <div class="deadline_error all_errors"></div>
                              </div>
                           </div>



                           <div class="job-detail-form">
                              <h5 class="fw-bold">LOCATION DETAILS</h5>
                           </div>

                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Company <span class="text-danger">*</span></label>
                                 <div class="form-group">
                                    <input type="text" name="company" id="edit_company" class="form-control">
                                    <div class="company_error all_errors"></div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">City <span class="text-danger"></span></label>
                                 <input class="form-control" type="text" id="edit_city" name="city">
                                 <div class="city_error all_errors"></div>
                              </div>
                              <!-- <div class="col-md-6">
                                 <label class="col-form-label">Website <span class="text-danger"></span></label>
                                 <input class="form-control" type="text" id="edit_website" name="website">
                                 <div class="website_error all_errors"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Phone <span class="text-danger"></span></label>
                                 <div class="form-group">
                                    <input type="text" name="phone" id="edit_phone" class="form-control">
                                    <div class="phone_error all_errors"></div>
                                 </div>
                              </div> -->
                           </div>
                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Address <span class="text-danger"></span></label>
                                 <input class="form-control" type="text" id="edit_address" name="address">
                                 <div class="address_error all_errors"></div>
                              </div>

                              <div class="col-md-6">
                                 <label class="col-form-label">State <span class="text-danger"></span></label>
                                 <div class="form-group">
                                    <select name="state" id="edit_state" class="form-control">
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
                                 <label class="col-form-label">Status <span class="text-danger"></span></label>
                                 <div class="form-group">
                                    <select name="status" class="form-control" id="edit_status">
                                       <option value="published">Published</option>
                                       <option value="draft">Draft</option>
                                       <option value="closed">Closed</option>
                                    </select>
                                    <div class="status_error all_errors"></div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <label class="col-form-label">Published At <span class="text-danger"></span></label>
                                 <input class="form-control" type="date" id="edit_published_date" name="published_date">
                                 <div class="published_date_error all_errors"></div>
                              </div>

                              <div class="col-md-6">
                                 <label class="col-form-label">Zip <span class="text-danger"></span></label>
                                 <input class="form-control" type="text" id="edit_zip" name="zip">
                                 <div class="zip_error all_errors"></div>
                              </div>

                           </div>
                           <div class="job-detail-form">
                              <h5 class="fw-bold">JOB DESCRIPTION</h5>
                           </div>
                           <div class="form-group row">
                              <div class="col">
                                 <label class="col-form-label">Description <span class="text-danger"></span></label>
                                 <textarea id="edit_mytextarea" name="description"></textarea>
                                 <div class="description_error all_errors"></div>
                              </div>
                           </div>
                           <div class="text-center py-3">
                              <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-save-size btn-rounded job-save-btn">Save</button>&nbsp;&nbsp;
                           </div>
                        </form>

                     </div>
                  </div>
               </div>
            </div>
            <!-- modal-content -->
         </div>
         <!-- modal-dialog -->
      </div>
      {{-- edit modal end --}}
   </div>
</div>
<!-- /Page Wrapper -->


@endsection