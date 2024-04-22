<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Content</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Make the body fill the entire viewport height */
            margin: 0; /* Remove default margin to avoid extra spacing */
        }

        .content {
            text-align: center;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="content">
       
        <h1>Unauthorized</h1>
        <p>You are not allowed to access this page.</p>
        <p><a href="#" onclick="goBack()">Go back to the previous page</a></p>

        <script>
            function goBack() {
                window.history.back();
            }
        </script>
    </div>
</body>
</html>
