<h1 class="h4 mb-3">لوحة التحكم</h1>

<p>أهلاً <?= e($_SESSION['username']) ?>، هذه الصفحة للadmin فقط.</p>

<div class="list-group">
    <a class="list-group-item list-group-item-action" href="<?= url('/restaurants') ?>">عرض المطاعم</a>
</div>