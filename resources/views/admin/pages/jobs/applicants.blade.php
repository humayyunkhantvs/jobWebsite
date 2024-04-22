@extends('admin.layouts.main')
@section('main-container')

<!-- Page Wrapper -->
<div class="page-wrapper">
   <div class="content container-fluid">
      <!-- Page Header -->
      <div class="crms-title row bg-white mb-4">
         <div class="col">
            <h3 class="page-title">
               <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="la la-table"></i>
               </span> <span>Applicants </span>
            </h3>
         </div>
         <div class="col text-end">
            <ul class="breadcrumb bg-white float-end m-0 ps-0 pe-0">
               <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
               <li class="breadcrumb-item active">Applicants Dashboard</li>
            </ul>

         </div>

      </div>

      <div class="mb-3">
         <label for="jobFilter" class="form-label">Filter by Job:</label>
         <select id="jobFilter" class="form-select">
            <option value="">All Jobs</option>
            @foreach($categories as $categorie)
            <option value="{{$categorie->id}}">{{ $categorie->name }}</option>
            @endforeach
         </select>
      </div>

      <!-- <div class="mb-3">
         <label for="jobStatusFilter" class="form-label">Filter by Job Status:</label>
         <select id="jobStatusFilter" class="form-select">
            <option value="">All Statuses</option>
            <option value="open">Open</option>
            <option value="closed">Closed</option>
            <option value="draft">Draft</option>
         </select>
      </div> -->
      <!-- <div class="row mb-5">
         <div class="col-lg-12">
             <li class="list-inline-item">
                 <button class="add btn btn-gradient-primary font-weight-bold text-white todo-list-add-btn btn-rounded" id="add-category" data-bs-toggle="modal" data-bs-target="#add_category">New Category</button>
              </li>
         </div>
       </div> -->
      <!-- /Page Header -->
      <table id="applicants_table" class="table table-striped table-bordered table-hover">
         <thead>
            <tr>
               <th>First Name</th>
               <th>Last Name</th>
               <th>Email</th>
               <th>Action </th>
            </tr>
         </thead>
      </table>


      <!-- Edit Item Modal -->
      <div class="modal fade" id="view-applicant-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel1">Applicant</h4>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
               </div>
               <div class="modal-body ">
                  <form data-toggle="validator" method="GET">
                  @csrf
                     <!-- <input type="hidden" id="update-id" name="id" class="edit-id"> -->


                     <div class="form-group">
                        <label class="control-label" for="job_title">Job Title:</label>
                        <input type="text" id="job_title" name="job_title" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="email">Email:</label>
                        <input type="text" id="email" name="email" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="phone">Phone:</label>
                        <input id="phone" name="phone" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="address">Address:</label>
                        <input id="address" name="address" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="dob">DOB:</label>
                        <input id="dob" name="dob" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="city">City:</label>
                        <input id="city" name="city" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="state">State:</label>
                        <input id="state" name="state" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="zip_code">Zip Code:</label>
                        <input id="zip_code" name="zip_code" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="cover_letter">Cover Letter:</label>
                        <input id="cover_letter" name="cover_letter" class="form-control" readonly />
                        <!-- <div class="help-block with-errors"></div> -->
                     </div>

                     <!-- <div class="form-group">
							<label class="control-label" for="cv">CV:</label>
							<input id="cv" name="cv" class="form-control"  readonly/>
						
						</div> -->


                     <!-- <a href="download">Download Cv</a> -->
                  </form>
               </div>
            </div>
         </div>
      </div>





   </div>
   <!-- Modal -->
   <!-- <div class="modal right fade" id="add_category" tabindex="-1" role="dialog" aria-modal="true">
          <div class="modal-dialog" role="document">
             <div class="modal-content">
                <div class="modal-header">
                   <h4 class="modal-title text-center">Add Category</h4>
                   <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                   <div class="row">
                      <div class="col-md-12">
                         <form id="addcategoryForm" action="{{route('add_category')}}" >
                            @csrf
                            <h4>Category Details</h4>
                            <div class="form-group row">
                               <div class="col-md-6">
                                  <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                  <input class="form-control" type="text" id="name" name="name" placeholder="Category name">
                                  <div class="name_error all_errors text-danger"></div>
                               </div>
                            </div>
                            
                            <div class="text-center py-3">
                               <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">Save</button>&nbsp;&nbsp;
                            </div>
                         </form>
                      </div>
                   </div>
                </div>
             </div>
           
          </div>
         
       </div> -->
   <!-- modal -->

   <!-- {{-- edit modal  --}}
       <div class="modal right fade" id="edit_category" tabindex="-1" role="dialog" aria-modal="true">
          <div class="modal-dialog" role="document">
             <div class="modal-content">
                <div class="modal-header">
                   <h4 class="modal-title text-center">Edit Category</h4>
                   <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                   <div class="row">
                      <div class="col-md-12">
                         <form id="updatecategoryForm"  action="{{route('update_category')}}">
                            @csrf
                            <h4>Category Details</h4>
                            <input type="hidden" id="category_id" name="id" >
                            <div class="form-group row">
                                <div class="col-md-6">
                                   <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                   <input class="form-control" type="text" id="name" name="name" placeholder="Category name">
                                   <div class="name_error all_errors text-danger"></div>
                                </div>
                             </div>
                        
                            <div class="text-center py-3">
                               <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">Save</button>&nbsp;&nbsp;
                            </div>
                         </form>
                      </div>
                   </div>
                </div>
             </div>
            
          </div>
          
       </div> -->
   <!-- {{-- edit modal end --}} -->
</div>
</div>
<!-- /Page Wrapper -->
@endsection