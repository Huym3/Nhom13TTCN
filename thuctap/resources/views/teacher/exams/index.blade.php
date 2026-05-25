{{-- resources/views/teacher/exams/index.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Quản lý đề thi')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>📋 Quản lý đề thi</h2>
        <p>Tạo và quản lý đề thi cho học sinh</p>
    </div>
    <a href="{{ route('teacher.exams.create') }}" class="btn-primary">+ Tạo đề mới</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

{{-- Modal cảnh báo xóa (Cập nhật giao diện Glass) --}}
@if(session('confirm_delete'))
<div id="modalXoa" style="
    position:fixed; inset:0; background:rgba(15,23,42,0.3); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    display:flex; align-items:center; justify-content:center; z-index:9999;">
    <div class="glass-strong" style="padding:32px; max-width:460px; width:90%; text-align:center;">
        <div style="font-size:48px; margin-bottom:16px;">⚠️</div>
        <h3 style="margin-bottom:8px; color:var(--red);">Cảnh báo xóa dữ liệu</h3>
        <p style="color:var(--text-secondary); margin-bottom:8px; font-size:14px;">
            Đề thi <strong>{{ session('ten_de_thi') }}</strong> đang có
            <strong style="color:var(--red);">{{ session('so_bai_lam') }} bài làm</strong> của học sinh.
        </p>
        <p style="color:var(--text-muted); margin-bottom:24px; font-size:13px; line-height:1.6;">
            Toàn bộ kết quả, điểm số và lịch sử làm bài sẽ bị <strong>xóa vĩnh viễn</strong> và không thể khôi phục.
        </p>
        <div style="display:flex; gap:12px; justify-content:center;">
            <button onclick="document.getElementById('modalXoa').remove()" class="btn-outline">
                Hủy bỏ
            </button>
            <form action="{{ route('teacher.exams.destroy', session('confirm_delete')) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <input type="hidden" name="confirmed" value="1">
                <button type="submit" class="btn-danger" style="padding: 9px 20px;">
                    🗑 Xóa tất cả
                </button>
            </form>
        </div>
    </div>
</div>
@endif

@if($exams->isEmpty())
    <div class="empty-state glass" style="padding: 60px;">
        <div class="empty-icon">📄</div>
        <p>Bạn chưa có đề thi nào.</p>
        <a href="{{ route('teacher.exams.create') }}" class="btn-primary" style="margin-top: 10px;">Tạo đề thi đầu tiên</a>
    </div>
@else
    <div class="exam-list">
        @foreach($exams as $exam)
        <div class="exam-card glass">
            <div class="exam-card-left">
                <div class="exam-card-header">
                    <h3>{{ $exam->TenDeThi }}</h3>
                    <span class="badge badge-{{ strtolower($exam->TrangThaiDe) }}">
                        {{ $exam->TrangThaiDe === 'Published' ? '🟢 Đã phát hành' : '⚪ Nháp' }}
                    </span>
                </div>
                <div class="exam-meta">
                    @if($exam->MaDe)
                        <span>🔢 Mã đề: {{ $exam->MaDe }}</span>
                    @endif
                    <span>⏱ {{ $exam->ThoiGian }} phút</span>
                    <span>📝 {{ $exam->SoCauThucTe }} câu hỏi</span>
                    <span>👥 {{ $exam->SoBaiLam }} bài đã nộp</span>
                    <span>📅 {{ \Carbon\Carbon::parse($exam->NgayTao)->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="exam-card-actions">
                @if($exam->TrangThaiDe === 'Draft')
                    <a href="{{ route('teacher.exams.edit', $exam->MaDeThi) }}" class="btn-outline">✏️ Sửa</a>

                    <form action="{{ route('teacher.exams.publish', $exam->MaDeThi) }}" method="POST" style="display:inline">
                        @csrf @method('PUT')
                        <button type="submit" class="btn-success"
                            onclick="return confirm('Phát hành đề này? Học sinh sẽ có thể làm bài.')">
                            🚀 Phát hành
                        </button>
                    </form>

                    <form action="{{ route('teacher.exams.destroy', $exam->MaDeThi) }}" method="POST" style="display:none">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger"
                            onclick="return confirm('Xóa đề thi này?')">🗑 Xóa</button>
                    </form>
                @else
                    <a href="{{ route('teacher.exams.stats', $exam->MaDeThi) }}" class="btn-outline">📊 Thống kê</a>

                    <form action="{{ route('teacher.exams.unpublish', $exam->MaDeThi) }}" method="POST" style="display:inline">
                        @csrf @method('PUT')
                        <button type="submit" class="btn-outline"
                            onclick="return confirm('Chuyển về nháp? Học sinh sẽ không làm được bài trong thời gian này.')">
                            ⏸ Về nháp
                        </button>
                    </form>
                @endif
                <form action="{{ route('teacher.exams.destroy', $exam->MaDeThi) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger"
                        onclick="return confirm('Xóa đề thi này? Nếu đề đã có bài làm, hệ thống sẽ hỏi xác nhận thêm trước khi xóa dữ liệu.')">Xóa</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- Modal cảnh báo chuyển về nháp (Cập nhật giao diện Glass) --}}
@if(session('confirm_unpublish'))
<div id="modalUnpublish" style="
    position:fixed; inset:0; background:rgba(15,23,42,0.3); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    display:flex; align-items:center; justify-content:center; z-index:9999;">
    <div class="glass-strong" style="padding:32px; max-width:460px; width:90%; text-align:center;">
        <div style="font-size:48px; margin-bottom:16px;">⚠️</div>
        <h3 style="margin-bottom:8px; color:var(--amber);">Cảnh báo chuyển về nháp</h3>
        <p style="color:var(--text-secondary); margin-bottom:8px; font-size:14px;">
            Đề thi <strong>{{ session('ten_de_thi_unpublish') }}</strong> đang có
            <strong style="color:var(--red);">{{ session('so_bai_lam_unpublish') }} bài làm</strong> của học sinh.
        </p>
        <p style="color:var(--text-muted); margin-bottom:24px; font-size:13px; line-height:1.6;">
            Toàn bộ kết quả và điểm số sẽ bị <strong>xóa vĩnh viễn</strong>. Học sinh sẽ có thể làm lại bài này như mới khi đề được phát hành lại.
        </p>
        <div style="display:flex; gap:12px; justify-content:center;">
            <button onclick="document.getElementById('modalUnpublish').remove()" class="btn-outline">
                Hủy bỏ
            </button>
            <form action="{{ route('teacher.exams.unpublish', session('confirm_unpublish')) }}" method="POST" style="display:inline">
                @csrf @method('PUT')
                <input type="hidden" name="confirmed" value="1">
                <button type="submit" style="padding:9px 20px; background:var(--amber); color:#fff; border:none; border-radius:var(--radius-sm); cursor:pointer; font-size:14px; font-weight:600; font-family:var(--font-main);">
                    ⏸ Xác nhận về nháp
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endsection