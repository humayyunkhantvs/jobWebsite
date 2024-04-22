<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Job;
use App\Models\Category;
use App\Models\Template;
use App\Models\Team;
use App\Models\Setting;
use App\Models\User;
use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;




class JobsController extends Controller
{
    //
    public function index()
    {

        $categories = Category::all();

        $jobs = Job::all();

        $userRole = auth()->user()->role;
        $adminUsers = [];
        if ($userRole == 'superadmin') {
            $adminUsers = User::where('role', 'admin')->get();
        }
        return view('admin.pages.jobs.index', compact('categories', 'jobs', 'userRole', 'adminUsers'));
    }

    public function getUsersByAdmin($adminId)
    {
        // Fetch users associated with the selected admin from the teams table
        $users = Team::where('admin_id', $adminId)->pluck('user_id');

        // Assuming User is the model for your users table
        $users = User::whereIn('id', $users)->get();

        return response()->json(['users' => $users]);
    }

    public function add_jobs(Request $request)
    {
        $data = [];
        $rules = array(
            'title' => 'required',
            'company' => 'required',
            'category_id' => 'required',
        );
        $messages = array(
            'title.required' => 'Title is required',
            'company.required' => 'Company is required',
            'category_id.required' => 'Category is required',
        );
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $job = new Job();
            $job->title = $request->title;
            $job->user_id = Auth::user()->id;
            $job->address = $request->address;
            // $job->email = $request->email;
            // $job->website = $request->website;
            // $job->phone = $request->phone;
            $job->company = $request->company;
            $job->category_id = $request->category_id;
            $job->city = $request->city;
            $job->zip = $request->zip;
            $job->state = $request->state;
            $job->salary = $request->salary;
            $job->type = $request->type;
            $job->deadline = $request->deadline;
            $job->published_at = $request->published_date;
            $job->description = $request->description;
            $job->job_status = $request->status;
            $job->save();

            $data['response'] = true;
            $data['message'] = "Job Added Successfully";
        }
        return response()->json($data);
    }

    public function job_edit($id)
    {

        $data = [];
        if (Job::where('id', base64_decode($id))->exists()) {
            $job_data = Job::where('id', base64_decode($id))->first();


            return response()->json([
                'result' => 'SUCCESS',
                'data' => $job_data,
            ], 200);
        } else {
            return response()->json([
                'result' => 'ERROR',
                'message' => 'Blog not found against this ID.'
            ], 404);
        }
    }

    public function job_delete($id)
    {
        $data = [];
        if (Job::where('id', base64_decode($id))->exists()) {
            $job = Job::where('id', base64_decode($id))->first();
            $job->delete();
            $data['response'] = true;
            $data['message'] = "Job Deleted Successfully";
        } else {
            $data['response'] = false;
            $data['message'] = "Job Not Found";
        }
        return response()->json($data);
    }

    public function update_job(Request $request)
    {
        $data = [];
        $rules = array(
            'title' => 'required',
            'company' => 'required',
            'category_id' => 'required',
        );
        $messages = array(
            'title.required' => 'Title is required',
            'company.required' => 'Company is required',
            'category_id.required' => 'Category is required',
        );
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $job = Job::find($request->input('id'));
            $job->title = $request->title;
            $job->address = $request->address;
            $job->email = $request->email;
            $job->website = $request->website;
            $job->phone = $request->phone;
            $job->company = $request->company;
            $job->category_id = $request->category_id;
            $job->city = $request->city;
            $job->zip = $request->zip;
            $job->state = $request->state;
            $job->salary = $request->salary;
            $job->type = $request->type;
            $job->deadline = $request->deadline;
            $job->published_at = $request->published_date;
            $job->description = $request->description;
            $job->job_status = $request->status;

            $job->save();
            $data['response'] = true;
            $data['message'] = "Job Updated Successfully";
        }
        return response()->json($data);
    }

    public function job_datatable(Request $request)
    {
        $table_data = [];
        $data = [];

        $offset = $request->get('start');
        $limit = $request->get('length');
        $draw = $request->get('draw');

        $search = $request->get('search');
        $search_value = $search['value'];

        $order = $request->get('order');
        $job_status_filter = $request->get('job_status');
        $column_order_index = $order[0]['column'];
        $column_order_direction = $order[0]['dir'];

        $columns = $request->get('columns');
        $column_data = $columns[$column_order_index]['data'];

        $user = Auth::user();
        $user_id = $user->id;

        $total_records = 0;
        $total_filtered_records = 0;

        $jobs = Job::query();

        if ($user->role == 'superadmin') {

            $admin_id = $request->input('admin_id');
            $user_id_filter = $request->input('user_id');

            $admin_id = $request->get('admin_id');
            $user_id_filter = $request->get('user_id');


            if (!empty($admin_id) && empty($user_id_filter)) {
                $jobs = $jobs->where('user_id', $admin_id);
            }

            if (!empty($user_id_filter)) {
                $jobs = $jobs->where('user_id', $user_id_filter);
            }

            $total_records = Job::count();
            $total_filtered_records = $jobs->count();
        } else {

            $total_records = Job::where('user_id', $user_id)->count();
            $total_filtered_records = Job::where('user_id', $user_id)->count();
            $jobs = $jobs->where('user_id', $user_id);
        }

        if (!empty($job_status_filter)) {
            $jobs = $jobs->where('job_status', $job_status_filter);
        }

        if (!empty($search_value)) {
            $jobs = $jobs->where('title', 'like', '%' . $search_value . '%');
        }


        $jobs_data = $jobs->orderBy($column_data, $column_order_direction)->offset($offset)->limit($limit)->get();

        foreach ($jobs_data as $key => $val) {
            $table_data['title'] = $val['title'];
            $table_data['description'] = $val['description'];
            $table_data['published_at'] = $val['published_at'];
            $table_data['company'] = $val['company'];
            $table_data['action'] = '<div class="edit-delete-btn">
            <a title="Edit" href="javascript:void(0)" class="text-success editjob" rel="' . '/job_edit/' . base64_encode($val['id']) . '"><i class="feather-edit-3 me-1"></i></a>
            <a title="Delete" href="javascript:void(0)" class="text-danger deletejob" rel="' . '/job_delete/' . base64_encode($val['id']) . '"><i class="feather-trash-2 me-1"></i></a>
            </div>';
            $data[] = $table_data;
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_filtered_records,
            'data' => $data,
        ]);
    }

    public function categories()
    {
        $userRole = auth()->user()->role;
        return view('admin.pages.jobs.categories', compact('userRole'));
    }

    public function add_category(Request $request)
    {
        $data = [];
        $rules = array(
            'name' => 'required'
        );
        $messages = array(
            'name.required' => 'Name is required'
        );
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $Category = new Category();
            $Category->user_id = Auth::user()->id;
            $Category->name = $request->name;
            $Category->save();
            $data['response'] = true;
            $data['message'] = "Category Added Successfully";
        }
        return response()->json($data);
    }

    public function category_edit($id)
    {

        $data = [];
        if (Category::where('id', base64_decode($id))->exists()) {
            $category_data = Category::where('id', base64_decode($id))->first();


            return response()->json([
                'result' => 'SUCCESS',
                'data' => $category_data,
            ], 200);
        } else {
            return response()->json([
                'result' => 'ERROR',
                'message' => 'Category not found against this ID.'
            ], 404);
        }
    }
    public function update_category(Request $request)
    {
        $data = [];
        $rules = array(
            'name' => 'required',
        );
        $messages = array(
            'name.required' => 'Name is required',
        );
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $Category = Category::find($request->input('id'));
            $Category->name = $request->name;
            $Category->save();
            $data['response'] = true;
            $data['message'] = "Category Updated Successfully";
        }
        return response()->json($data);
    }

    public function category_delete($id)
    {
        $data = [];
        if (Category::where('id', base64_decode($id))->exists()) {
            $Category = Category::where('id', base64_decode($id))->first();
            $Category->delete();
            $data['response'] = true;
            $data['message'] = "Category Deleted Successfully";
        } else {
            $data['response'] = false;
            $data['message'] = "Category Not Found";
        }
        return response()->json($data);
    }

    public function category_datatable(Request $request)
    {
        $table_data = [];
        $data = [];

        $offset = $request->get('start');
        $limit = $request->get('length');
        $draw = $request->get('draw');

        $search = $request->get('search');
        $search_value = $search['value'];

        $order = $request->get('order');
        $column_order_index = $order[0]['column'];
        $column_order_direction = $order[0]['dir'];
        $columns = $request->get('columns');
        $column_data = $columns[$column_order_index]['data'];
        $total_records = 0;
        $total_filtered_records = 0;

        $categories = Category::query();
        $total_records = $categories->count();
        $total_filtered_records = $categories->count();
        if (!empty($search_value)) {
            $categories = $categories->where('name', 'like', '%' . $search_value . '%');
        }

        $categories_data = $categories->orderBy($column_data, $column_order_direction)->offset($offset)->limit($limit)->get();

        foreach ($categories_data as $key => $val) {
            $table_data['name'] = $val['name'];
            $table_data['action'] = '<div class="edit-delete-btn">
            <a title="Edit" href="javascript:void(0)" class="text-success editcategory" rel="' . '/category_edit/' . base64_encode($val['id']) . '"><i class="feather-edit-3 me-1"></i></a>
            <a title="Delete" href="javascript:void(0)" class="text-danger deletecategory" rel="' . '/category_delete/' . base64_encode($val['id']) . '"><i class="feather-trash-2 me-1"></i></a>
            </div>';
            $data[] = $table_data;
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_filtered_records,
            'data' => $data,
        ]);
    }

    public function get_template()
    {
        return view('admin.pages.jobs.template');
    }

    public function add_template(Request $request)
    {
        $data = [];
        $rules = array(
            'title' => 'required',
            'status' => 'required',
            'image' => 'required',
            'color' => 'required',
        );
        $messages = array(
            'title.required' => 'Title is required',
            'status.required' => 'Status is required',
            'color.required' => 'Color is required',
            'image.required' => 'Image is required'
        );
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {

            $user_id = Auth::user()->id;
            $new_status = $request->status;

            if ($new_status == '1') {
                // Deactivate all other templates of the user
                Template::where('user_id', $user_id)->where('status', '1')->update(['status' => '0']);
            }
            $Template = new Template();
            $Template->title = $request->title;
            $Template->color = $request->color;
            $Template->status = $new_status;
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $filename = now()->timestamp . '_' . $filename;
            $path = public_path('upload/');
            $file->move($path, $filename);
            $Template->image = $filename;
            $Template->user_id = $user_id;
            $Template->save();
            $data['response'] = true;
            $data['message'] = "Template Added Successfully";
        }
        return response()->json($data);
    }

    public function template_edit($id)
    {

        if (Template::where('id', base64_decode($id))->exists()) {
            $template_data = Template::where('id', base64_decode($id))->first();


            return response()->json([
                'result' => 'SUCCESS',
                'data' => $template_data,
            ], 200);
        } else {
            return response()->json([
                'result' => 'ERROR',
                'message' => 'Template not found against this ID.'
            ], 404);
        }
    }
    public function update_template(Request $request)
    {
        $data = [];
        $rules = array(
            'title' => 'required',
        );
        $messages = array(
            'title.required' => 'Title is required',
        );
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $template = Template::find($request->input('id'));
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload'), $imageName);

                $template->image = $imageName;
            }
            $template->title = $request->title;
            $template->save();
            $data['response'] = true;
            $data['message'] = "Template Updated Successfully";
        }
        return response()->json($data);
    }

    public function template_delete($id)
    {
        $data = [];
        if (Template::where('id', base64_decode($id))->exists()) {
            $Template = Template::where('id', base64_decode($id))->first();
            $Template->delete();
            $data['response'] = true;
            $data['message'] = "Template Deleted Successfully";
        } else {
            $data['response'] = false;
            $data['message'] = "Template Not Found";
        }
        return response()->json($data);
    }

    public function template_datatable(Request $request)
    {
        $table_data = [];
        $data = [];

        $offset = $request->get('start');
        $limit = $request->get('length');
        $draw = $request->get('draw');

        $search = $request->get('search');
        $search_value = $search['value'];

        $order = $request->get('order');
        $column_order_index = $order[0]['column'];
        $column_order_direction = $order[0]['dir'];
        $columns = $request->get('columns');
        $column_data = $columns[$column_order_index]['data'];
        $total_records = 0;
        $total_filtered_records = 0;

        $user_id = auth()->user()->id;

        $templates = Template::where('user_id', $user_id);
        $total_records = $templates->count();
        $total_filtered_records = $templates->count();

        if (!empty($search_value)) {
            $templates = $templates->where('title', 'like', '%' . $search_value . '%');
        }

        $templates_data = $templates->orderBy($column_data, $column_order_direction)->offset($offset)->limit($limit)->get();

        foreach ($templates_data as $key => $val) {
            $table_data['title'] = $val['title'];
            $table_data['action'] = '<div class="edit-delete-btn">
            <a title="Edit" href="javascript:void(0)" class="text-success edittemplate" rel="' . '/template_edit/' . base64_encode($val['id']) . '"><i class="feather-edit-3 me-1"></i></a>
            <a title="Delete" href="javascript:void(0)" class="text-danger deletetemplate" rel="' . '/template_delete/' . base64_encode($val['id']) . '"><i class="feather-trash-2 me-1"></i></a>
            </div>';
            $data[] = $table_data;
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_filtered_records,
            'data' => $data,
        ]);
    }

    //settings function

    public function settings(Request $request)
    {
        $user_id = auth()->user()->id;

        // Check if settings exist for the logged-in user
        $settings = Setting::where('user_id', $user_id)->first();

        // $hash = '7kNZc3s5w08vSyoIXQLv';

        // if (User::where('hash', $hash)->exists()) {
        //     $user = User::where('hash', $hash)->first();
        //     $whereArray = [];
        //     $whereArray[] = ['jobs.user_id', '=', $user->id];

        //     if (Job::where('user_id', $user->id)->exists()) {

        //         if ($request->filled('category')) {
        //             $whereArray[] = ['jobs.category_id', '=', trim($request->category)];
        //         }

        //         if ($request->filled('location')) {
        //             $location = explode(",", $request->location);
        //             // $whereArray[] = ['jobs.city', '=', trim($location[0])];
        //             $whereArray[] = ['jobs.state', '=', trim($location[0])];
        //         }

        //         if ($request->filled('search_term')) {
        //             $whereArray[] = ['jobs.title', 'like', '%' . $request->search_term . '%'];
        //         }

        //         if ($request->filled('sort')) {
        //             $sort = $request->sort;
        //         } else {
        //             $sort = 'ASC';
        //         }

        //         $template = Template::where('user_id', $user->id)->where('status', 1)->first();
        //         // $imageName = Template::where('user_id', $user->id)->value('image');
        //         $backgroundcolor = $template->color;
        //         $imageName = $template->image;


        //         $allCategories = Category::all();

        //         $jobs = Job::join('categories', 'jobs.category_id', '=', 'categories.id')->where($whereArray)->where('jobs.job_status', 'published')->orderBy('jobs.title', $sort)->select('jobs.*', 'categories.name as category_name')->get();

        //         // Generate the HTML content
        //         $htmlContent = view('admin.pages.settings',compact('jobs', 'imageName','backgroundcolor', 'allCategories','settings'))->render();

        //         return view('admin.pages.settings',compact('jobs', 'imageName','backgroundcolor', 'allCategories','settings','htmlContent'));
        //         // return response()->json([
        //         //     'message' => 'Jobs found!',
        //         //     'data' => jobs_html($jobs, $hash, $allCategories, $imageName),
        //         // ], 200);
        //     } else {
        //         return response()->json([
        //             'message' => 'Jobs not found!'
        //         ], 404);
        //     }
        // } else {
        //     return response()->json([
        //         'message' => 'User not found!'
        //     ], 404);
        // }

        return view('admin.pages.settings', compact('settings'));
    }

    public function add_settings(Request $request)
    {
        $data = [];
        $rules = array(
            'api_key' => 'required',
            // 'cv' => 'required',
            // 'cover_letter' => 'required',
        );
        $messages = array(
            'api_key.required' => 'API KEY is required',
            // 'cv.required' => 'CV is required',
            // 'cover_letter.required' => 'Cover letter is required'
        );
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $setting = new Setting();
            $setting->api_key = $request->api_key;
            // $setting->cv = $request->cv;
            // $setting->cover_letter = $request->cover_letter;
            // $file = $request->file('cv');
            // $filename = $file->getClientOriginalName();
            // $filename = now()->timestamp . '_' . $filename;
            // $path = public_path('upload/user/cv');
            // $file->move($path, $filename);
            // $setting->cv = $filename;
            $setting->user_id = Auth::user()->id;
            $setting->save();
            $data['response'] = true;
            $data['message'] = "Form Submitted Successfully";
        }
        return response()->json($data);
    }


    public function setting_edit($id)
    {

        if (Setting::where('id', base64_decode($id))->exists()) {
            $setting_data = Setting::where('id', base64_decode($id))->first();


            return response()->json([
                'result' => 'SUCCESS',
                'data' => $setting_data,
            ], 200);
        } else {
            return response()->json([
                'result' => 'ERROR',
                'message' => 'Template not found against this ID.'
            ], 404);
        }
    }


    public function auth_key($api_key)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://rest.gohighlevel.com/v1/custom-fields',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $api_key
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public function update_setting(Request $request)
    {
        $data = [];
        $rules = array(
            'api_key' => 'required',
        );
        $messages = array(
            'api_key.required' => 'Api Key is required',
        );
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {


            $api_key = $request->input('api_key');

            $response = $this->auth_key($api_key);

            if (!empty($response->msg) && $response->msg === 'Api key is invalid.') {
                $data['response'] = false;
                $data['message'] = 'Invalid API key. Please provide a valid API key.';
            } else {

                $setting = Setting::find($request->input('id'));




                // if ($request->hasFile('cv')) {
                //     $image = $request->file('cv');
                //     $imageName = time() . '.' . $image->getClientOriginalExtension();
                //     $image->move(public_path('upload/user/cv'), $imageName);

                //     $setting->cv = $imageName;
                // }
                $setting->api_key = $request->api_key;
                $setting->save();
                $data['response'] = true;
                $data['message'] = "Data Updated Successfully";
            }
        }
        return response()->json($data);
    }

    public function setting_delete($id)
    {
        $data = [];
        if (Setting::where('id', base64_decode($id))->exists()) {
            $setting = Setting::where('id', base64_decode($id))->first();
            $setting->delete();
            $data['response'] = true;
            $data['message'] = "Data Deleted Successfully";
        } else {
            $data['response'] = false;
            $data['message'] = "Api Key Not Found";
        }
        return response()->json($data);
    }


    public function get_applicants()
    {
        $jobs = Job::all();

        $categories = Category::all();

        return view('admin.pages.jobs.applicants', compact('jobs', 'categories'));
    }


    public function get_applicant_details($id)
    {

        // dd($id);
        $applicants = Applicant::find($id);

        return response()->json([
            'response' => true,
            'data' => $applicants,
        ]);
    }

    public function applicants_datatable(Request $request)
    {
        // dd("123123");
        $table_data = [];
        $data = [];

        $offset = $request->get('start');
        $limit = $request->get('length');
        $draw = $request->get('draw');

        $search = $request->get('search');
        $search_value = $search['value'];

        $order = $request->get('order');
        $categoryId = $request->get('category_id');
        $column_order_index = $order[0]['column'];
        $column_order_direction = $order[0]['dir'];
        $columns = $request->get('columns');
        $column_data = $columns[$column_order_index]['data'];
        $total_records = 0;
        $total_filtered_records = 0;

        $user_id = auth()->user()->id;

        $applicants = Applicant::where('user_id', $user_id);

        if (!empty($categoryId)) {
            $applicants = $applicants->join('categories', 'applicants.category_id', '=', 'categories.id')
                ->where('categories.id', $categoryId)
                ->select('applicants.*');
        }

        $total_records = $applicants->count();
        $total_filtered_records = $applicants->count();


        if (!empty($search_value)) {
            $applicants = $applicants->where('first_name', 'like', '%' . $search_value . '%');
        }

        $applicants_data = $applicants->orderBy($column_data, $column_order_direction)->offset($offset)->limit($limit)->get();

        foreach ($applicants_data as $key => $val) {
            $table_data['first_name'] = $val['first_name'];
            $table_data['last_name'] = $val['last_name'];
            $table_data['email'] = $val['email'];
            $table_data['action'] = '<div>
            <button data-bs-toggle="modal" data-bs-target="#view-applicant-modal" rel="' . route('get_applicant_details', $val['id']) . '" class="btn btn-primary view-applicant">View</button>
            <a href="' . asset('upload/user/' . $val['cv']) . '" download>Download CV</a>
            </div>';
            // $table_data['first_name'] = $val['first_name'];
            $data[] = $table_data;
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_filtered_records,
            'data' => $data,
        ]);
    }

    public function set_customfield_keys(Request $request)
    {

        $data = [];
        $rules = array(
            'cv_value' => 'required',
            'cover_letter_value' => 'required',
        );
        $messages = array(
            'cv_value.required' => 'Cv value is required',
            'cover_letter_value.required' => 'Cover letter value is required',
        );
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            // checks
            // if (strpos($request->cv_value, 'contact.') === 0) {
            //     $cv_value = $request->cv_value;
            // } else {
            //     $cv_value = 'contact.' . $request->cv_value;
            // }

            // if (strpos($request->cover_letter_value, 'contact.') === 0) {
            //     $cover_letter_value = $request->cover_letter_value;
            // } else {
            //     $cover_letter_value = 'contact.' . $request->cover_letter_value;
            // }

            // Get API key
            $user_id = Auth::user()->id;

            $setting = Setting::where('user_id', $user_id)->first();

            $api_key = $setting->api_key;


            // Get custom fields
            $response = $this->get_ghl_customfields($api_key);

            $response = json_decode($response, true);



            if (isset($response['customFields']) && !empty($response['customFields'])) {
                $cv_upload_key = null;
                $cover_letter_key = null;
                $cv_value = $request->input('cv_value');
                $cover_letter_value = $request->input('cover_letter_value');
                $cv_value_concatenated = strpos($cv_value, 'contact.') === 0 ? $cv_value : 'contact.' . $cv_value;
                $cover_letter_value_concatenated = strpos($cover_letter_value, 'contact.') === 0 ? $cover_letter_value : 'contact.' . $cover_letter_value;


                // Check if cv_value and cover_letter_value match
                $cv_match = false;
                $cover_letter_match = false;

                foreach ($response['customFields'] as $field) {
                    if ($field['fieldKey'] === $cv_value_concatenated) {
                        $cv_upload_key = $field['id'];
                        $cv_match = true;
                    }

                    if ($field['fieldKey'] === $cover_letter_value_concatenated) {
                        $cover_letter_key = $field['id'];
                        $cover_letter_match = true;
                    }
                }


                if (!$cv_match || !$cover_letter_match) {

                    // $data['response'] = false;
                    $data['error'] = "CV and cover letter values must match with GHL fields";
                    // return response()->json($data['error'] = 'CV and cover letter values must match');
                } else {
                    // If cv_value and cover_letter_value match, proceed to save to database
                    $setting->cv_value = $cv_value_concatenated;
                    $setting->cover_letter_value = $cover_letter_value_concatenated;
                    $setting->ghl_cover_letter_key = $cover_letter_key;
                    $setting->ghl_cv_key = $cv_upload_key;
                    $setting->save();


                    $data['response'] = true;
                    $data['message'] = "Data posted Successfully";
                }
            }



            return response()->json($data);
        }
    }

    // public function filterCustomFields($response){

    //     // $cv_upload_key = null;
    //     // $cover_letter_key = null;

    //     if (isset($response['customFields']) && !empty($response['customFields'])) {
    //         foreach ($response['customFields'] as $field) {
    //             if ($field['fieldKey'] === 'contact.cv_upload') {
    //                 $cv_upload_key = $field['id'];
    //             }

    //             if ($field['fieldKey'] === 'contact.cover_letter') {
    //                 $cover_letter_key = $field['id'];
    //             }
    //         }
    //     }


    // }


    //     public function set_ghl_customfield_keys($cv_upload_key, $cover_letter_key) 
    //     {

    //     $user_id = Auth::user()->id;
    //     // $cv_value = $request->input('cv_value');
    //     // $cover_letter_value = $request->input('cover_letter_value');

    //     // print_r($cv_value);
    //     // exit;


    //     if ($cv_upload_key !== null || $cover_letter_key !== null) {
    //         Setting::where('user_id', $user_id)->update([
    //            'ghl_cv_key' => $cv_upload_key,
    //            'ghl_cover_letter_key' => $cover_letter_key,
    //         //    'cv_value' => $cv_value, 
    //         //     'cover_letter_value' => $cover_letter_value 
    //        ]);
    //    }

    //     }

    public function get_ghl_customfields($api_key)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://rest.gohighlevel.com/v1/custom-fields/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER =>   array(
                'Authorization: Bearer ' . $api_key
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;

        // $response = json_decode($response, true);

        // $this->set_ghl_customfield_keys($cv_upload_key, $cover_letter_key);

    }



    // public function setting_datatable(Request $request)
    // {
    //     $table_data = [];
    //     $data = [];

    //     $offset = $request->get('start');
    //     $limit = $request->get('length');
    //     $draw = $request->get('draw');

    //     $search = $request->get('search');
    //     $search_value = $search['value'];

    //     $order = $request->get('order');
    //     $column_order_index = $order[0]['column'];
    //     $column_order_direction = $order[0]['dir'];
    //     $columns = $request->get('columns');
    //     $column_data = $columns[$column_order_index]['data'];
    //     $total_records = 0;
    //     $total_filtered_records = 0;

    //     $user_id = auth()->user()->id;

    //     $settings = Setting::where('user_id', $user_id);
    //     $total_records = $settings->count();
    //     $total_filtered_records = $settings->count();

    //     if (!empty($search_value)) {
    //         $settings = $settings->where('api_key', 'like', '%' . $search_value . '%');
    //     }

    //     $settings_data = $settings->orderBy($column_data, $column_order_direction)->offset($offset)->limit($limit)->get();

    //     foreach ($settings_data as $key => $val) {
    //         $table_data['api_key'] = $val['api_key'];
    //         $table_data['action'] = '<div class="edit-delete-btn">
    //         <a title="Edit" href="javascript:void(0)" class="text-success editsetting" rel="' . '/setting_edit/' . base64_encode($val['id']) . '"><i class="feather-edit-3 me-1"></i></a>
    //         <a title="Delete" href="javascript:void(0)" class="text-danger deletesetting" rel="' . '/setting_delete/' . base64_encode($val['id']) . '"><i class="feather-trash-2 me-1"></i></a>
    //         </div>';
    //         $data[] = $table_data;
    //     }

    //     return response()->json([
    //         'draw' => $draw,
    //         'recordsTotal' => $total_records,
    //         'recordsFiltered' => $total_filtered_records,
    //         'data' => $data,
    //     ]);
    // }
}
