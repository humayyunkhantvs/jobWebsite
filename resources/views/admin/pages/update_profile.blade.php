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
                    </span> <span>Update Profile </span>
                </h3>
            </div>
            <div class="col text-end">
                <ul class="breadcrumb bg-white float-end m-0 ps-0 pe-0">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item active">Update Profile Dashboard</li>
                </ul>

            </div>

        </div>
        <!-- <div class="row mb-5">
         <div class="col-lg-12">
             <li class="list-inline-item">
                 <button class="add btn btn-gradient-primary font-weight-bold text-white todo-list-add-btn btn-rounded" id="update-profile" data-bs-toggle="modal" data-bs-target="#update_profile">Update Profile</button>
              </li>
         </div>
       </div> -->
        <!-- /Page Header -->



        <div class="row">
            <div class="col-md-12">
                <form id="updateuserform" action="{{route('update_user')}}">
                    @csrf
                    <h4>User Details</h4>
                    <input type="hidden" id="team_id" name="id" value="{{ $user->id }}">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label">Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="name" name="name" placeholder="Name" value="{{ $user->name }}">
                            <div class="name_error all_errors text-danger"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="col-form-label">Email<span class="text-danger">*</span></label>
                            <input class="form-control" type="text"  placeholder="Email" value="{{ $user->email }}" readonly>
                            <div class="name_error all_errors text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <h4>Password :</h4>
                        <div class="col-md-6">
                            <label class="col-form-label">Old Password <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="old_password" name="old_password" placeholder="Password">
                            <div class="old_password_error all_errors text-danger"></div>
                            <div class="error_div all_errors text-danger"></div>

                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">New Password <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="password" name="password" placeholder="Password">
                            <div class="password_error all_errors text-danger"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="cnfrm_password" name="password_confirmation" placeholder="Confirm Password">
                            <div class="password_confirmation_error all_errors text-danger"></div>
                        </div>

                    </div>
                    <div class="py-3">
                        <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">Update Profile</button>&nbsp;&nbsp;
                    </div>
                </form>
            </div>
        </div>
        {{-- edit modal  --}}
        <!-- <div class="modal right fade" id="update_profile" tabindex="-1" role="dialog" aria-modal="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Edit User</h4>
                        <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                            <form id="updateuserform"  action="{{route('update_user')}}">
                                @csrf
                                <h4>User Details</h4>
                                <input type="hidden" id="team_id" name="id" value="{{ $user->id }}">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                    <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                   <input class="form-control" type="text" id="name" name="name" placeholder="Name" value="{{ $user->name }}">                                       
                                         <div class="name_error all_errors text-danger"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">Old Password <span class="text-danger">*</span></label>
                                       <input class="form-control" type="text" id="old_password" name="old_password" placeholder="Password" >                                       
                                             <div class="old_password_error all_errors text-danger"></div>
                                             <div class="error_div all_errors text-danger"></div>
                                            
                                        </div>
                                
                                </div>
                                <div class="form-group row">
                                    
                                    <div class="col-md-6">
                                        <label class="col-form-label">New Password <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="password" name="password" placeholder="Password">
                                        <div class="password_error all_errors text-danger"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>
                                       <input class="form-control" type="text" id="cnfrm_password" name="password_confirmation" placeholder="Confirm Password" >                                       
                                             <div class="password_confirmation_error all_errors text-danger"></div>
                                        </div>
                                </div>
                                <div class="text-center py-3">
                                    <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">Update Profile</button>&nbsp;&nbsp;
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div> -->
        {{-- edit modal end --}}





    </div>
</div>
<!-- /Page Wrapper -->
@endsection