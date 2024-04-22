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
               </span> <span>Template </span>
            </h3>
         </div>
         <div class="col text-end">
            <ul class="breadcrumb bg-white float-end m-0 ps-0 pe-0">
               <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
               <li class="breadcrumb-item active">Template Dashboard</li>
            </ul>

         </div>

      </div>
      <div class="row mb-5">
         <div class="col-lg-12">
            <li class="list-inline-item">
               <button class="add btn btn-gradient-primary font-weight-bold text-white todo-list-add-btn btn-rounded" id="add-template" data-bs-toggle="modal" data-bs-target="#add_template">New Template</button>
            </li>
         </div>
      </div>
      <!-- /Page Header -->
      <table id="template_table" class="table table-striped table-bordered table-hover">
         <thead>
            <tr>
               <th>Name</th>
               <th>Action </th>
            </tr>
         </thead>
      </table>
      <!-- Modal -->
      <div class="modal right fade" id="add_template" tabindex="-1" role="dialog" aria-modal="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title text-center">Add Template</h4>
                  <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-md-12">
                        <form id="addtemplateForm" action="{{route('add_template')}}" enctype="multipart/form-data">
                           @csrf
                           <h4>Template Details</h4>
                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Title <span class="text-danger">*</span></label>
                                 <input class="form-control" type="text" id="ttile" name="title" placeholder="Template name">
                                 <div class="title_error all_errors text-danger"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Status <span class="text-danger"></span></label>
                                 <div class="form-group row">
                                    <select name="status" id="status" class="form-control">
                                       <option value="1">Active</option>
                                       <option value="0">Inactive</option>
                                    </select>
                                    <div class="type_error all_errors text-danger"></div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Image <span class="text-danger">*</span> </label>
                                 <input class="form-control" type="file" id="image" name="image">
                                 <label class="col-form-label text-danger">Dimension (2100x300 px)</label>
                                 <div class="image_error all_errors text-danger"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Color <span class="text-danger">*</span></label>
                                 <!-- <input class="form-control" type="text" id="ttile" name="title" placeholder="Template name"> -->
                                 <input class="form-control" type="color" id="color" name="color" value="#849c3d">
                                 <div class="color_error all_errors text-danger"></div>
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
      <div class="modal right fade" id="edit_template" tabindex="-1" role="dialog" aria-modal="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title text-center">Edit Template</h4>
                  <button type="button" class="btn-close xs-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-md-12">
                        <form id="updatetemplateForm" enctype="multipart/form-data" action="{{route('update_template')}}">
                           @csrf
                           <h4>Template Details</h4>
                           <input type="hidden" id="template_id" name="id">
                           <div class="form-group row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Title <span class="text-danger">*</span></label>
                                 <input class="form-control" type="text" id="edit_title" name="title" placeholder="Template name">
                                 <div class="title_error all_errors text-danger"></div>
                              </div>
                              <div class="col-md-6">
                                 <label class="col-form-label">Image <span class="text-danger">*</span></label>
                                 <input class="form-control" type="file" id="edit_image" name="image">
                                 <div class="image_error all_errors text-danger"></div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <label class="col-form-label">Current Image</label>
                                 <img id="current_image_preview" src="" alt="Current Image" style="max-width: 100%; height: auto;">
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