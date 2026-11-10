<h1>
    <?= e($restaurant['place_name']) ?>
    <?php if ($restaurant['is_open']): ?>
        <span class="badge bg-success fs-6">مفتوح الآن</span>
    <?php else: ?>
        <span class="badge bg-secondary fs-6">مغلق الآن</span>
    <?php endif; ?>
</h1>

<p class="text-muted">
    <?= e(implode('، ', $restaurant['categories'])) ?>
    · <?= price_label($restaurant['price_range']) ?>
    · ★ <?= e($restaurant['rating']) ?>
</p>

<p>
    <?php if ($restaurant['phone_number']): ?>
        <a href="tel:<?= e($restaurant['phone_number']) ?>"><?= e($restaurant['phone_number']) ?></a> ·
    <?php endif; ?>
    <a href="https://www.google.com/maps?q=<?= e($restaurant['latitude']) ?>,<?= e($restaurant['longitude']) ?>" target="_blank">الخريطة</a>
</p>

<h2 class="h5 mt-4">ساعات العمل</h2>
<table class="table table-sm w-auto">
    <?php for ($day = 0; $day <= 6; $day++): ?>
        <tr>
            <th><?= day_name($day) ?></th>
            <td dir="ltr">
                <?php if (isset($restaurant['hours'][$day])): ?>
                    <?= substr($restaurant['hours'][$day]['open_time'], 0, 5) ?>
                    -
                    <?= substr($restaurant['hours'][$day]['close_time'], 0, 5) ?>
                <?php else: ?>
                    مغلق
                <?php endif; ?>
            </td>
        </tr>
    <?php endfor; ?>
</table>

<?php if ($restaurant['attributes']): ?>
    <h2 class="h5 mt-4">المميزات</h2>
    <?php foreach ($restaurant['attributes'] as $attribute): ?>
        <span class="badge bg-light text-dark border"><?= e($attribute) ?></span>
    <?php endforeach; ?>
<?php endif; ?>

<h2 class="h5 mt-4">القائمة</h2>
<?php if (!$restaurant['menu']): ?>
    <p class="text-muted">لا توجد قائمة بعد.</p>
<?php endif; ?>

<?php foreach ($restaurant['menu'] as $section => $meals): ?>
    <h3 class="h6 mt-3"><?= e($section) ?></h3>
    <ul class="list-group mb-3">
        <?php foreach ($meals as $meal): ?>
            <li class="list-group-item d-flex justify-content-between">
                <span><?= e($meal['meal_name']) ?></span>
                <span><?= e($meal['price']) ?> ₪</span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
