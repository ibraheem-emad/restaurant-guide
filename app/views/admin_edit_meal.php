<h1 class="h4 mb-3">تعديل وجبة</h1>

<?php if ($error != ''): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post" action="<?= url('/admin/edit-meal/' . $meal['meal_id']) ?>">
    <div class="mb-3">
        <label class="form-label" for="meal_name">اسم الوجبة</label>
        <input class="form-control" type="text" id="meal_name" name="meal_name" value="<?= e($meal['meal_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="price">السعر (₪)</label>
        <input class="form-control" type="text" id="price" name="price" value="<?= e($meal['price']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="category_id">التصنيف</label>
        <select class="form-select" id="category_id" name="category_id">
            <option value="">بدون تصنيف</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['category_id'] ?>" <?= $meal['category_id'] == $category['category_id'] ? 'selected' : '' ?>><?= e($category['category_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-primary" type="submit">حفظ التعديلات</button>
    <a class="btn btn-secondary" href="<?= url('/admin/menu/' . $meal['place_id']) ?>">إلغاء</a>
</form>