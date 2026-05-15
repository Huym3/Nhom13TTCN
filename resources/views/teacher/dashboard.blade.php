{{-- resources/views/teacher/dashboard.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Dashboard Giáo viên')

@section('content')
<div class="dashboard">
    <h2>Xin chào, {{ Session::get('hoTen') }} 👋</h2>

    @php
        $maNguoiDung = Session::get('maNguoiDung');
        $tongCauHoi  = \Illuminate\Support\Facades\DB::table('Question')
                            ->where('MaNguoiTao', $maNguoiDung)->count();
        $tongDeThi   = \Illuminate\Support\Facades\DB::table('DeThi')
                            ->where('MaNguoiTaoDe', $maNguoiDung)->count();
        $tongPublish = \Illuminate\Support\Facades\DB::table('DeThi')
                            ->where('MaNguoiTaoDe', $maNguoiDung)
                            ->where('TrangThaiDe', 'Published')->count();
        $tongBaiLam  = \Illuminate\Support\Facades\DB::table('BaiLamCuaHS as bl')
                            ->join('DeThi as de', 'de.MaDeThi', '=', 'bl.MaDeThi')
                            ->where('de.MaNguoiTaoDe', $maNguoiDung)
                            ->whereNotNull('bl.ThoiGianNopBai')->count();
    @endphp

    <div class="stats-grid" style="margin-bottom:28px">
        <div class="stat-card">
            <div class="stat-number">{{ $tongCauHoi }}</div>
            <div class="stat-label">Câu hỏi đã tạo</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $tongDeThi }}</div>
            <div class="stat-label">Đề thi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color:#16a34a">{{ $tongPublish }}</div>
            <div class="stat-label">Đề đã phát hành</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $tongBaiLam }}</div>
            <div class="stat-label">Bài học sinh đã nộp</div>
        </div>
    </div>

    <div class="dashboard-grid">
        {{-- Đề thi gần đây --}}
        <div class="card">
            <h3>📋 Đề thi gần đây</h3>
            @php
                $deThi = \Illuminate\Support\Facades\DB::table('DeThi')
                    ->where('MaNguoiTaoDe', $maNguoiDung)
                    ->orderByDesc('NgayTao')->limit(5)->get();
            @endphp
            @forelse($deThi as $de)
            <div class="exam-item">
                <div>
                    <strong>{{ $de->TenDeThi }}</strong>
                    <span class="badge {{ $de->TrangThaiDe === 'Published' ? 'badge-published' : 'badge-draft' }}">
                        {{ $de->TrangThaiDe }}
                    </span>
                </div>
                <span style="font-size:13px;color:#94a3b8">{{ $de->ThoiGian }} phút</span>
            </div>
            @empty
                <p class="empty">Chưa có đề thi nào.</p>
            @endforelse
            <a href="{{ route('teacher.exams.index') }}" class="view-all">Xem tất cả →</a>
        </div>

        {{-- Câu hỏi gần đây --}}
        <div class="card">
            <h3>❓ Câu hỏi vừa tạo</h3>
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
        {{-- Hiển thị icon hoặc ảnh cực nhỏ để nhận biết câu hỏi có hình ảnh --}}
        <img src="{{ asset('storage/' . $q->HinhAnh) }}" 
             style="width: 40px; height: 30px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0;">
    @endif
    <div>
        <strong>{{ Str::limit($q->NoiDungCH, 45) }}</strong>
        <span class="badge-loai badge-loai-{{ strtolower($q->LoaiCauHoi) }}">{{ $q->LoaiCauHoi }}</span>
    </div>
</div>
                <span style="font-size:12px;color:#94a3b8">{{ $q->TenChuyenDe }}</span>
            </div>
            @empty
                <p class="empty">Chưa có câu hỏi nào.</p>
            @endforelse
            <a href="{{ route('teacher.questions.index') }}" class="view-all">Xem tất cả →</a>
        </div>
    </div>
</div>
@endsection