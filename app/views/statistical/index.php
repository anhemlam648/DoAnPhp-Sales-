<?php
include_once 'app/views/share/header.php';
if (!Auth::isAdmin()) {
    // Nếu không phải admin, chuyển hướng người dùng đến trang báo lỗi hoặc trang không có quyền truy cập
    include_once 'app/views/errors/access_denied.php'; 
    exit; // Dừng việc thực thi mã
}
?>
<?php
// Thông tin kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root"; // Thay thế bằng tên người dùng của bạn
$password = ""; // Thay thế bằng mật khẩu của bạn
$database = "sang3"; // Thay thế bằng tên cơ sở dữ liệu của bạn

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

// Truy vấn cơ sở dữ liệu để lấy thông tin về số lượng đơn hàng và tổng giá tiền của mỗi tháng
$sql = "SELECT COUNT(DISTINCT `order`.Id) as total_orders, DATE_FORMAT(`order`.CreatedAt, '%Y-%m') as month_year, SUM(`order`.TotalPrice) as total_price, SUM(orderdetail.Quantity) as total_products 
        FROM `order` 
        LEFT JOIN orderdetail ON `order`.Id = orderdetail.OrderId
        WHERE `order`.TotalPrice > 0 
        GROUP BY DATE_FORMAT(`order`.CreatedAt, '%Y-%m')";
$result = $conn->query($sql);

// Mảng để lưu trữ dữ liệu cho biểu đồ
$labels = [];
$orderCount = [];
$totalPrice = [];
$totalProducts = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['month_year'];
        $orderCount[] = $row['total_orders'];
        $totalPrice[] = $row['total_price'];
        $totalProducts[] = $row['total_products'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thống kê đơn hàng</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="orderChart" width="800" height="400"></canvas>

    <script>
        var ctx = document.getElementById('orderChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Số lượng đơn hàng',
                    data: <?php echo json_encode($orderCount); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Tổng giá tiền',
                    data: <?php echo json_encode($totalPrice); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Số lượng sản phẩm',
                    data: <?php echo json_encode($totalProducts); ?>,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true ,
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>


<?php
include_once 'app/views/share/footer.php';
?>