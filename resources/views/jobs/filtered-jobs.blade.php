
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
