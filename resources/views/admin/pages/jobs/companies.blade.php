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
                </span> <span>Companies </span>
            </h3>
          </div>
          <div class="col text-end">
             <ul class="breadcrumb bg-white float-end m-0 ps-0 pe-0">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Companies Dashboard</li>
             </ul>
            
          </div>
          
       </div>
       <div class="row mb-5">
         <div class="col-lg-12">
             <li class="list-inline-item">
                 <button class="add btn btn-gradient-primary font-weight-bold text-white todo-list-add-btn btn-rounded" id="add-company" data-bs-toggle="modal" data-bs-target="#add_company">New Company</button>
              </li>
         </div>
       </div>
       <!-- /Page Header -->
       <table id="companies_table" class="table table-striped table-bordered table-hover">
          <thead>
             <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th> Website</th>
                <th>Location</th>
                <th> Description</th>
                <th>Action </th>
             </tr>
          </thead>
       </table>
       <!-- Modal -->
       <div class="modal right fade" id="add_company" tabindex="-1" role="dialog" aria-modal="true">
          <div class="modal-dialog" role="document">
             <div class="modal-content">
                <div class="modal-header">
                   <h4 class="modal-title text-center">Add Company</h4>
                   <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                   <div class="row">
                      <div class="col-md-12">
                        <form id="addcompanyForm"  action="{{route('add_company')}}" enctype="multipart/form-data">
                            @csrf
                            <h4>Company Details</h4>
                            
                            <div class="form-group row">
                                <div class="col-md-6">
                                   <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                   <input class="form-control" type="text" name="name" placeholder="Company Name">
                                   <div class="name_error all_errors text-danger"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text"  name="email" placeholder="Company Email">
                                    <div class="email_error all_errors text-danger"></div>
                                </div>
                             </div>
                             <div class="form-group row">
                                <div class="col-md-6">
                                   <label class="col-form-label">Phone <span class="text-danger">*</span></label>
                                   <input class="form-control" type="text"  name="phone" placeholder="Company Phone">
                                   <div class="phone_error all_errors text-danger"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Website <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="website" placeholder="Company Website">
                                    <div class="website_error all_errors text-danger"></div>
                                </div>
                             </div>
                             <div class="form-group row">
                                <div class="col-md-6">
                                   <label class="col-form-label">Location <span class="text-danger">*</span></label>
                                   <input class="form-control" type="text" name="location" placeholder="Company Location">
                                   <div class="location_error all_errors text-danger"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">logo <span class="text-danger">*</span></label>
                                    <input class="form-control" type="file"  name="logo" placeholder="Company Logo">
                                    <div class="logo_error all_errors text-danger"></div>
                                </div>
                             </div>
                             <div class="form-group row">
                                <div class="col">
                                   <label class="col-form-label">Description <span class="text-danger">*</span></label>
                                   <textarea class="form-control" name="description" placeholder="Company Description"></textarea>
                                   
                                   <div class="description_error all_errors text-danger"></div>
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
             <!-- modal-content -->
          </div>
          <!-- modal-dialog -->
       </div>
       <!-- modal -->
 
       {{-- edit modal  --}}
       <div class="modal right fade" id="edit_company" tabindex="-1" role="dialog" aria-modal="true">
          <div class="modal-dialog" role="document">
             <div class="modal-content">
                <div class="modal-header">
                   <h4 class="modal-title text-center">Edit Company</h4>
                   <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                   <div class="row">
                      <div class="col-md-12">
                         <form id="updatecompanyForm"  action="{{route('update_company')}}" enctype="multipart/form-data">
                            @csrf
                            <h4>Company Details</h4>
                            <input type="text" id="company_id" name="id" >
                            <div class="form-group row">
                                <div class="col-md-6">
                                   <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                   <input class="form-control" type="text" id="name" name="name" placeholder="Company Name">
                                   <div class="name_error all_errors text-danger"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="email" name="email" placeholder="Company Email">
                                    <div class="email_error all_errors text-danger"></div>
                                </div>
                             </div>
                             <div class="form-group row">
                                <div class="col-md-6">
                                   <label class="col-form-label">Phone <span class="text-danger">*</span></label>
                                   <input class="form-control" type="text" id="phone" name="phone" placeholder="Company Phone">
                                   <div class="phone_error all_errors text-danger"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Website <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="website" name="website" placeholder="Company Website">
                                    <div class="website_error all_errors text-danger"></div>
                                </div>
                             </div>
                             <div class="form-group row">
                                <div class="col-md-6">
                                   <label class="col-form-label">Location <span class="text-danger">*</span></label>
                                   <input class="form-control" type="text" id="location" name="location" placeholder="Company Location">
                                   <div class="location_error all_errors text-danger"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">logo <span class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="logo" name="logo" placeholder="Company Logo">
                                    <div class="logo_error all_errors text-danger"></div>
                                </div>
                             </div>
                             <div class="form-group row">
                                <div class="col">
                                   <label class="col-form-label">Description <span class="text-danger">*</span></label>
                                   <textarea class="form-control" name="description" id="description" placeholder="Company Description"></textarea>
                                 
                                   <div class="description_error all_errors text-danger"></div>
                               
                                
                             </div>
                            <div class="text-center py-3">
                               <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">Save</button>&nbsp;&nbsp;
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