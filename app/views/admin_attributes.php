
<h1 class="h4 mb-3">المميزات</h1>

<?php if (isset($_GET['saved'])): ?>
    <div class="alert alert-success">تم الحفظ.</div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">تم الحذف.</div>
<?php endif; ?>

<?php if ($error != ''): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<table class="table">
    <?php foreach ($attributes as $attribute): ?>
        <tr>
            <td>
                <form class="d-flex" method="post" action="<?= url('/admin/edit-attribute/' . $attribute['attribute_id']) ?>">
                    <input class="form-control form-control-sm me-2" type="text" name="attribute_name" value="<?= e($attribute['attribute_name']) ?>" required>
                    <button class="btn btn-sm btn-outline-primary" type="submit">حفظ</button>
                </form>
            </td>
            <td>
                <form method="post" action="<?= url('/admin/delete-attribute/' . $attribute['attribute_id']) ?>" onsubmit="return confirm('سيتم حذف هذه الميزة من كل المطاعم. هل أنت متأكد؟')">
                    <button class="btn btn-sm btn-outline-danger" type="submit">حذف</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2 class="h5 mt-4 mb-3">إضافة ميزة</h2>

<form class="d-flex" method="post" action="<?= url('/admin/attributes') ?>">
    <input class="form-control me-2" type="text" name="attribute_name" required>
    <button class="btn btn-primary" type="submit">إضافة</button>
</form>