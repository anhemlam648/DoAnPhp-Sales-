<!-- app/views/product/list.php -->
<?php
include_once 'app/views/share/header.php';
?>
<div class="container">
    <h1>Danh sách sản phẩm</h1>
    <div class="row">
        <?php if (Auth::isAdmin()) : ?>
            <!-- Chỉ hiển thị nút Thêm sản phẩm nếu người dùng là admin -->
            <a href="/chieu2/product/add" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-flag"></i>
                </span>
                <span class="text">Add Product</span>
            </a>
        <?php endif; ?>

        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Price</th>
                            <?php if (Auth::isAdmin()) : ?>
                                <!-- Chỉ hiển thị cột Action nếu người dùng là admin -->
                                <th>Action (Edit/Delete)</th>
                            <?php endif; ?>
                            <th>Add to Cart</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['description'] ?></td>
                                <td>
                                    <?php
                                    if (empty($row['image']) || !file_exists($row['image'])) {
                                        echo "No Image!";
                                    } else {
                                        echo "<img src='/chieu2/" . $row['image'] . "' alt='' style='max-width: 100px; max-height: 100px;' />";
                                    }
                                    ?>
                                </td>
                                <td><?= number_format($row['price']) ?> đ</td>
                                <?php if (Auth::isAdmin()) : ?>
                                    <!-- Chỉ hiển thị nút Edit và Delete nếu người dùng là admin -->
                                    <td>
                                        <a href="/chieu2/product/edit/<?= $row['id'] ?>" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/chieu2/product/delete/<?= $row['id'] ?>" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                <?php endif; ?>
                                <td>
                                    <!-- Thêm nút Add to Cart với thuộc tính data-product-id -->
                                    <button class="btn btn-success btn-sm" onclick="addToCart(<?= $row['id'] ?>)">Add to Cart</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
include_once 'app/views/share/footer.php';
?>

<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 5,
            "stateSave": true
        });
    });

    function addToCart(productId) {
        // Gửi yêu cầu AJAX để thêm sản phẩm vào giỏ hàng
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/chieu2/Shopping/addAction/' + productId, true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                // Nếu thêm sản phẩm thành công, gửi yêu cầu AJAX để tải trang success.php
                var successRequest = new XMLHttpRequest();
                successRequest.open('GET', '/chieu2/Shopping/addSuccess', true);
                successRequest.onload = function() {
                    if (successRequest.status == 200) {
                        // Nếu thành công, hiển thị nội dung của trang success.php
                        document.body.innerHTML = successRequest.responseText;
                    } else {
                        console.log('Error: ' + successRequest.status);
                    }
                };
                successRequest.send();
            } else {
                // Nếu có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng
                alert('Failed to add product to cart.');
            }
        };
        xhr.send(); // Gửi yêu cầu
    }
</script>

