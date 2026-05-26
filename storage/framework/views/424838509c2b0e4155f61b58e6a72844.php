<?php $__env->startSection('title', 'Dashboard Giáo viên'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard">
    <div class="page-header" style="border-bottom: none; margin-bottom: 10px;">
        <div class="page-header-left">
            <h2 class="page-title">Xin chào, <?php echo e(Session::get('hoTen')); ?> 👋</h2>
            <p class="page-sub">Chào mừng trở lại không gian quản lý của bạn.</p>
        </div>
    </div>

    <?php
        $maNguoiDung = Session::get('maNguoiDung');
        $tongCauHoi  = \Illuminate\Support\Facades\DB::table('Question')->where('MaNguoiTao', $maNguoiDung)->count();
        $tongDeThi   = \Illuminate\Support\Facades\DB::table('DeThi')->where('MaNguoiTaoDe', $maNguoiDung)->count();
        $tongPublish = \Illuminate\Support\Facades\DB::table('DeThi')->where('MaNguoiTaoDe', $maNguoiDung)->where('TrangThaiDe', 'Published')->count();
        $tongBaiLam  = \Illuminate\Support\Facades\DB::table('BaiLamCuaHS as bl')->join('DeThi as de', 'de.MaDeThi', '=', 'bl.MaDeThi')->where('de.MaNguoiTaoDe', $maNguoiDung)->whereNotNull('bl.ThoiGianNopBai')->count();
    ?>

    <div class="stats-grid" style="margin-bottom:28px">
        <div class="stat-card glass">
            <div class="stat-number"><?php echo e($tongCauHoi); ?></div>
            <div class="stat-label">Câu hỏi đã tạo</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number"><?php echo e($tongDeThi); ?></div>
            <div class="stat-label">Đề thi</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number" style="color:var(--green)"><?php echo e($tongPublish); ?></div>
            <div class="stat-label">Đề đã phát hành</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number"><?php echo e($tongBaiLam); ?></div>
            <div class="stat-label">Bài học sinh nộp</div>
        </div>
    </div>

    <div class="dashboard-grid">
        
        <div class="card glass">
            <h3 class="card-title">📋 Đề thi gần đây</h3>
            <?php
                $deThi = \Illuminate\Support\Facades\DB::table('DeThi')->where('MaNguoiTaoDe', $maNguoiDung)->orderByDesc('NgayTao')->limit(5)->get();
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $deThi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $de): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="exam-item">
                <div>
                    <strong><?php echo e($de->TenDeThi); ?></strong>
                    <span class="badge <?php echo e($de->TrangThaiDe === 'Published' ? 'badge-published' : 'badge-draft'); ?>">
                        <?php echo e($de->TrangThaiDe); ?>

                    </span>
                </div>
                <span style="font-size:13px;color:var(--text-muted)"><?php echo e($de->ThoiGian); ?> phút</span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="empty">Chưa có đề thi nào.</p>
            <?php endif; ?>
            <a href="<?php echo e(route('teacher.exams.index')); ?>" class="view-all">Xem tất cả →</a>
        </div>

        
        <div class="card glass">
            <h3 class="card-title">❓ Câu hỏi vừa tạo</h3>
            <?php
                $cauHoi = \Illuminate\Support\Facades\DB::table('Question as q')
                    ->join('ChuyenDe as cd', 'cd.MaChuyenDe', '=', 'q.MaChuyenDe')
                    ->where('q.MaNguoiTao', $maNguoiDung)
                    ->orderByDesc('q.NgayTao')->limit(5)
                    ->select('q.*', 'cd.TenChuyenDe')->get();
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $cauHoi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="exam-item">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <?php if($q->HinhAnh): ?>
                        <img src="<?php echo e(asset('storage/' . $q->HinhAnh)); ?>" style="width: 40px; height: 30px; object-fit: cover; border-radius: 4px; border: 1px solid var(--glass-border-strong);">
                    <?php endif; ?>
                    <div>
                        <strong><?php echo e(Str::limit($q->NoiDungCH, 45)); ?></strong>
                        <span class="badge-loai badge-loai-<?php echo e(strtolower($q->LoaiCauHoi)); ?>"><?php echo e($q->LoaiCauHoi); ?></span>
                    </div>
                </div>
                <span style="font-size:12px;color:var(--text-muted)"><?php echo e($q->TenChuyenDe); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="empty">Chưa có câu hỏi nào.</p>
            <?php endif; ?>
            <a href="<?php echo e(route('teacher.questions.index')); ?>" class="view-all">Xem tất cả →</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/Teacher/Dashboard.blade.php ENDPATH**/ ?>