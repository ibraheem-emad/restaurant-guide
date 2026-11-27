<h1 class="h4 mb-3">لوحة التحكم</h1>

<p>أهلاً <?= e($_SESSION['username']) ?>، هذه الصفحة للadmin فقط.</p>

<?php if (isset($_GET['saved'])): ?>
    <div class="alert alert-success">تم حفظ المطعم.</div>
<?php endif; ?>

<div class="list-group">
    <a class="list-group-item list-group-item-action" href="<?= url('/admin/restaurants') ?>">إدارة المطاعم</a>
    <a class="list-group-item list-group-item-action" href="<?= url('/admin/add-restaurant') ?>">إضافة مطعم</a>
    <a class="list-group-item list-group-item-action" href="<?= url('/admin/categories') ?>">التصنيفات</a>
    <a class="list-group-item list-group-item-action" href="<?= url('/admin/attributes') ?>">المميزات</a>
    <a class="list-group-item list-group-item-action" href="<?= url('/admin/users') ?>">المستخدمون</a>
    <a class="list-group-item list-group-item-action" href="<?= url('/restaurants') ?>">عرض المطاعم</a>
</div>