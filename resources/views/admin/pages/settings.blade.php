@extends('admin.layouts.main')
@section('main-container')
<style>
   .all_errors{
   color: red;
   }
   .tox.tox-tinymce-aux {
   z-index: 1000000000 !important;
   }
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
   <div class="content container-fluid">
      @auth
      <!-- User is logged in -->
      <input type="hidden" id="db_hash" value="{{ auth()->user()->hash }}">
      @endauth
      <!-- Page Header -->
      <div class="crms-title row bg-white mb-4">
         <div class="col">
            <h3 class="page-title">
               <span class="page-title-icon bg-gradient-primary text-white me-2">
               <i class="la la-table"></i>
               </span> <span>Settings </span>
            </h3>
         </div>
         <div class="col text-end">
            <ul class="breadcrumb bg-white float-end m-0 ps-0 pe-0">
               <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
               <li class="breadcrumb-item active">Settings Dashboard</li>
            </ul>
         </div>
      </div>
      <div class="row">
         
         <div class="row">
            <div class="col">
               <form id="{{ isset($settings) ? 'updatesettingForm' : 'addsettingForm' }}" action="{{ isset($settings) ? route('update_setting', ['id' => $settings->id]) : route('add_settings')  }}" method="post">
                  @csrf
                  <!-- <h4>Settings</h4>
                  <div class="form-group row">
                     <div class="col-md-3">
                        <label class="col-form-label">CV <span class="text-danger">*</span></label>
                        <div class="form-group">
                           <input type="file" name="cv" id="cv" class="form-control">
                           <div class="cv_error all_errors"></div>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <label class="col-form-label">Current CV</label>
                        @if(isset($settings) && $settings->cv)
                        <p>{{ $settings->cv }}</p>
                        <a href="{{ asset('upload/user/cv/' . $settings->cv) }}" target="_blank">View CV</a>
                        @else
                        <p>No CV uploaded</p>
                        @endif
                     </div>
                  </div> -->
                  <div class="row">
                     <div class="col-md-6">
                        <label class="col-form-label">API Key <span class="text-danger">*</span></label>
                        <div class="form-group row">
                           <!-- <textarea name="api_key" id="api_key" rows="10">{{ optional($settings)->api_key }}</textarea> -->
                           <input type="text" name="api_key" id="api_key" value="{{ optional($settings)->api_key }}">
                           
                           <div class="api_key_error all_errors"></div>
                        </div>
                     </div>
                     
                     <div class="col-md-3"></div>
                  </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div class="text-center ">
            <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">
            {{ isset($settings) ? 'Update' : 'Save' }}
            </button>&nbsp;&nbsp;
            </div>
            </div>
            </div>
            {{-- <x-forms.tinymce-editor/> --}}
            </form>
         </div>

         <div class="row">
            <div class="col" id="form_container">
               <form id="save_custom_field" action="{{route('set_customfield_keys')}}" method="post">
               @csrf
               <div class="col-md-6">
                        <label class="col-form-label">Cv Key <span class="text-danger">*</span></label>
                        <div class="form-group row">
                           <!-- <textarea name="api_key" id="api_key" rows="10">{{ optional($settings)->api_key }}</textarea> -->
                           <input type="text" name="cv_value" id="cv_value" value="{{ optional($settings)->cv_value }}">
                           
                           <div class="cv_value_error all_errors text-danger"></div>
                          
                        </div>
               </div>
               <div class="col-md-6">
                        <label class="col-form-label">Cover Letter Key <span class="text-danger">*</span></label>
                        <div class="form-group row">
                           <!-- <textarea name="api_key" id="api_key" rows="10">{{ optional($settings)->api_key }}</textarea> -->
                           <input type="text" name="cover_letter_value" id="cover_letter_value" value="{{ optional($settings)->cover_letter_value }}">
                           
                           <div class="cover_letter_value_error all_errors"></div>
                        </div>
               </div>

            <div class="row">
            <div class="col-md-6">
            <div class="text-center ">
            <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">
                  Get Keys
            </button>&nbsp;&nbsp;   
            </div>
            </div>
            </div>
            </form>
            </div>
         </div>
         <div class="col-lg-6">
            <div class="form-group row mt-4">
               <div class="col-md-12">
                  <a href="JavaScript:void(0)" class="btn btn-primary mb-3" id="copy_code">Copy Code</a>
                  <div class="form-group row">
                  <textarea name="hash_code" id="hash_code" rows="18"></textarea>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row mb-5">
      </div>
      <!-- /Page Header -->
   </div>
</div>
<!-- /Page Wrapper -->
@endsection