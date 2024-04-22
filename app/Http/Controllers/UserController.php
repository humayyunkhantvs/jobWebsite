<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Applicant;
use App\Models\Setting;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function apply_job(Request $request)
    {

        $data = [];
        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:applicants',
            'dob' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'cv' => 'required',
            'cover_letter' => 'required',

        );
        $messages = array(
            'first_name.required' => 'First Name is required',
            'last_name.required' => 'Last Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'address.required' => 'Address is required',
            'city.required' => 'City is required',
            'state.required' => 'State is required',
            'zip_code.required' => 'Zip Code is required',
            'cv.required' => 'CV is required',
            'cover_letter.required' => 'Cover Letter is required',
        );
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $userSettings = Setting::where('user_id', $request->user_id)->first();
            $api_key = $userSettings->api_key;
            $cv_key = $userSettings->ghl_cv_key;
            // $cover_letter_key = $userSettings->ghl_cover_letter_key;

            // changes to be made
            $jobDetails = Job::where('id', $request->job_id)->first();

            $applicant = new Applicant();
            $applicant->first_name = $request->first_name;
            $applicant->last_name = $request->last_name;
            $applicant->user_id = $request->user_id;
            $applicant->job_id = $request->job_id;
            $applicant->job_title = $request->job_title;
            $applicant->category_id = $jobDetails->category_id;
            $applicant->address = $request->address;
            $applicant->email = $request->email;
            $applicant->city = $request->city;
            $applicant->phone = $request->phone;
            $applicant->state = $request->state;
            $applicant->dob = $request->dob;
            $applicant->city = $request->city;
            $applicant->zip_code = $request->zip_code;
            $applicant->cover_letter = $request->cover_letter;
           
            $file = $request->file('cv');
            $filename = $file->getClientOriginalName();
            $filename = now()->timestamp . '_' . $filename;
            $path = public_path('upload/user/');
            $file->move($path, $filename);
            $applicant->cv = $filename;

            $applicant->save();
            $lastInsertedId = $applicant->id;
            $this->get_applicant($applicant, $lastInsertedId,$api_key,$cv_key);
            $data['response'] = true;
            $data['message'] = "Application Submitted Successfully";
        }
        return response()->json($data);
    }

    public function get_applicant($applicant, $lastInsertedId,$api_key,$cv_key)
    {
       


        $applicant_id = $lastInsertedId;
        $first_name = $applicant->first_name;
        $last_name = $applicant->last_name;
        $email = $applicant->email;
        $phone = $applicant->phone;
        $address = $applicant->address;
        $city = $applicant->city;
        $state = $applicant->state;
        $zip_code = $applicant->zip_code;
        $dob = $applicant->dob;
        $cover_letter = $applicant->cover_letter;

        $cv = $applicant->cv;
        $cv = asset('/upload/user') . '/' . $cv;

        if (!empty($phone)) {
            $phone =
                "+1" . preg_replace("/[^0-9]/", "", $phone);
        } else {
            $phone = null;
        }

        $custom_fields = array(

            $cv_key => $cv,
            // $cover_letter_key => $cover_letter,
        );
        $post_data = [
            "email" => $email,
            "phone" => $phone,
            "firstName" => $first_name,
            "lastName" => $last_name,
            "name" => $first_name,
            "address1" => $address,
            "city" => $city,
            "state" => $state,
            "dateOfBirth" => $dob,
            "country" => "United States",
            "postalCode" => $zip_code,
            "customField" => $custom_fields,

        ];

        $this->create_ghl_contact($post_data, $applicant_id,$api_key);
    }

    public function create_ghl_contact($post_data, $applicant_id,$api_key)
    {

    // $api_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJsb2NhdGlvbl9pZCI6ImRhN1luMWdadlZ1a0N0MmhPNzBZIiwiY29tcGFueV9pZCI6IllUS2lUZTNnOVNYZDZydFo0UnVPIiwidmVyc2lvbiI6MSwiaWF0IjoxNjM3MzQ0OTIzMzAwLCJzdWIiOiI4WmZhNmI4bUc4RGpzQkNNcUdOZyJ9.0Ndp2WuZJzpYx9oQ0ol4YJtyC1M2edsxvlBN1lVKHtU';
   

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://rest.gohighlevel.com/v1/contacts/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($post_data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $response = json_decode($response, true);
        // return $response;
        if (isset($response['contact'])) {
        Applicant::where('id', $applicant_id)->update([
            'ghl_id' => $response['contact']['id'],
            'ghl_post_data' => json_encode($post_data),
            'ghl_response' => $response,
            
            ]);
        }
       
    }

    // public function auth_key($token)
    // {
    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => 'https://rest.gohighlevel.com/v1/custom-fields',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'GET',
    //         CURLOPT_HTTPHEADER => array(
    //             'Authorization: Bearer ' . $token
    //         ),
    //     ));

    //     $response = curl_exec($curl);
    //     curl_close($curl);
    //     return json_decode($response);
    // }

    
}
