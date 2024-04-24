
        @foreach ($jobs as $job)
        <div job_id="{{$job->id}}" class="job-posting container h-50 p-5 mb-2 d-block">
            <h2 class="job_title">{{$job->title}} - {{$job->company}}</h2>
            <p class="job_location"> <strong>Location:</strong> <span class="job_address_line_1">{{$job->address}}</span>, <span class="job_city">{{$job->city}}</span>, <span class="job_state">{{$job->state}}</span> <span class="job_zip">{{$job->zip}}</span>, <span class="job_country">{{$job->country}}</span> </p>
            <p> <strong>Job Category:</strong> <span class="job_category">{{$job->category_name}}</span> </p>
            <a target="_blank" href="{{ route('details-job', ['job' => base64_encode($job->id)]) }}" class="btn job-button-test text-white" style="background-color: {{ !empty($backgroundcolor) ? $backgroundcolor : '#849c3d' }};">Job Details</a>
        </div>
        @endforeach
