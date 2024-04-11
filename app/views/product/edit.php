<?php
include_once 'app/views/share/header.php';

if (!Auth::isAdmin()) {
    // Nếu không phải admin, chuyển hướng người dùng đến trang báo lỗi hoặc trang không có quyền truy cập
    include_once 'app/views/errors/access_denied.php'; 
    exit; // Dừng việc thực thi mã
}
?>

<?php if (isset($errors)) : ?>
    <div class="alert alert-danger" role="alert">
        <ul>
            <?php foreach ($errors as $err) : ?>
                <li><?= $err ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<div class="container">
    <h1>Sửa sản phẩm</h1>
    <div class="card-body p-3">
        <form class="user" action="/chieu2/product/save" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product->id ?>">

            <div class="form-group row">
                <div class="col-md-6">
                    <input value="<?= $product->name ?>" type="text" class="form-control" id="name" name="name" placeholder="Product Name">
                </div>
                <div class="col-md-6">
                    <input value="<?= $product->price ?>" type="number" class="form-control" id="price" name="price" placeholder="Product Price">
                </div>
            </div>

            <div class="form-group">
                <input value="<?= $product->description ?>" type="text" class="form-control" id="description" name="description" placeholder="Product Description">
            </div>

            <div class="form-group">
                <?php if (empty($product->image) || !file_exists($product->image)) : ?>
                    <p>No Image!</p>
                <?php else : ?>
                    <img id="productImage" src="/chieu2/<?= $product->image ?>" alt="Product Image" class="img-thumbnail" style="max-width: 200px;">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <input type="file" class="form-control form-control-user" id="image" name="image" onchange="previewImage(event)">
            </div>

            <script>
                function previewImage(event) {
                    var image = document.getElementById('productImage');
                    var file = event.target.files[0];
                    var reader = new FileReader();

                    reader.onload = function() {
                        image.src = reader.result;
                    }

                    reader.readAsDataURL(file);
                }
            </script>

            <div class="form-group text-center">
                <button class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-save"></i>
                    </span>
                    <span class="text">Save Product</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php
include_once 'app/views/share/footer.php';
?>
