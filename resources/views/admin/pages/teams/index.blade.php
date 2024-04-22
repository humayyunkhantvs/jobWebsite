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
                @if($userRole == 'admin')
                </span> <span>Agency Sub Account</span>
                @endif
                @if($userRole == 'superadmin')
                </span> <span>Agency Admin Account</span>
                @endif
             </h3>
          </div>
          <div class="col text-end">
             <ul class="breadcrumb bg-white float-end m-0 ps-0 pe-0">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Agency Users Dashboard</li>
             </ul>
            
          </div>
          
       </div>
       <div class="row mb-5">
         <div class="col-lg-12">
             <li class="list-inline-item">
                 <button class="add btn btn-gradient-primary font-weight-bold text-white todo-list-add-btn btn-rounded" id="add-team" data-bs-toggle="modal" data-bs-target="#add_team">New Sub Account</button>
              </li>
         </div>
       </div>
       <!-- /Page Header -->
       <table id="teams_table" class="table table-striped table-bordered table-hover">
        <thead>
           <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Send Reset Link</th>
              <th>Action </th>
           </tr>
        </thead>
     </table>


     <!-- Modal -->
     <div class="modal right fade" id="add_team" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
              <div class="modal-header">
                 <h4 class="modal-title text-center">Add Sub Account</h4>
                 <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                 <div class="row">
                    <div class="col-md-12">
                       <form id="addteamform" action="{{route('add_team')}}" >
                          @csrf
                          <h4>Sub Account Details</h4>
                          <div class="form-group row">
                             <div class="col-md-6">
                                <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="Name" name="name" placeholder="Name">
                                <div class="name_error all_errors text-danger"></div>
                             </div>
                             <div class="col-md-6">
                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="email" name="email" placeholder="Email">
                                <div class="email_error all_errors text-danger"></div>
                             </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-md-6">
                               <label class="col-form-label">Password <span class="text-danger">*</span></label>
                               <input class="form-control" type="password" id="password" name="password" placeholder="Password">
                               <div class="password_error all_errors text-danger"></div>
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
       <div class="modal right fade" id="edit_team" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
              <div class="modal-header">
                 <h4 class="modal-title text-center">Edit Agency User</h4>
                 <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                 <div class="row">
                    <div class="col-md-12">
                       <form id="updateteamform"  action="{{route('update_team')}}">
                          @csrf
                          <h4>Agency User Details</h4>
                          <input type="hidden" id="team_id" name="id" >
                          <div class="form-group row">
                             <div class="col-md-6">
                                <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="name" name="name" placeholder="Name">
                                <div class="name_error all_errors text-danger"></div>
                             </div>
                             <div class="col-md-6">
                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="email" name="email" placeholder="Email">
                                <div class="email_error all_errors text-danger"></div>
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
     {{-- edit modal end --}}





        </div>
    </div>
    <!-- /Page Wrapper -->
@endsection