<?php 
include_once 'app/views/share/header.php'; 
?>

<div class="container">
    <h1 class="mt-4">Thông tin cá nhân</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name"><strong>Tên:</strong></label>
                <input type="text" class="form-control" id="name" value="<?= $_SESSION['name'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="email"><strong>Email:</strong></label>
                <input type="email" class="form-control" id="email" value="<?= $_SESSION['username'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="role"><strong>Quyền:</strong></label>
                <input type="text" class="form-control" id="role" value="<?= $_SESSION['role'] ?>" readonly>
            </div>
            <!-- Thêm các trường thông tin cá nhân khác nếu cần -->
        </div>
        <form method="post" action="/chieu2/Account/uploadImage" enctype="multipart/form-data">
            <div class="form-group">
                <label for="avatar"><strong>Ảnh đại diện:</strong></label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="avatar" name="avatar" onchange="previewImage(this)">
                        <label class="custom-file-label" for="avatar">Chọn file</label>
                    </div>
                </div>
                <div id="imagePreview" class="mt-2" style="max-width: 200px;">
                    <?php if (!empty($_SESSION['avatar'])) : ?>
                        <img src="<?= '/chieu2/' . $_SESSION['avatar'] ?>" class="img-fluid" alt="Avatar">
                    <?php else : ?>
                        <p class="text-muted">Chưa có ảnh</p>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        var preview = document.getElementById('imagePreview');
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-fluid';
                preview.appendChild(img);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            var p = document.createElement('p');
            p.className = 'text-muted';
            p.textContent = 'Chưa có ảnh';
            preview.appendChild(p);
        }
    }
</script>

<?php include_once 'app/views/share/footer.php'; ?>
