<h1 class="h4 mb-3">تعديل مطعم</h1>

<?php if ($error != ''): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post" action="<?= url('/admin/edit-restaurant/' . $restaurant['place_id']) ?>">
    <div class="mb-3">
        <label class="form-label" for="place_name">اسم المطعم</label>
        <input class="form-control" type="text" id="place_name" name="place_name" value="<?= e($restaurant['place_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="phone_number">رقم الهاتف</label>
        <input class="form-control" type="text" id="phone_number" name="phone_number" value="<?= e($restaurant['phone_number']) ?>">
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label" for="latitude">خط العرض (latitude)</label>
            <input class="form-control" type="text" id="latitude" name="latitude" value="<?= e($restaurant['latitude']) ?>" required>
        </div>
        <div class="col">
            <label class="form-label" for="longitude">خط الطول (longitude)</label>
            <input class="form-control" type="text" id="longitude" name="longitude" value="<?= e($restaurant['longitude']) ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="price_range">الأسعار</label>
        <select class="form-select" id="price_range" name="price_range">
            <option value="low" <?= $restaurant['price_range'] == 'low' ? 'selected' : '' ?>>اقتصادي</option>
            <option value="medium" <?= $restaurant['price_range'] == 'medium' ? 'selected' : '' ?>>متوسط</option>
            <option value="high" <?= $restaurant['price_range'] == 'high' ? 'selected' : '' ?>>مرتفع</option>
        </select>
    </div>

    <div class="mb-3">
        <div class="form-label">التصنيفات</div>
        <?php foreach ($categories as $category): ?>
            <label class="me-3">
                <input type="checkbox" name="categories[]" value="<?= $category['category_id'] ?>" <?= in_array($category['category_id'], $checked_categories) ? 'checked' : '' ?>>
                <?= e($category['category_name']) ?>
            </label>
        <?php endforeach; ?>
    </div>

    <div class="mb-3">
        <div class="form-label">المميزات</div>
        <?php foreach ($attributes as $attribute): ?>
            <label class="me-3">
                <input type="checkbox" name="attributes[]" value="<?= $attribute['attribute_id'] ?>" <?= in_array($attribute['attribute_id'], $checked_attributes) ? 'checked' : '' ?>>
                <?= e($attribute['attribute_name']) ?>
            </label>
        <?php endforeach; ?>
    </div>

    <div class="mb-3">
        <div class="form-label">ساعات العمل (اترك اليوم فارغاً إذا كان المطعم مغلقاً فيه)</div>
        <table class="table table-sm w-auto">
            <?php for ($day = 0; $day <= 6; $day++): ?>
                <tr>
                    <th><?= day_name($day) ?></th>
                    <td>
                        <input class="form-control form-control-sm" type="time" name="open_time[<?= $day ?>]" value="<?= isset($hours[$day]) ? substr($hours[$day]['open_time'], 0, 5) : '' ?>">
                    </td>
                    <td>إلى</td>
                    <td>
                        <input class="form-control form-control-sm" type="time" name="close_time[<?= $day ?>]" value="<?= isset($hours[$day]) ? substr($hours[$day]['close_time'], 0, 5) : '' ?>">
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>
    
    <div class="mb-3">
        <label class="form-label" for="status">الحالة</label>
        <select class="form-select" id="status" name="status">
            <option value="draft" <?= $restaurant['status'] == 'draft' ? 'selected' : '' ?>>مسودة (مخفي عن الزوار)</option>
            <option value="published" <?= $restaurant['status'] == 'published' ? 'selected' : '' ?>>منشور</option>
            <option value="hidden" <?= $restaurant['status'] == 'hidden' ? 'selected' : '' ?>>مخفي</option>
        </select>
    </div>

    <button class="btn btn-primary" type="submit">حفظ التعديلات</button>
    <a class="btn btn-outline-secondary" href="<?= url('/admin/restaurants') ?>">إلغاء</a>
</form>