<h1 class="h4 mb-3">منيو <?= e($restaurant['place_name']) ?></h1>

<?php if (isset($_GET['saved'])): ?>
    <div class="alert alert-success">تمت حفظ الوجبة.</div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">تم حذف الوجبة.</div>
<?php endif; ?>

<?php if ($error != ''): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if (!$meals): ?>
    <p class="text-muted">لا توجد وجبات بعد.</p>
<?php else: ?>
    <table class="table">
        <tr>
            <th>الوجبة</th>
            <th>التصنيف</th>
            <th>السعر</th>
            <th></th>
        </tr>
        <?php foreach ($meals as $meal): ?>
            <tr>
                <td><?= e($meal['meal_name']) ?></td>
                <td><?= e($meal['category_name']) ?></td>
                <td><?= e($meal['price']) ?> ₪</td>
                <td>
                    <a class="btn btn-sm btn-outline-primary" href="<?= url('/admin/edit-meal/' . $meal['meal_id']) ?>">تعديل</a>
                    <form class="d-inline" method="post" action="<?= url('/admin/delete-meal/' . $meal['meal_id']) ?>" onsubmit="return confirm('هل تريد حذف هذه الوجبة؟')">
                        <button class="btn btn-sm btn-outline-danger" type="submit">حذف</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<h2 class="h5 mt-4 mb-3">إضافة وجبة</h2>

<form method="post" action="<?= url('/admin/menu/' . $restaurant['place_id']) ?>">
    <div class="mb-3">
        <label class="form-label" for="meal_name">اسم الوجبة</label>
        <input class="form-control" type="text" id="meal_name" name="meal_name" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="price">السعر (₪)</label>
        <input class="form-control" type="text" id="price" name="price" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="category_id">التصنيف</label>
        <select class="form-select" id="category_id" name="category_id">
            <option value="">بدون تصنيف</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['category_id'] ?>"><?= e($category['category_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-primary" type="submit">إضافة الوجبة</button>
    <a class="btn btn-secondary" href="<?= url('/admin/restaurants') ?>">رجوع</a>
</form>