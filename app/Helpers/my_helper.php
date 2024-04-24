<?php

use Illuminate\Support\Str;


function hash_token()
{
    $randomString = Str::random(20);
    $user = \App\Models\User::where('hash', $randomString)->first();
    if (!empty($user)) {
        $randomString = Str::random(20);
    }
    return $randomString;
}




function jobs_html($jobs, $hash, $allCategories, $imageName)
{
    $jobs_html = "";
    $unique_job_categories = [];
    $unique_job_locations = [];

    foreach ($jobs as $job) {
        if (!empty($job['category'])) {
            if (!in_array($job['category'], $unique_job_categories)) {
                $unique_job_categories[] = $job['category'];
            }
        }

        $job_location = $job["city"] . ", " . $job["state"];
        if (!empty($job_location)) {
            if (!in_array($job_location, $unique_job_locations)) {
                $unique_job_locations[] = $job_location;
            }
        }

        // Jobs html start
        $jobs_html .=
            '<div job_id="' .
            $job["id"] .
            '" class="job-posting container h-50 p-5 mb-2 d-block">';
        $jobs_html .= '  <h2 class="job_title">' . $job["title"] . ' - ' . $job['category_name'] . "</h2>";
        $jobs_html .= '  <p class="job_location">';
        $jobs_html .= "    <strong>Location:</strong>";
        $jobs_html .=
            '    <span class="job_address_line_1">' .
            $job["address"] .
            "</span>,";
        $jobs_html .= '    <span class="job_city">' . $job["city"] . "</span>,";
        $jobs_html .=
            '    <span class="job_state">' .
            $job["state"] .
            '</span> <span class="job_zip">' .
            $job["zip"] .
            "</span>,";
        $jobs_html .= '    <span class="job_country">' . $job["country"] . "</span>";
        $jobs_html .= "  </p>";
        $jobs_html .= "  <p>";
        $jobs_html .= "    <strong>Job Category:</strong>";
        $jobs_html .=
            '    <span class="job_category">' .
            (!empty($job["category_name"]) ? $job["category_name"] : "N/A") .
            "</span>";
        $jobs_html .= "  </p>";
        $jobs_html .=
            // '  <a  target="_blank" href="#" data-hash="' . $hash . '" data-id="' . $job['id'] . '" class="btn btn-primary job-button-test job_details">Job Details</a>';

            // '  <a target="_blank" href="' . route('job-details', ['hash' => $hash, 'job' => $job['id']]) . '"  class="btn btn-primary job-button-test job_details">Job Details</a>';
            '  <a target="_blank" href="' . route('details-job', ['hash' => $hash, 'job' => base64_encode($job['id'])]) . '"  class="btn btn-primary job-button-test">Job Details</a>';
        $jobs_html .= "</div>";

        // Display the image name
        // Jobs html end
    }

    // Category filter html start
    $category_html =
        '<select class="form-select" name="category" id="category-filter">';
    $category_html .= '<option value="" selected>All Job Category</option>';
    foreach ($allCategories as $category) {
        $category_html .=
            '<option value="' . $category['id'] . '">' . $category['name'] . "</option>";
    }
    $category_html .= "</select>";
    // Category filter html end

    // Location filter html start
    $location_html =
        '<select class="form-select" name="location" id="location-filter">';
    $location_html .=
        '<option value="" selected>All Job Location</option>';
    foreach ($unique_job_locations as $job_location) {
        $location_html .=
            '<option value="' . $job_location . '">' . $job_location . "</option>";
    }
    $location_html .= "</select>";
    // Location filter html end

    // All filters html start
    $filters_html =
        '<div class="container">
        <div class="row">
            <div class="col-md-3 my-1 category-filter">
                ' . $category_html . '
            </div>
            <div class="col-md-3 my-1 location-filter">
                ' . $location_html . '
            </div>
            
            <div class="col-md-3 my-1 search-filter">
                <div class="input-group">
                    <input class="form-control border-end-0 border rounded-pill" type="text" name="search" placeholder="Search Jobs By Title..." id="search-filter">
                </div>
            </div>

            <div class="col-md-3 my-1 sorting-filter">
                <select class="form-select" name="sorting" id="sorting-filter">
                    <option value="ASC" selected>Alphabetical</option>
                    <option value="DESC">Newest</option>
                </select>
            </div>
        </div>
    </div>';
    // All filters html end

    $jobs_image_html = $imageName;



    return ['jobs' => $jobs_html, 'filters' => $filters_html, 'img' => $jobs_image_html];
}
