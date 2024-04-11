<?php
include_once 'app/views/share/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <!-- Link CSS của Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            margin: 20px auto;
            width: 50%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-message">
            <p>Product added to cart successfully!</p>
            <a href="/chieu2/Shopping/listShoppingCart" class="btn btn-primary">View Cart</a>
        </div>
    </div>
</body>
</html>
<?php
include_once 'app/views/share/footer.php';
?>