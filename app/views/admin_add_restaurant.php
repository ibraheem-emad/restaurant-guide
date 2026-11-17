<h1 class="h4 mb-3">إضافة مطعم</h1>

<?php if ($error != ''): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post" action="<?= url('/admin/add-restaurant') ?>">
    <div class="mb-3">
        <label class="form-label" for="place_name">اسم المطعم</label>
        <input class="form-control" type="text" id="place_name" name="place_name" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="phone_number">رقم الهاتف</label>
        <input class="form-control" type="text" id="phone_number" name="phone_number">
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label" for="latitude">خط العرض (latitude)</label>
            <input class="form-control" type="text" id="latitude" name="latitude" required>
        </div>
        <div class="col">
            <label class="form-label" for="longitude">خط الطول (longitude)</label>
            <input class="form-control" type="text" id="longitude" name="longitude" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="price_range">الأسعار</label>
        <select class="form-select" id="price_range" name="price_range">
            <option value="low">اقتصادي</option>
            <option value="medium">متوسط</option>
            <option value="high">مرتفع</option>
        </select>
    </div>

    <div class="mb-3">
        <div class="form-label">التصنيفات</div>
        <?php foreach ($categories as $category): ?>
            <label class="me-3">
                <input type="checkbox" name="categories[]" value="<?= $category['category_id'] ?>">
                <?= e($category['category_name']) ?>
            </label>
        <?php endforeach; ?>
    </div>

    <div class="mb-3">
        <div class="form-label">المميزات</div>
        <?php foreach ($attributes as $attribute): ?>
            <label class="me-3">
                <input type="checkbox" name="attributes[]" value="<?= $attribute['attribute_id'] ?>">
                <?= e($attribute['attribute_name']) ?>
            </label>
        <?php endforeach; ?>
    </div>

    <div class="mb-3">
        <label class="form-label" for="status">الحالة</label>
        <select class="form-select" id="status" name="status">
            <option value="draft">مسودة (مخفي عن الزوار)</option>
            <option value="published">منشور</option>
        </select>
    </div>

    <button class="btn btn-primary" type="submit">حفظ المطعم</button>
</form>