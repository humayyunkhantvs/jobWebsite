<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Team;
use Illuminate\Auth\Events\Verified;

class TeamsController extends Controller
{
    //
    public function index()
    {
        $userRole = auth()->user()->role;
        return view('admin.pages.teams.index', compact('userRole'));
    }

    public function add_team(Request $request)
    {
        $data = [];
        $rules = array(
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
        );
        $messages = array(
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        );
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->hash = hash_token();
            $user->role = 'admin';
            $user->save();

            $user->sendEmailVerificationNotification();
            $lastInsertedUserId = $user->id;
            $team = new Team();
            $team->user_id = $lastInsertedUserId;
            $team->admin_id = Auth::user()->id;
            $team->save();
            $data['response'] = true;
            $data['message'] = "User added successfully";
        }

        return response()->json($data);
    }

    public function team_datatable(Request $request)
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

        $user = auth()->user();

        $users = User::query();


        if ((auth()->user()->role === 'superadmin')) {

            $total_records = $users->whereIn('role', ['admin', 'user'])->count();
            $users->whereIn('role', ['admin', 'user']);
        } elseif ((auth()->user()->role === 'admin')) {

            $adminId = $user->id;
            $userIds = Team::where('admin_id', $adminId)->pluck('user_id')->toArray();
            $total_records = $users->whereIn('id', $userIds)->count();
            $users->whereIn('id', $userIds);
        }

        if (!empty($search_value)) {
            $users = $users->where('name', 'like', '%' . $search_value . '%')->orWhere('email', 'like', '%' . $search_value . '%');
        }

        $total_filtered_records = $users->count();

        $users_data = $users->orderBy($column_data, $column_order_direction)->offset($offset)->limit($limit)->get();

        foreach ($users_data as $key => $val) {
            $table_data['name'] = $val['name'];
            $table_data['email'] = $val['email'];
            $table_data['reset_password'] = '<button onclick="sendResetLink(\'' . $val['email'] . '\')" class="btn btn-primary">Send Reset Link</button>';
            $table_data['action'] = '<div class="edit-delete-btn">
                    <a title="Edit" href="javascript:void(0)" class="text-success editteam" rel="' .'/team_edit/'. base64_encode($val['id']) . '"><i class="feather-edit-3 me-1"></i></a>
                    <a title="Delete" href="javascript:void(0)" class="text-danger deleteteam" rel="' . '/team_delete/'. base64_encode($val['id']) . '"><i class="feather-trash-2 me-1"></i></a>
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

    public function team_edit($id)
    {
        $data = [];
        if (User::where('id', base64_decode($id))->exists()) {
            $user_data = User::where('id', base64_decode($id))->first();
            return response()->json([
                'result' => 'SUCCESS',
                'data' => $user_data,
            ], 200);
        } else {
            return response()->json([
                'result' => 'ERROR',
                'message' => 'User not found against this ID.'
            ], 404);
        }
    }

    public function update_team(Request $request)
    {
        $data = [];
        $id = $request->input('id');
        $rules = array(
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id), 'unique_email:users,email,' . $request->input('email')],
        );
        $messages = array(
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
        );
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $user = User::find($request->input('id'));
            $user->name = $request->name;
            $user->email = $request->email;

            $user->save();
            $data['response'] = true;
            $data['message'] = "Team updated successfully";
        }

        return response()->json($data);
    }

    public function team_delete($id)
    {
        $data = [];
        if (User::where('id', base64_decode($id))->exists()) {
            $user = User::where('id', base64_decode($id))->first();
            $user->delete();
            $data['response'] = true;
            $data['message'] = "Team Deleted Successfully";
        } else {
            $data['response'] = false;
            $data['message'] = "Team Not Found";
        }
        return response()->json($data);
    }

    public function forgot_password_email(Request $request)
    {


        $rules = [

            'email' => 'required|email',

        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            // dd($request->all());
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'result' => 'SUCCESS',
                    'message' => __($status)
                ], 200);
            } else {
                return response()->json([
                    'result' => 'ERROR',
                    'message' => __($status)
                ], 404);
            }
        }
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('admin.pages.teams.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $data = [];
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|alpha_num|min:8|max:50',
            'confirm_password' => 'required|alpha_num|same:password||min:8|max:50',
        ]);

        if ($validator->fails()) {
            $data['errors'] = true;
            $data['errors_messages'] = $validator->messages();
        } else {

            $status = Password::reset(
                $request->only('email', 'password', 'confirm_password', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();
                }

            );
            $data['response'] = true;
            $data['resp_msg'] = "Password Updated Successfully";
        }

        return response()->json($data);
    }

    public function reset_password_process(Request $request)
    {



        $rules = [

            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password',

        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            $status = Password::reset(
                $request->only('token', 'email', 'password', 'confirm_password'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'result' => 'SUCCESS',
                    'message' => __($status),
                    // 'redirect_url' => route('login')
                ], 200);
            } else {
                return response()->json([
                    'result' => 'ERROR',
                    'message' => __($status)
                ], 404);
            }
        }
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (!$user || !hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect('/');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect()->route('login')->with('verified', true);
    }
}
