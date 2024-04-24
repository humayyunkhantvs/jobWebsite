<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/images/sph-logo.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Job Posting</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div id="main_html1"></div>
    <div id="error" class="text-center bg-danger text-white"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            url = new URL(window.location.href);
            let baseURL = '';

            if (url.searchParams.has('hash') && url.searchParams.has('id')) {
                baseURL = 'http://127.0.0.1:8000/api/jobs?hash=' + url.searchParams.get('hash') + '&id=' + url.searchParams.get('id');
            } else {
                baseURL = 'http://127.0.0.1:8000/api/jobs?hash=7kNZc3s5w08vSyoIXQLv&id=';
            }

            $.ajax({
                url: baseURL,
                type: 'GET',
                success: function(response) {
                    $('#main_html1').html(response.jobs_html);
                },
                error: function(xhr, status, error) {
                    $('#error').html('Error: ' + error);
                }
            });
        });
    </script>
</body>

</html>