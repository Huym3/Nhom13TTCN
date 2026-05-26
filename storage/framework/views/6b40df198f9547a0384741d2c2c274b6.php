<?php $__env->startSection('title', 'Chi tiết bài làm'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-left">
        <h2>👁 Bài làm của <span style="color: var(--blue);"><?php echo e($baiLam->HoTen); ?></span></h2>
        <p><?php echo e($exam->TenDeThi); ?> — Nộp lúc <?php echo e(\Carbon\Carbon::parse($baiLam->ThoiGianNopBai)->format('d/m/Y H:i')); ?></p>
    </div>
    <a href="<?php echo e(route('teacher.exams.stats', $exam->MaDeThi)); ?>" class="btn-outline">← Quay lại thống kê</a>
</div>


<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card glass">
        <div class="stat-number" style="color:<?php echo e($baiLam->TongDiem >= 5 ? 'var(--green)' : 'var(--red)'); ?>">
            <?php echo e(number_format($baiLam->TongDiem, 2)); ?>

        </div>
        <div class="stat-label">Tổng điểm</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number"><?php echo e(number_format($baiLam->DiemPhan1, 2)); ?>đ</div>
        <div class="stat-label">Phần I</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number"><?php echo e(number_format($baiLam->DiemPhan2, 2)); ?>đ</div>
        <div class="stat-label">Phần II</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number"><?php echo e(number_format($baiLam->DiemPhan3, 2)); ?>đ</div>
        <div class="stat-label">Phần III</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number"><?php echo e(gmdate('i:s', $baiLam->TongThoiGianLamBai)); ?></div>
        <div class="stat-label">Thời gian làm</div>
    </div>
</div>


<?php if($ketQuaPhan1->count() > 0): ?>
<div class="form-card glass" style="margin-bottom:24px">
    <h3 class="section-title">PHẦN I — Trắc nghiệm</h3>
    <?php $__currentLoopData = $ketQuaPhan1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $borderColor = is_null($cau->DungSai) ? '#cbd5e1' : ($cau->DungSai ? 'var(--green)' : 'var(--red)');
        $bgColor = is_null($cau->DungSai) ? 'rgba(255,255,255,0.4)' : ($cau->DungSai ? 'rgba(5, 150, 105, 0.05)' : 'rgba(220, 38, 38, 0.05)');
    ?>
    <div style="margin-bottom:16px;padding:16px;border-radius:12px;border-left:4px solid <?php echo e($borderColor); ?>;background:<?php echo e($bgColor); ?>; border-top: 1px solid var(--glass-border-strong); border-right: 1px solid var(--glass-border-strong); border-bottom: 1px solid var(--glass-border-strong);">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <strong>Câu <?php echo e($i + 1); ?></strong>
            <span style="color:<?php echo e($borderColor); ?>;font-weight:600">
                <?php if(is_null($cau->DungSai)): ?>
                    <span style="color: var(--text-muted);">⏭ Chưa trả lời (0đ)</span>
                <?php elseif($cau->DungSai): ?>
                    ✅ Đúng (+<?php echo e($cau->DiemDatDuoc); ?>đ)
                <?php else: ?>
                    ❌ Sai (0đ)
                <?php endif; ?>
            </span>
        </div>
        <?php if(isset($cau->HinhAnh) && $cau->HinhAnh): ?>
            <img src="<?php echo e(asset('storage/' . $cau->HinhAnh)); ?>" style="max-width:300px;border-radius:8px;margin-bottom:12px; border: 1px solid var(--glass-border-strong);">
        <?php else: ?>
            <p style="margin-bottom:12px; color: var(--text-secondary);"><?php echo e($cau->NoiDungCH); ?></p>
        <?php endif; ?>
        <div style="display:flex;flex-direction:column;gap:6px">
            <?php $__currentLoopData = $cau->tatCaDapAn; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $da): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $bgOption = $da->LaDapAnDung ? 'rgba(34, 197, 94, 0.15)' : ($da->KyHieu === $cau->DaChon && !$da->LaDapAnDung ? 'rgba(239, 68, 68, 0.15)' : 'rgba(255,255,255,0.6)');
                $borderOption = $da->LaDapAnDung ? 'rgba(34, 197, 94, 0.4)' : ($da->KyHieu === $cau->DaChon ? 'rgba(239, 68, 68, 0.4)' : 'var(--glass-border-strong)');
            ?>
            <div style="padding:8px 12px;border-radius:8px; background:<?php echo e($bgOption); ?>; border:1px solid <?php echo e($borderOption); ?>">
                <strong><?php echo e($da->KyHieu); ?>.</strong> <span style="color: var(--text-secondary);"><?php echo e($da->NoiDungDapAn); ?></span>
                <?php if($da->LaDapAnDung): ?> <span style="color:var(--green);font-size:12px;margin-left:8px; font-weight: 600;">✓ Đáp án đúng</span> <?php endif; ?>
                <?php if($da->KyHieu === $cau->DaChon && !$da->LaDapAnDung): ?> <span style="color:var(--red);font-size:12px;margin-left:8px; font-weight: 600;">✗ HS chọn</span> <?php endif; ?>
                <?php if($da->KyHieu === $cau->DaChon && $da->LaDapAnDung): ?> <span style="color:var(--green);font-size:12px;margin-left:8px; font-weight: 600;">✓ HS chọn đúng</span> <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php if($cau->GiaiThich): ?>
        <div style="margin-top:12px;padding:12px;background:rgba(245, 158, 11, 0.1);border-radius:8px;font-size:13px; border: 1px solid rgba(245, 158, 11, 0.2); color: #92400e;">
            💡 <strong>Giải thích:</strong> <?php echo e($cau->GiaiThich); ?>

        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<?php if($ketQuaPhan2->count() > 0): ?>
<div class="form-card glass" style="margin-bottom:24px">
    <h3 class="section-title">PHẦN II — Đúng/Sai</h3>
    <?php $__currentLoopData = $ketQuaPhan2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="margin-bottom:16px;padding:16px;border-radius:12px;background:rgba(255,255,255,0.5);border:1px solid var(--glass-border-strong)">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <strong>Câu <?php echo e($i + 1); ?></strong>
            <span style="font-weight:600; color: var(--blue);">+<?php echo e($cau->diemDat); ?>đ</span>
        </div>
        <?php if(isset($cau->HinhAnh) && $cau->HinhAnh): ?>
            <img src="<?php echo e(asset('storage/' . $cau->HinhAnh)); ?>" style="max-width:300px;border-radius:8px;margin-bottom:12px; border: 1px solid var(--glass-border-strong);">
        <?php else: ?>
            <p style="margin-bottom:12px; color: var(--text-secondary);"><?php echo e($cau->NoiDungCH); ?></p>
        <?php endif; ?>
        <table class="data-table">
            <thead>
                <tr><th class="text-center">Ý</th><th class="text-center">Đáp án đúng</th><th class="text-center">HS chọn</th><th></th></tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cau->cacY; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr style="background:<?php echo e($y->DungSai ? 'rgba(5, 150, 105, 0.05)' : 'rgba(220, 38, 38, 0.05)'); ?>">
                    <td class="text-center"><strong><?php echo e(strtoupper($y->KyHieu)); ?></strong></td>
                    <td class="text-center"><?php echo e($y->DapAnDung ? 'Đúng' : 'Sai'); ?></td>
                    <td class="text-center">
                        <?php if(is_null($y->LuaChonCuaHocSinh)): ?> <em style="color:var(--text-muted)">Chưa trả lời</em>
                        <?php else: ?> <span style="font-weight: 500;"><?php echo e($y->LuaChonCuaHocSinh ? 'Đúng' : 'Sai'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center"><?php echo e($y->DungSai ? '✅' : '❌'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<?php if($ketQuaPhan3->count() > 0): ?>
<div class="form-card glass">
    <h3 class="section-title">PHẦN III — Trả lời số</h3>
    <?php $__currentLoopData = $ketQuaPhan3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="margin-bottom:16px;padding:16px;border-radius:12px;border-left:4px solid <?php echo e($cau->DungSai ? 'var(--green)' : 'var(--red)'); ?>;background:<?php echo e($cau->DungSai ? 'rgba(5, 150, 105, 0.05)' : 'rgba(220, 38, 38, 0.05)'); ?>; border-top: 1px solid var(--glass-border-strong); border-right: 1px solid var(--glass-border-strong); border-bottom: 1px solid var(--glass-border-strong);">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <strong>Câu <?php echo e($i + 1); ?></strong>
            <span style="color:<?php echo e($cau->DungSai ? 'var(--green)' : 'var(--red)'); ?>;font-weight:600">
                <?php echo e($cau->DungSai ? '✅ Đúng (+0.5đ)' : '❌ Sai (0đ)'); ?>

            </span>
        </div>
        <?php if(isset($cau->HinhAnh) && $cau->HinhAnh): ?>
            <img src="<?php echo e(asset('storage/' . $cau->HinhAnh)); ?>" style="max-width:300px;border-radius:8px;margin-bottom:12px; border: 1px solid var(--glass-border-strong);">
        <?php else: ?>
            <p style="margin-bottom:12px; color: var(--text-secondary);"><?php echo e($cau->NoiDungCH); ?></p>
        <?php endif; ?>
        <div style="display:flex;gap:32px;margin-top:12px; background: rgba(255,255,255,0.6); padding: 12px; border-radius: 8px; border: 1px solid var(--glass-border-strong);">
            <span>HS trả lời: <strong><?php echo e($cau->CauTraLoiSo ?? 'Chưa trả lời'); ?></strong></span>
            <span>Đáp án đúng: <strong style="color:var(--green)"><?php echo e($cau->DapAnSo); ?></strong></span>
        </div>
        <?php if($cau->GiaiThich): ?>
        <div style="margin-top:12px;padding:12px;background:rgba(245, 158, 11, 0.1);border-radius:8px;font-size:13px; border: 1px solid rgba(245, 158, 11, 0.2); color: #92400e;">
            💡 <strong>Giải thích:</strong> <?php echo e($cau->GiaiThich); ?>

        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/teacher/exams/xem-bai-lam.blade.php ENDPATH**/ ?>