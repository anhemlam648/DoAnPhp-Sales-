<?php
include_once 'app/views/share/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <!-- Bootstrap CSS -->
    <link href="/chieu2/public/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">Access Denied</div>
                    <div class="card-body">
                        <p>You don't have permission to access this page.</p>
                        <p>Please contact the administrator for assistance.</p>
                        <a href="/chieu2/" class="btn btn-primary">Go to Home Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
include_once 'app/views/share/footer.php';
?>