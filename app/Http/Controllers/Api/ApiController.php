<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Category;
use App\Models\Template;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Crypt;



class ApiController extends Controller
{

    public function index($hash, $jobId)
    {

        // print_r($hash);

        if (User::where('hash', $hash)->exists()) {
            $user = User::where('hash', $hash)->first();
            $whereArray = [];
            $whereArray[] = ['user_id', '=', $user->id];
            $whereArray[] = ['id', '=', $jobId];


            if (Job::where($whereArray)->exists()) {
                $job = Job::where($whereArray)->first();
                // Fetch category details based on category_id
                $category = Category::find($job->category_id);
                $categoryName =  $category->name;
                $job['category_name'] = $categoryName;
                $imageName = Template::where('user_id', $user->id)->value('image');
                $viewContent = View::make('jobs.details', compact('job', 'imageName', 'categoryName'))->render();
                return response()->json([
                    'status' => 200,
                    'html' => $viewContent,
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Job not found',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ], 404);
        }
    }


    public function jobs_detail(Request $request, $jobId)
    {

        $hash = '7kNZc3s5w08vSyoIXQLv';
        if (User::where('hash', $hash)->exists()) {
            $user = User::where('hash', $hash)->first();
            $whereArray = [];
            $whereArray[] = ['user_id', '=', $user->id];
            $whereArray[] = ['id', '=', base64_decode($jobId)];


            if (Job::where($whereArray)->exists()) {
                $job = Job::where($whereArray)->first();
                $category = Category::find($job->category_id);
                $categoryName =  $category->name;
                $job['category_name'] = $categoryName;
                $template = Template::where('user_id', $user->id)->where('status', 1)->first();
                $backgroundcolor = $template->color;
                $imageName = $template->image;
                return view('jobs.details', compact('job', 'imageName', 'backgroundcolor', 'categoryName'));
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Job not found',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ], 404);
        }
    }

    // public function jobs_detail(Request $request, $hash, $job){

    //     $jobId = base64_encode($job);

    //     if (User::where('hash', $hash)->exists()) {
    //         $user = User::where('hash', $hash)->first();
    //         $whereArray = [];
    //         $whereArray[] = ['user_id', '=', $user->id];
    //         $whereArray[] = ['id', '=', $jobId];


    //         if (Job::where($whereArray)->exists()) {
    //             $job = Job::where($whereArray)->first();
    //             // Fetch category details based on category_id
    //             $category = Category::find($job->category_id);
    //             $categoryName =  $category->name;
    //             $job['category_name'] = $categoryName;
    //             $imageName = Template::where('user_id', $user->id)->value('image');
    //             // $viewContent = View::make('jobs.test', compact('job', 'imageName', 'categoryName'))->render();
    //             return view('jobs.details',compact('job', 'imageName', 'categoryName'));
    //             // return response()->json([
    //             //     'status' => 200,
    //             //     'message' => 'Successfully loaded',
    //             //     'html' => $viewContent,
    //             // ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => 'Job not found',
    //             ], 404);
    //         }
    //     } else {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'User not found',
    //         ], 404);
    //     }

    // }



    public function show(Request $request, string $hash)
    {
        if (User::where('hash', $hash)->exists()) {
            $user = User::where('hash', $hash)->first();
            $whereArray = [];
            $whereArray[] = ['jobs.user_id', '=', $user->id];

            if (Job::where('user_id', $user->id)->exists()) {

                if ($request->filled('category')) {
                    $whereArray[] = ['jobs.category_id', '=', trim($request->category)];
                }

                if ($request->filled('location')) {
                    $location = explode(",", $request->location);
                    $whereArray[] = ['jobs.city', '=', trim($location[0])];
                    $whereArray[] = ['jobs.state', '=', trim($location[1])];
                }

                if ($request->filled('search_term')) {
                    $whereArray[] = ['jobs.title', 'like', '%' . $request->search_term . '%'];
                }

                if ($request->filled('sort')) {
                    $sort = $request->sort;
                } else {
                    $sort = 'ASC';
                }

                $imageName = Template::where('user_id', $user->id)->value('image');

                $allCategories = Category::all();

                $jobs = Job::join('categories', 'jobs.category_id', '=', 'categories.id')->where($whereArray)->where('jobs.job_status', 'published')->orderBy('jobs.title', $sort)->select('jobs.*', 'categories.name as category_name')->get();

                return response()->json([
                    'message' => 'Jobs found!',
                    'data' => jobs_html($jobs, $hash, $allCategories, $imageName),
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Jobs not found!'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'User not found!'
            ], 404);
        }
    }


    public function showJobs(Request $request)
    {
        if ($request->filled('hash') && $request->filled('id')) {
            // get only single job from the database
            $hash = $request->get('hash');
            $jobId = $request->get('id');

            if (User::where('hash', $hash)->exists()) {
                $user = User::where('hash', $hash)->first();
                $whereArray = [];
                $whereArray[] = ['user_id', '=', $user->id];
                $whereArray[] = ['id', '=', Crypt::decryptString($jobId)];


                if (Job::where($whereArray)->exists()) {
                    $job = Job::where($whereArray)->first();
                    $category = Category::find($job->category_id);
                    $categoryName =  $category->name;
                    $job['category_name'] = $categoryName;
                    $template = Template::where('user_id', $user->id)->where('status', 1)->first();
                    $backgroundcolor = $template->color;
                    $imageName = $template->image;

                    // Prepare the data to be returned as JSON
                    $jsonData = [
                        'message' => 'Jobs found!',
                        'jobs_html' => view('jobs.details', compact('job', 'imageName', 'backgroundcolor', 'categoryName'))->render(),
                    ];

                    return response()->json($jsonData, 200);
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Job not found',
                    ], 404);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found',
                ], 404);
            }
        } else {
            // Get all the jobs from the database
            $hash = $request->hash;
            if (User::where('hash', $hash)->exists()) {
                $user = User::where('hash', $hash)->first();
                $whereArray = [];
                $whereArray[] = ['jobs.user_id', '=', $user->id];

                if (Job::where('user_id', $user->id)->exists()) {

                    if ($request->filled('category')) {
                        $whereArray[] = ['jobs.category_id', '=', trim($request->category)];
                    }

                    if ($request->filled('location')) {
                        $location = explode(",", $request->location);
                        $whereArray[] = ['jobs.state', '=', trim($location[0])];
                    }

                    if ($request->filled('search_term')) {
                        $whereArray[] = ['jobs.title', 'like', '%' . $request->search_term . '%'];
                    }

                    if ($request->filled('sort')) {
                        $sort = $request->sort;
                    } else {
                        $sort = 'ASC';
                    }

                    $template = Template::where('user_id', $user->id)->where('status', 1)->first();
                    $backgroundcolor = $template->color;
                    $imageName = $template->image;

                    $allCategories = Category::all();

                    $jobs = Job::join('categories', 'jobs.category_id', '=', 'categories.id')->where($whereArray)->where('jobs.job_status', 'published')->orderBy('jobs.title', $sort)->select('jobs.*', 'categories.name as category_name')->get();

                    // Prepare the data to be returned as JSON
                    $jsonData = [
                        'message' => 'Jobs found!',
                        'jobs_html' => view('jobs.joblist', compact('jobs', 'imageName', 'backgroundcolor', 'allCategories', 'hash'))->render(),
                    ];

                    return response()->json($jsonData, 200);
                } else {
                    return response()->json(['message' => 'Jobs not found!'], 404);
                }
            } else {
                return response()->json(['message' => 'User not found!'], 404);
            }
        }
    }

    public function filterJobs(Request $request, $hash)
    {
        if (User::where('hash', $hash)->exists()) {
            $user = User::where('hash', $hash)->first();
            $whereArray = [];
            $whereArray[] = ['jobs.user_id', '=', $user->id];

            if ($request->filled('category')) {
                $whereArray[] = ['jobs.category_id', '=', trim($request->category)];
            }

            if ($request->filled('location')) {
                $location = explode(",", $request->location);
                $whereArray[] = ['jobs.state', '=', trim($location[0])];
            }

            if ($request->filled('search_term')) {
                $whereArray[] = ['jobs.title', 'like', '%' . $request->search_term . '%'];
            }

            if ($request->filled('sort')) {
                $sort = $request->sort;
            } else {
                $sort = 'ASC';
            }

            if (Job::where($whereArray)->exists()) {
                $template = Template::where('user_id', $user->id)->where('status', 1)->first();
                $backgroundcolor = $template->color;
                $imageName = $template->image;

                $allCategories = Category::all();

                $jobs = Job::join('categories', 'jobs.category_id', '=', 'categories.id')->where($whereArray)->where('jobs.job_status', 'published')->orderBy('jobs.title', $sort)->select('jobs.*', 'categories.name as category_name')->get();

                // Prepare the data to be returned as JSON
                $jsonData = [
                    'message' => 'Jobs found!',
                    'jobs_html' => view('jobs.filtered-jobs', compact('jobs', 'imageName', 'backgroundcolor', 'allCategories', 'hash'))->render(),
                ];

                return response()->json($jsonData, 200);
            } else {
                return response()->json(['message' => 'Jobs not found!'], 404);
            }
        } else {
            return response()->json(['message' => 'User not found!'], 404);
        }
    }


    // public function showJobs(Request $request)
    // {

    //     $hash = '7kNZc3s5w08vSyoIXQLv';

    //     if (User::where('hash', $hash)->exists()) {
    //         $user = User::where('hash', $hash)->first();
    //         $whereArray = [];
    //         $whereArray[] = ['jobs.user_id', '=', $user->id];

    //         if (Job::where('user_id', $user->id)->exists()) {

    //             if ($request->filled('category')) {
    //                 $whereArray[] = ['jobs.category_id', '=', trim($request->category)];
    //             }

    //             if ($request->filled('location')) {
    //                 $location = explode(",", $request->location);
    //                 // $whereArray[] = ['jobs.city', '=', trim($location[0])];
    //                 $whereArray[] = ['jobs.state', '=', trim($location[0])];
    //             }

    //             if ($request->filled('search_term')) {
    //                 $whereArray[] = ['jobs.title', 'like', '%' . $request->search_term . '%'];
    //             }

    //             if ($request->filled('sort')) {
    //                 $sort = $request->sort;
    //             } else {
    //                 $sort = 'ASC';
    //             }

    //             $template = Template::where('user_id', $user->id)->where('status', 1)->first();
    //             // $imageName = Template::where('user_id', $user->id)->value('image');
    //             $backgroundcolor = $template->color;
    //             $imageName = $template->image;


    //             $allCategories = Category::all();

    //             $jobs = Job::join('categories', 'jobs.category_id', '=', 'categories.id')->where($whereArray)->where('jobs.job_status', 'published')->orderBy('jobs.title', $sort)->select('jobs.*', 'categories.name as category_name')->get();

    //             return view('jobs.joblist',compact('jobs', 'imageName','backgroundcolor', 'allCategories'));
    //             // return response()->json([
    //             //     'message' => 'Jobs found!',
    //             //     'data' => jobs_html($jobs, $hash, $allCategories, $imageName),
    //             // ], 200);
    //         } else {
    //             return response()->json([
    //                 'message' => 'Jobs not found!'
    //             ], 404);
    //         }
    //     } else {
    //         return response()->json([
    //             'message' => 'User not found!'
    //         ], 404);
    //     }
    // }



    // public function index($hash, $jobId)
    // {
    //     if (User::where('hash', $hash)->exists()) {
    //         $user = User::where('hash', $hash)->first();
    //         $whereArray = [];
    //         $whereArray[] = ['user_id', '=', $user->id];
    //         $whereArray[] = ['id', '=', $jobId];

    //         if (Job::where($whereArray)->exists()) {
    //             $job = Job::where($whereArray)->first();
    //             $imageName = Template::where('user_id', $user->id)->value('image');
    //             return view('jobs.details', compact('job', 'imageName'));
    //         } else {
    //             return redirect()->route('jobs-listings');
    //         }
    //     } else {
    //         return redirect()->route('jobs-listings');
    //     }
    // }
}
