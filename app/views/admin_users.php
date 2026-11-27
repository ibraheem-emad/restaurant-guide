<h1 class="h4 mb-3">المستخدمون</h1>

<?php if (isset($_GET['saved'])): ?>
    <div class="alert alert-success">تم الحفظ.</div>
<?php endif; ?>

<?php if ($error != ''): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table align-middle">
        <tr>
            <th>الاسم</th>
            <th>البريد</th>
            <th>الدور</th>
            <th>المطعم</th>
            <th>الحالة</th>
            <th></th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= e($user['username']) ?></td>
                <td><?= e($user['email']) ?></td>
                <td>
                    <?php if ($user['user_id'] == $_SESSION['user_id']): ?>
                        مدير (أنت)
                    <?php else: ?>
                        <form class="d-flex" method="post" action="<?= url('/admin/user-role/' . $user['user_id']) ?>">
                            <select class="form-select form-select-sm w-auto me-2" name="role">
                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>مستخدم</option>
                                <option value="owner" <?= $user['role'] == 'owner' ? 'selected' : '' ?>>صاحب مطعم</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>مدير</option>
                            </select>
                            <button class="btn btn-sm btn-outline-primary" type="submit">تغيير</button>
                        </form>
                    <?php endif; ?>
                </td>
                <td><?= e($user['restaurants']) ?></td>
                <td>
                    <?php if ($user['is_blocked']): ?>
                        <span class="badge bg-danger">محظور</span>
                    <?php else: ?>
                        نشط
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                        <form method="post" action="<?= url('/admin/block-user/' . $user['user_id']) ?>">
                            <?php if ($user['is_blocked']): ?>
                                <button class="btn btn-sm btn-outline-success" type="submit">إلغاء الحظر</button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-outline-danger" type="submit">حظر</button>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<h2 class="h5 mt-4 mb-3">إضافة حساب صاحب مطعم</h2>

<form method="post" action="<?= url('/admin/users') ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="username">الاسم</label>
            <input class="form-control" type="text" id="username" name="username" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="email">البريد الإلكتروني</label>
            <input class="form-control" type="email" id="email" name="email" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="password">كلمة المرور (8 أحرف على الأقل)</label>
            <input class="form-control" type="password" id="password" name="password" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="place_id">المطعم</label>
            <select class="form-select" id="place_id" name="place_id" required>
                <option value="">اختر مطعماً</option>
                <?php foreach ($restaurants as $r): ?>
                    <option value="<?= $r['place_id'] ?>"><?= e($r['place_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <button class="btn btn-primary" type="submit">إنشاء الحساب</button>
</form>