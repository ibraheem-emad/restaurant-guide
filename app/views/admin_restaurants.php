<h1 class="h4 mb-3">إدارة المطاعم</h1>

<?php if (isset($_GET['saved'])): ?>
    <div class="alert alert-success">تم حفظ التعديلات.</div>
<?php endif; ?>

<div class="mb-3">
    <a class="btn btn-sm <?= $status == '' ? 'btn-primary' : 'btn-outline-primary' ?>" href="<?= url('/admin/restaurants') ?>">الكل</a>
    <a class="btn btn-sm <?= $status == 'published' ? 'btn-primary' : 'btn-outline-primary' ?>" href="<?= url('/admin/restaurants?status=published') ?>">منشور</a>
    <a class="btn btn-sm <?= $status == 'draft' ? 'btn-primary' : 'btn-outline-primary' ?>" href="<?= url('/admin/restaurants?status=draft') ?>">مسودة</a>
    <a class="btn btn-sm <?= $status == 'hidden' ? 'btn-primary' : 'btn-outline-primary' ?>" href="<?= url('/admin/restaurants?status=hidden') ?>">مخفي</a>
</div>

<?php if (!$restaurants): ?>
    <p class="text-muted">لا توجد مطاعم.</p>
<?php else: ?>
    <table class="table">
        <tr>
            <th>الاسم</th>
            <th>الأسعار</th>
            <th>الحالة</th>
            <th></th>
        </tr>
        <?php foreach ($restaurants as $r): ?>
            <tr>
                <td><?= e($r['place_name']) ?></td>
                <td><?= price_label($r['price_range']) ?></td>
                <td><?= status_label($r['status']) ?></td>
                <td>
                    <a href="<?= url('/admin/edit-restaurant/' . $r['place_id']) ?>">تعديل</a>
                    ·
                    <a href="<?= url('/restaurant/' . $r['place_id']) ?>">عرض</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>