<?php include_once 'app/views/share/header.php'; ?>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-center">Login</h5>
        </div>
        <div class="card-body">
            <?php if (isset($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= $err ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="/chieu2/account/checklogin" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <div class="text-center mt-3">
                <a href="/chieu2/account/register">Create an Account</a>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/share/footer.php'; ?>
