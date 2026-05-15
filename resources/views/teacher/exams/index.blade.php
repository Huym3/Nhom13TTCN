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

{{-- Modal cảnh báo xóa --}}
@if(session('confirm_delete'))
<div id="modalXoa" style="
    position:fixed;inset:0;background:rgba(0,0,0,0.5);
    display:flex;align-items:center;justify-content:center;z-index:9999">
    <div style="background:#fff;border-radius:12px;padding:32px;max-width:460px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.3)">
        <div style="font-size:48px;text-align:center;margin-bottom:16px">⚠️</div>
        <h3 style="text-align:center;margin-bottom:8px;color:#dc2626">Cảnh báo xóa dữ liệu</h3>
        <p style="text-align:center;color:#64748b;margin-bottom:8px">
            Đề thi <strong>{{ session('ten_de_thi') }}</strong> đang có
            <strong style="color:#dc2626">{{ session('so_bai_lam') }} bài làm</strong> của học sinh.
        </p>
        <p style="text-align:center;color:#64748b;margin-bottom:24px">
            Toàn bộ kết quả, điểm số và lịch sử làm bài sẽ bị <strong>xóa vĩnh viễn</strong> và không thể khôi phục.
        </p>
        <div style="display:flex;gap:12px;justify-content:center">
            <button onclick="document.getElementById('modalXoa').remove()"
                style="padding:10px 24px;border:1px solid #cbd5e1;border-radius:8px;background:#fff;cursor:pointer;font-size:14px">
                Hủy bỏ
            </button>
            <form action="{{ route('teacher.exams.destroy', session('confirm_delete')) }}"
                  method="POST" style="display:inline">
                @csrf @method('DELETE')
                <input type="hidden" name="confirmed" value="1">
                <button type="submit"
                    style="padding:10px 24px;background:#dc2626;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:14px;font-weight:600">
                    🗑 Xóa tất cả
                </button>
            </form>
        </div>
    </div>
</div>
@endif

@if($exams->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">📄</div>
        <p>Bạn chưa có đề thi nào.</p>
        <a href="{{ route('teacher.exams.create') }}" class="btn-primary">Tạo đề thi đầu tiên</a>
    </div>
@else
    <div class="exam-list">
        @foreach($exams as $exam)
        <div class="exam-card">
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

                    <form action="{{ route('teacher.exams.destroy', $exam->MaDeThi) }}" method="POST" style="display:inline">
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
            </div>
        </div>
        @endforeach
    </div>
@endif


@if(session('confirm_unpublish'))
<div id="modalUnpublish" style="
    position:fixed;inset:0;background:rgba(0,0,0,0.5);
    display:flex;align-items:center;justify-content:center;z-index:9999">
    <div style="background:#fff;border-radius:12px;padding:32px;max-width:460px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.3)">
        <div style="font-size:48px;text-align:center;margin-bottom:16px">⚠️</div>
        <h3 style="text-align:center;margin-bottom:8px;color:#d97706">Cảnh báo chuyển về nháp</h3>
        <p style="text-align:center;color:#64748b;margin-bottom:8px">
            Đề thi <strong>{{ session('ten_de_thi_unpublish') }}</strong> đang có
            <strong style="color:#dc2626">{{ session('so_bai_lam_unpublish') }} bài làm</strong> của học sinh.
        </p>
        <p style="text-align:center;color:#64748b;margin-bottom:24px">
            Toàn bộ kết quả và điểm số sẽ bị <strong>xóa vĩnh viễn</strong>. Học sinh sẽ có thể làm lại bài này như mới khi đề được phát hành lại.
        </p>
        <div style="display:flex;gap:12px;justify-content:center">
            <button onclick="document.getElementById('modalUnpublish').remove()"
                style="padding:10px 24px;border:1px solid #cbd5e1;border-radius:8px;background:#fff;cursor:pointer;font-size:14px">
                Hủy bỏ
            </button>
            <form action="{{ route('teacher.exams.unpublish', session('confirm_unpublish')) }}"
                  method="POST" style="display:inline">
                @csrf @method('PUT')
                <input type="hidden" name="confirmed" value="1">
                <button type="submit"
                    style="padding:10px 24px;background:#d97706;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:14px;font-weight:600">
                    ⏸ Xác nhận về nháp
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endsection