<?php $__env->startSection('title', 'Kết quả bài làm'); ?>

<?php $__env->startSection('content'); ?>
<div class="result-page">

    
    <div class="result-summary glass" style="margin-bottom: 16px;">
        <h2><?php echo e($baiLam->TenDeThi); ?></h2>
        <div class="score-big <?php echo e($baiLam->TongDiem >= 5 ? 'pass' : 'fail'); ?>">
            <?php echo e(number_format($baiLam->TongDiem, 2)); ?><span style="font-size: 22px; font-weight: 400; opacity: 0.4;"> / 10</span>
        </div>
        <div class="score-detail">
            <div class="score-part">
                <span>Phần I</span>
                <strong><?php echo e(number_format($baiLam->DiemPhan1, 2)); ?>đ</strong>
            </div>
            <div class="score-part">
                <span>Phần II</span>
                <strong><?php echo e(number_format($baiLam->DiemPhan2, 2)); ?>đ</strong>
            </div>
            <div class="score-part">
                <span>Phần III</span>
                <strong><?php echo e(number_format($baiLam->DiemPhan3, 2)); ?>đ</strong>
            </div>
        </div>
        <?php
            $dauDung   = $ketQuaPhan1->filter(fn($c) => $c->DungSai === 1)->count();
            $dauSai    = $ketQuaPhan1->filter(fn($c) => $c->DungSai === 0)->count();
            $chuaLamP1 = $ketQuaPhan1->filter(fn($c) => is_null($c->DungSai))->count();
            $chuaLamP2 = $ketQuaPhan2->filter(function($cau) {
                return collect($cau->cacY)->filter(fn($y) => !is_null($y->LuaChonCuaHocSinh))->count() === 0;
            })->count();
            $chuaLamP3 = $ketQuaPhan3->filter(fn($c) => is_null($c->CauTraLoiSo))->count();
            $tongChuaLam = $chuaLamP1 + $chuaLamP2 + $chuaLamP3;
        ?>
        <div class="result-meta" style="margin-top: 12px;">
            <span>Chưa làm: <?php echo e($tongChuaLam); ?> câu</span>
            <span>Thời gian: <?php echo e(gmdate('i:s', $baiLam->TongThoiGianLamBai)); ?></span>
        </div>
    </div>

    
    <?php if($ketQuaPhan1->count() > 0): ?>
    <div class="result-section">
        <h3>PHẦN I — Trắc nghiệm</h3>
        <?php $__currentLoopData = $ketQuaPhan1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="result-question glass <?php echo e(is_null($cau->DungSai) ? 'chua-lam' : ($cau->DungSai ? 'dung' : 'sai')); ?>">
            <div class="rq-header">
                <span class="rq-num">Câu <?php echo e($i + 1); ?></span>
                <span class="rq-status" style="color: <?php echo e(is_null($cau->DungSai) ? 'var(--text-muted)' : ($cau->DungSai ? 'var(--green)' : 'var(--red)')); ?>">
                    <?php if(is_null($cau->DungSai)): ?> Chưa trả lời (0đ)
                    <?php elseif($cau->DungSai): ?> Đúng (+<?php echo e($cau->DiemDatDuoc); ?>đ)
                    <?php else: ?> Sai (0đ)
                    <?php endif; ?>
                </span>
            </div>
            <div class="rq-content">
                <?php if(isset($cau->HinhAnh) && $cau->HinhAnh): ?>
                    <img src="<?php echo e(asset('storage/' . $cau->HinhAnh)); ?>" style="max-width:100%; border-radius:8px; margin-bottom:8px;">
                <?php else: ?>
                    <?php echo $cau->NoiDungCH; ?>

                <?php endif; ?>
            </div>
            <div class="rq-options">
                <?php $__currentLoopData = $cau->tatCaDapAn; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $da): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rq-option
                    <?php echo e($da->LaDapAnDung ? 'correct' : ''); ?>

                    <?php echo e($da->KyHieu === $cau->DaChon && !$da->LaDapAnDung ? 'wrong-choice' : ''); ?>

                    <?php echo e($da->KyHieu === $cau->DaChon && $da->LaDapAnDung ? 'correct-choice' : ''); ?>">
                    <strong style="min-width:18px;"><?php echo e($da->KyHieu); ?>.</strong>
                    <?php echo e($da->NoiDungDapAn); ?>

                    <?php if($da->LaDapAnDung): ?> <span class="tag-correct">✓ Đáp án đúng</span> <?php endif; ?>
                    <?php if($da->KyHieu === $cau->DaChon && !$da->LaDapAnDung): ?> <span class="tag-wrong">✗ Bạn chọn</span> <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if($cau->GiaiThich): ?>
            <div class="rq-explain">💡 <?php echo e($cau->GiaiThich); ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <?php if($ketQuaPhan2->count() > 0): ?>
    <div class="result-section">
        <h3>PHẦN II — Đúng/Sai</h3>
        <?php $__currentLoopData = $ketQuaPhan2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $soYChuaLam = collect($cau->cacY)->filter(fn($y) => is_null($y->LuaChonCuaHocSinh))->count();
            $trangThai = $soYChuaLam == count($cau->cacY) ? 'chua-lam' : ($cau->diemDat > 0 ? 'dung' : 'sai');
        ?>
        <div class="result-question glass <?php echo e($trangThai); ?>">
            <div class="rq-header">
                <span class="rq-num">Câu <?php echo e($i + 1); ?></span>
                <span class="rq-status" style="color: <?php echo e($trangThai === 'chua-lam' ? 'var(--text-muted)' : ($cau->diemDat > 0 ? 'var(--green)' : 'var(--red)')); ?>">
                    <?php if($trangThai === 'chua-lam'): ?> Chưa trả lời (0đ)
                    <?php else: ?> +<?php echo e($cau->diemDat); ?>đ
                    <?php endif; ?>
                </span>
            </div>
            <div class="rq-content">
                <?php if(isset($cau->HinhAnh) && $cau->HinhAnh): ?>
                    <img src="<?php echo e(asset('storage/' . $cau->HinhAnh)); ?>" style="max-width:100%; border-radius:8px; margin-bottom:8px;">
                <?php else: ?>
                    <?php echo $cau->NoiDungCH; ?>

                <?php endif; ?>
            </div>
            <div style="padding: 0 14px 14px;">
                <table class="ds-result-table">
                    <thead>
                        <tr><th>Ý</th><th>Đáp án đúng</th><th>Bạn chọn</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $cau->cacY; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($y->DungSai ? 'dung' : 'sai'); ?>">
                            <td><strong><?php echo e($y->KyHieu); ?></strong></td>
                            <td><?php echo e($y->DapAnDung ? 'Đúng' : 'Sai'); ?></td>
                            <td>
                                <?php if(is_null($y->LuaChonCuaHocSinh)): ?> <em style="color:var(--text-muted);">Chưa trả lời</em>
                                <?php else: ?> <?php echo e($y->LuaChonCuaHocSinh ? 'Đúng' : 'Sai'); ?>

                                <?php endif; ?>
                            </td>
                            <td><?php echo e($y->DungSai ? '✅' : '❌'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <?php if($ketQuaPhan3->count() > 0): ?>
    <div class="result-section">
        <h3>PHẦN III — Trả lời ngắn</h3>
        <?php $__currentLoopData = $ketQuaPhan3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="result-question glass <?php echo e(is_null($cau->DungSai) ? 'chua-lam' : ($cau->DungSai ? 'dung' : 'sai')); ?>">
            <div class="rq-header">
                <span class="rq-num">Câu <?php echo e($i + 1); ?></span>
                <span class="rq-status" style="color: <?php echo e(is_null($cau->DungSai) ? 'var(--text-muted)' : ($cau->DungSai ? 'var(--green)' : 'var(--red)')); ?>">
                    <?php if(is_null($cau->DungSai)): ?> Chưa trả lời (0đ)
                    <?php elseif($cau->DungSai): ?> Đúng (+0.5đ)
                    <?php else: ?> Sai (0đ)
                    <?php endif; ?>
                </span>
            </div>
            <div class="rq-content">
                <?php if(isset($cau->HinhAnh) && $cau->HinhAnh): ?>
                    <img src="<?php echo e(asset('storage/' . $cau->HinhAnh)); ?>" style="max-width:100%; border-radius:8px; margin-bottom:8px;">
                <?php else: ?>
                    <?php echo $cau->NoiDungCH; ?>

                <?php endif; ?>
            </div>
            <div class="tls-result">
                <span>Bạn trả lời: <strong><?php echo e($cau->CauTraLoiSo ?? 'Chưa trả lời'); ?></strong></span>
                <span>Đáp án đúng: <strong style="color: var(--green);"><?php echo e($cau->DapAnSo); ?></strong></span>
            </div>
            <?php if($cau->GiaiThich): ?>
            <div class="rq-explain">💡 <?php echo e($cau->GiaiThich); ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <div class="result-actions">
        <a href="<?php echo e(route('student.results.index')); ?>" class="btn-outline">← Lịch sử bài làm</a>
        <a href="<?php echo e(route('student.exams')); ?>" class="btn-primary">Làm đề khác</a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/student/results/show.blade.php ENDPATH**/ ?>