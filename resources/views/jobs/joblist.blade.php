<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./sph-logo.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{--
      <link rel="stylesheet" href="{{ asset('user/assets/css/style.css')}}">
    --}}
    <link rel="stylesheet" href="{{asset('assets/css/style.css') }}">
    
    <style></style>
</head>

<body>


    <section id="hero-section" class="my-3">
        <nav class="navbar navbar-expand-md navbar-dark" style="background-color: {{ !empty($backgroundcolor) ? $backgroundcolor : '#849c3d' }};">
            <div class="container-fluid">
                <a class="navbar-brand" href="javascript:void(0)">
                    @if(!empty($imageName))
                    <!-- If $imageName is not empty or null, display the uploaded image -->
                    <img src="{{ asset('upload/' . $imageName) }}" id="bg_img">
                    @else
                    <!-- If $imageName is empty or null, display the default image -->
                    <img src="{{ asset('assets/images/bg.png') }}" id="bg_img">
                    @endif
                </a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0)">Find jobs by category, location or title.</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </section>


    <section id="filter-section">
        <div class="container">
            <div class="row">
                <div class="row">
                    <div class="col-md-3 my-1 search-filter">
                        <div class="input-group">
                            <input class="form-control border-end-0 border rounded-pill" type="text" name="search" placeholder="Search Jobs By Title..." id="search-filter">
                        </div>
                    </div>

                    <div class="col-md-3 my-1 category-filter">
                        <select class="form-select" name="category" id="category-filter">
                            <option value="" selected>All Job Category</option>
                            @foreach($allCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 my-1 location-filter">
                        <select class="form-select" name="location" id="location-filter">
                            <option value="" selected>All Job Location</option>
                            @php
                            $unique_states = array_unique($jobs->pluck('state')->toArray());
                            @endphp
                            @foreach ($unique_states as $state)
                            <option value="{{ $state }}">{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 my-1 sorting-filter">
                        <select class="form-select" name="sorting" id="sorting-filter">
                            <option value="ASC" selected>Alphabetical</option>
                            <option value="DESC">Newest</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="my-3" id="job-posting" data-hash="{{$hash}}">
            @foreach ($jobs as $job)
            @php
            $jobId = Crypt::encryptString($job->id);
            @endphp
                <div job_id="{{$job->id}}" class="job-posting container h-50 p-5 mb-2 d-block">
                    <h2 class="job_title">{{$job->title}} - {{$job->company}}</h2>
                    <p class="job_location"> <strong>Location:</strong> <span class="job_address_line_1">{{$job->address}}</span>, <span class="job_city">{{$job->city}}</span>, <span class="job_state">{{$job->state}}</span> <span class="job_zip">{{$job->zip}}</span>, <span class="job_country">{{$job->country}}</span> </p>
                    <p> <strong>Job Category:</strong> <span class="job_category">{{$job->category_name}}</span> </p>
                    <button class="btn job-button-test text-white show-job-details" style="background-color: {{ !empty($backgroundcolor) ? $backgroundcolor : '#849c3d' }};" data-hash="{{$hash}}" data-id="{{$jobId}}">Job Details</button>
                </div>
            @endforeach
        </section>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.tiny.cloud/1/eh83qrdiozahqd9f0coxhcr6icqz5xst3qtlykm0lm85fdu1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="{{ asset('user/assets/js/add_applicant.js?v=11')}}"></script>

    <script>
        $(document).ready(function() {
            async function filterJobs(query = "") {
                try {
                    const hash = document.querySelector("#job-posting").getAttribute('data-hash');
                    const baseUrl = "http://127.0.0.1:8000/api/jobs-filter/"+ hash + query;
                    const response = await fetch(baseUrl);
                    const html = await response.json();

                    if (!response.ok) {
                        if(response.status == 404){
                            throw new Error("No jobs found against your search!");;
                        }else {
                            throw new Error("Something went wrong");
                        }
                    }
                    document.getElementById("error").innerText = '';
                    const jobPostingContainer = document.getElementById("job-posting").innerHTML = html.jobs_html;
                    applyFilters();
                } catch (error) {
                    const jobPostingContainer = document.getElementById("job-posting").innerHTML = '';
                    document.getElementById("error").innerText = error;
                }
            }

            function redirectToJobDetails(jobId, hash) {
                let baseUrl = window.location.href;
                if(baseUrl.indexOf("?") > 0) {
                    baseUrl = baseUrl.substring(0, baseUrl.indexOf("?"));
                }

                baseUrl += "?hash="+hash+"&id="+jobId;
                window.open(baseUrl, '_blank');
            }

            // Function to apply filters
            function applyFilters() {
                let categoryFilterObj = document.getElementById("category-filter");
                let locationFilterObj = document.getElementById("location-filter");
                let searchFilterObj = document.getElementById("search-filter");
                let sortingFilterObj = document.getElementById("sorting-filter");
                let jobDetailsBtns = document.querySelectorAll(".show-job-details");

                for (let i = 0; i < jobDetailsBtns.length; i++) {
                    jobDetailsBtns[i].addEventListener("click", function() {
                        redirectToJobDetails(this.getAttribute('data-id'), this.getAttribute('data-hash'));
                    });
                }

                // Event listener for category filter
                categoryFilterObj.addEventListener("change", function() {
                    filterJobs(buildQuery());
                });

                // Event listener for location filter
                locationFilterObj.addEventListener("change", function() {
                    filterJobs(buildQuery());
                });

                // Event listener for search filter
                searchFilterObj.addEventListener("input", function() {
                    filterJobs(buildQuery());
                });

                // Event listener for sorting filter
                sortingFilterObj.addEventListener("change", function() {
                    filterJobs(buildQuery());
                });
            }

            // Function to build query string based on filter selections
            function buildQuery() {
                let selectedCategory = document.getElementById("category-filter").value;
                let selectedLocation = document.getElementById("location-filter").value;
                let searchTerm = document.getElementById("search-filter").value;
                let selectedSort = document.getElementById("sorting-filter").value;

                // console.log("Selected location:", selectedLocation);
                return `?category=${selectedCategory}&location=${selectedLocation}&search_term=${searchTerm}&sort=${selectedSort}`;
            }

            // Invoke applyFilters function to set up event listeners
            applyFilters();
        });
    </script>
</body>

</html>