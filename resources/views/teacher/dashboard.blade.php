@extends('layouts.teacher')
@section('title', 'Dashboard Giáo viên')

@section('content')
<div class="dashboard">
    <div class="page-header" style="border-bottom: none; margin-bottom: 10px;">
        <div class="page-header-left">
            <h2 class="page-title">Xin chào, {{ Session::get('hoTen') }} 👋</h2>
            <p class="page-sub">Chào mừng trở lại không gian quản lý của bạn.</p>
        </div>
    </div>

    @php
        $maNguoiDung = Session::get('maNguoiDung');
        $tongCauHoi  = \Illuminate\Support\Facades\DB::table('Question')->where('MaNguoiTao', $maNguoiDung)->count();
        $tongDeThi   = \Illuminate\Support\Facades\DB::table('DeThi')->where('MaNguoiTaoDe', $maNguoiDung)->count();
        $tongPublish = \Illuminate\Support\Facades\DB::table('DeThi')->where('MaNguoiTaoDe', $maNguoiDung)->where('TrangThaiDe', 'Published')->count();
        $tongBaiLam  = \Illuminate\Support\Facades\DB::table('BaiLamCuaHS as bl')->join('DeThi as de', 'de.MaDeThi', '=', 'bl.MaDeThi')->where('de.MaNguoiTaoDe', $maNguoiDung)->whereNotNull('bl.ThoiGianNopBai')->count();
    @endphp

    <div class="stats-grid" style="margin-bottom:28px">
        <div class="stat-card glass">
            <div class="stat-number">{{ $tongCauHoi }}</div>
            <div class="stat-label">Câu hỏi đã tạo</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number">{{ $tongDeThi }}</div>
            <div class="stat-label">Đề thi</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number" style="color:var(--green)">{{ $tongPublish }}</div>
            <div class="stat-label">Đề đã phát hành</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number">{{ $tongBaiLam }}</div>
            <div class="stat-label">Bài học sinh nộp</div>
        </div>
    </div>

    <div class="dashboard-grid">
        {{-- Đề thi gần đây --}}
        <div class="card glass">
            <h3 class="card-title">📋 Đề thi gần đây</h3>
            @php
                $deThi = \Illuminate\Support\Facades\DB::table('DeThi')->where('MaNguoiTaoDe', $maNguoiDung)->orderByDesc('NgayTao')->limit(5)->get();
            @endphp
            @forelse($deThi as $de)
            <div class="exam-item">
                <div>
                    <strong>{{ $de->TenDeThi }}</strong>
                    <span class="badge {{ $de->TrangThaiDe === 'Published' ? 'badge-published' : 'badge-draft' }}">
                        {{ $de->TrangThaiDe }}
                    </span>
                </div>
                <span style="font-size:13px;color:var(--text-muted)">{{ $de->ThoiGian }} phút</span>
            </div>
            @empty
                <p class="empty">Chưa có đề thi nào.</p>
            @endforelse
            <a href="{{ route('teacher.exams.index') }}" class="view-all">Xem tất cả →</a>
        </div>

        {{-- Câu hỏi gần đây --}}
        <div class="card glass">
            <h3 class="card-title">❓ Câu hỏi vừa tạo</h3>
            @php
                $cauHoi = \Illuminate\Support\Facades\DB::table('Question as q')
                    ->join('ChuyenDe as cd', 'cd.MaChuyenDe', '=', 'q.MaChuyenDe')
                    ->where('q.MaNguoiTao', $maNguoiDung)
                    ->orderByDesc('q.NgayTao')->limit(5)
                    ->select('q.*', 'cd.TenChuyenDe')->get();
            @endphp
            @forelse($cauHoi as $q)
            <div class="exam-item">
                <div style="display: flex; align-items: center; gap: 10px;">
                    @if($q->HinhAnh)
                        <img src="{{ asset('storage/' . $q->HinhAnh) }}" style="width: 40px; height: 30px; object-fit: cover; border-radius: 4px; border: 1px solid var(--glass-border-strong);">
                    @endif
                    <div>
                        <strong>{{ Str::limit($q->NoiDungCH, 45) }}</strong>
                        <span class="badge-loai badge-loai-{{ strtolower($q->LoaiCauHoi) }}">{{ $q->LoaiCauHoi }}</span>
                    </div>
                </div>
                <span style="font-size:12px;color:var(--text-muted)">{{ $q->TenChuyenDe }}</span>
            </div>
            @empty
                <p class="empty">Chưa có câu hỏi nào.</p>
            @endforelse
            <a href="{{ route('teacher.questions.index') }}" class="view-all">Xem tất cả →</a>
        </div>
    </div>
</div>
@endsection