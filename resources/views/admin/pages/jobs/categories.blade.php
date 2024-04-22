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
               </span> <span>Categories </span>
            </h3>
         </div>
         <div class="col text-end">
            <ul class="breadcrumb bg-white float-end m-0 ps-0 pe-0">
               <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
               <li class="breadcrumb-item active">Categories Dashboard</li>
            </ul>

         </div>

      </div>
      <div class="row mb-5">
         <div class="col-lg-12">
            @unless($userRole == 'superadmin')
            <li class="list-inline-item">
               <button class="add btn btn-gradient-primary font-weight-bold text-white todo-list-add-btn btn-rounded" id="add-category" data-bs-toggle="modal" data-bs-target="#add_category">New Category</button>
            </li>
            @endunless
         </div>
      </div>
      <!-- /Page Header -->
      <table id="categories_table" class="table table-striped table-bordered table-hover">
         <thead>
            <tr>
               <th>Name</th>
               <th>Action </th>
            </tr>
         </thead>
      </table>
      <!-- Modal -->
      <div class="modal right fade" id="add_category" tabindex="-1" role="dialog" aria-modal="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title text-center">Add Category</h4>
                  <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-md-12">
                        <form id="addcategoryForm" action="{{route('add_category')}}">
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
            <!-- modal-content -->
         </div>
         <!-- modal-dialog -->
      </div>
      <!-- modal -->

      {{-- edit modal  --}}
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
                        <form id="updatecategoryForm" action="{{route('update_category')}}">
                           @csrf
                           <h4>Category Details</h4>
                           <input type="hidden" id="category_id" name="id">
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
            <!-- modal-content -->
         </div>
         <!-- modal-dialog -->
      </div>
      {{-- edit modal end --}}
   </div>
</div>
<!-- /Page Wrapper -->
@endsection