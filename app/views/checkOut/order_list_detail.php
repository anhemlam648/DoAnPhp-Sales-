<?php
include_once 'app/views/share/header.php';
?>
<div class="container">
    <h1 class="mb-4">Danh sách đơn hàng đã thanh toán</h1>
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Địa chỉ</th>
                    <th scope="col">Tổng giá</th>
                    <th scope="col">Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?= $order->id ?></td>
                        <td><?= $order->name ?></td>
                        <td><?= $order->email ?></td>
                        <td><?= $order->address ?></td>
                        <td><?= number_format($order->totalPrice, 0, ',', '.') ?> đ</td>
                        <td><?= $order->createdAt ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once 'app/views/share/footer.php'; ?>

<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true, // Cho phép phân trang
            "lengthChange": true, // Không hiển thị dropdown chọn số lượng hàng trên trang
            "searching": true, // Tắt chức năng tìm kiếm
            "ordering": true, // Cho phép sắp xếp cột
            "info": true, // Hiển thị thông tin số trang
            "autoWidth": false, // Tắt tự động tính chiều rộng cột
            "responsive": true, // Kích hoạt tính năng responsive
            "pageLength": 5,
            "stateSave": true
        });
    });
</script>