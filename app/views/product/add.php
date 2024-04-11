<?php include_once 'app/views/share/header.php';
if (!Auth::isAdmin()) {
    // Nếu không phải admin, chuyển hướng người dùng đến trang báo lỗi hoặc trang không có quyền truy cập
    include_once 'app/views/errors/access_denied.php'; 
    exit; // Dừng việc thực thi mã
}

?>

<?php if (isset($errors)) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $err) : ?>
                <li><?= $err ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<h1>Thêm sản phẩm</h1>
<div class="card-body p-5">
    <form class="user" action="/chieu2/product/save" method="post" enctype="multipart/form-data">
        <div class="form-group row">
            <div class="col-md-6 mb-3">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" spellcheck="true">
            </div>
            <div class="col-md-6">
                <label for="price">Product Price:</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Product Price">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Product Description:</label>
            <textarea class="form-control" id="description" name="description" placeholder="Product Description"></textarea>
        </div>

        <div class="form-group">
            <label for="image">Product Image:</label>
            <input type="file" class="form-control-file" id="image" name="image">
        </div>

        <div class="form-group text-center">
            <button class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-save"></i>
                </span>
                <span type="submit" class="btn btn-primary" class="text">Save Product</span>
            </button>
        </div>
    </form>
</div>

<?php include_once 'app/views/share/footer.php'; ?>
