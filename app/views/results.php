<h1 class="h4 mb-3"><?= count($restaurants) ?> مطعم</h1>

<div class="row g-3">
    <?php foreach ($restaurants as $r): ?>
        <div class="col-12 col-md-6 col-lg-4">
            <a class="card h-100 text-decoration-none" href="<?= url('/restaurant/' . $r['place_id']) ?>">
                <div class="card-body">
                    <h2 class="h5"><?= e($r['place_name']) ?></h2>
                    <div class="text-muted small"><?= e($r['categories']) ?></div>
                    <div class="mt-2">
                        ★ <?= e($r['rating']) ?> · <?= price_label($r['price_range']) ?>
                        <?php if ($r['is_open']): ?>
                            <span class="badge bg-success">مفتوح الآن</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">مغلق الآن</span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
