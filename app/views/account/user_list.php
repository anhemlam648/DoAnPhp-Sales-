<?php include_once 'app/views/share/header.php'; ?>

<div class="container mt-4">
    <h1>Danh sách người dùng</h1>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= $user->name ?></td>
                        <td><?= $user->email ?></td>
                        <td>
                            <form method="post" action="/chieu2/Account/changeUserRole">
                                <input type="hidden" name="userId" value="<?= $user->id ?>">
                                <select name="role" class="form-control" onchange="updateRoleValue(this)">
                                    <option value="admin" <?= ($user->role === 'admin') ? 'selected' : '' ?>>Admin</option>
                                    <option value="user" <?= ($user->role === 'user') ? 'selected' : '' ?>>User</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <?php if (Auth::isAdmin() && $user->role === 'user') : ?>
                                <form method="post" action="/chieu2/Account/changeUserRole">
                                    <input type="hidden" name="userId" value="<?= $user->id ?>">
                                    <input type="hidden" name="role" value="<?= $user->role ?>" id="hiddenRole<?= $user->id ?>">
                                    <button type="submit" class="btn btn-primary">Bạn có thể phân quyền</button>
                                </form>
                            <?php endif; ?>
                            <?php if (Auth::isAdmin() && $user->role === 'admin') : ?>
                                <form method="post" action="#">
                                    <button type="submit" class="btn btn-primary">Bạn Không có quyền</button>
                                </form>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($user->role === 'user') : ?>
                                Đang có hành động
                            <?php endif; ?>
                            <?php if ($user->role === 'admin') : ?>
                                Không có hành động
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once 'app/views/share/footer.php'; ?>

<script>
    function updateRoleValue(selectElement) {
        var selectedRole = selectElement.value;
        var hiddenRoleInput = document.getElementById("hiddenRole" + selectElement.parentNode.querySelector('[name="userId"]').value);
        hiddenRoleInput.value = selectedRole;
    }
</script>

