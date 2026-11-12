<div class="row justify-content-center">
    <div class="col-md-5">
        <h1 class="h4 mb-3">إنشاء حساب</h1>

        <?php if ($error != ''): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" action="<?= url('/register') ?>">
            <div class="mb-3">
                <label class="form-label" for="username">الاسم</label>
                <input class="form-control" type="text" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">البريد الإلكتروني</label>
                <input class="form-control" type="email" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">كلمة المرور</label>
                <input class="form-control" type="password" id="password" name="password" required>
            </div>

            <button class="btn btn-primary w-100" type="submit">إنشاء الحساب</button>
        </form>
    </div>
</div>