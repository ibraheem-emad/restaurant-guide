<h1 class="h4 mb-3">صور <?= e($restaurant['place_name']) ?></h1>

<?php if (isset($_GET['saved'])): ?>
    <div class="alert alert-success">تم رفع الصورة.</div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">تم حذف الصورة.</div>
<?php endif; ?>

<?php if ($error != ''): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if (!$photos): ?>
    <p class="text-muted">لا توجد صور بعد.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($photos as $photo): ?>
            <div class="col-6 col-md-3 mb-3">
                <div class="card">
                    <img class="card-img-top" src="<?= url('/uploads/' . $photo['file_name']) ?>" alt="" style="height: 150px; object-fit: cover;">
                    <div class="card-body p-2">
                        <?php if ($photo['is_cover']): ?>
                            <div class="mb-2"><span class="badge bg-success">الغلاف</span></div>
                        <?php else: ?>
                            <form class="mb-2" method="post" action="<?= url('/admin/photo-cover/' . $photo['photo_id']) ?>">
                                <button class="btn btn-sm btn-outline-success" type="submit">اجعلها الغلاف</button>
                            </form>
                        <?php endif; ?>

                        <form class="d-inline" method="post" action="<?= url('/admin/photo-up/' . $photo['photo_id']) ?>">
                            <button class="btn btn-sm btn-outline-secondary" type="submit">قبل</button>
                        </form>
                        <form class="d-inline" method="post" action="<?= url('/admin/photo-down/' . $photo['photo_id']) ?>">
                            <button class="btn btn-sm btn-outline-secondary" type="submit">بعد</button>
                        </form>
                        <form class="d-inline" method="post" action="<?= url('/admin/delete-photo/' . $photo['photo_id']) ?>" onsubmit="return confirm('هل تريد حذف هذه الصورة؟')">
                            <button class="btn btn-sm btn-outline-danger" type="submit">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h2 class="h5 mt-4 mb-3">رفع صورة</h2>

<form method="post" action="<?= url('/admin/photos/' . $restaurant['place_id']) ?>" enctype="multipart/form-data">
    <div class="mb-3">
        <input class="form-control" type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>
        <div class="form-text">JPG أو PNG أو WEBP، حتى 2 ميغابايت.</div>
    </div>

    <button class="btn btn-primary" type="submit">رفع الصورة</button>
    <a class="btn btn-secondary" href="<?= url('/admin/restaurants') ?>">رجوع</a>
</form>