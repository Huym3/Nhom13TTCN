{{-- resources/views/teacher/exams/stats.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Thống kê đề thi')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>📊 Thống kê: {{ $exam->TenDeThi }}</h2>
        <p>Tổng hợp kết quả học sinh</p>
    </div>
    <a href="{{ route('teacher.exams.index') }}" class="btn-outline">← Quay lại</a>
</div>

@if(!$tongQuan || $tongQuan->TongBaiLam == 0)
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <p>Chưa có học sinh nào nộp bài cho đề thi này.</p>
    </div>
@else

{{-- ── Tổng quan ──────────────────────────────────────── --}}
<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-number">{{ $tongQuan->TongBaiLam }}</div>
        <div class="stat-label">Số bài đã nộp</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ number_format($tongQuan->DiemTrungBinh, 2) }}</div>
        <div class="stat-label">Điểm trung bình</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color:#16a34a">{{ number_format($tongQuan->DiemCaoNhat, 2) }}</div>
        <div class="stat-label">Điểm cao nhất</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color:#dc2626">{{ number_format($tongQuan->DiemThapNhat, 2) }}</div>
        <div class="stat-label">Điểm thấp nhất</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ gmdate('i:s', round($tongQuan->ThoiGianTB)) }}</div>
        <div class="stat-label">Thời gian làm TB</div>
    </div>
</div>

{{-- ── Phân bố điểm ───────────────────────────────────── --}}
<div class="form-card" style="margin-bottom:24px">
    <h3 class="section-title">Phân bố điểm</h3>
    <div class="chart-wrap">
        @php $maxSL = $phanBoDiem->max('SoLuong') ?: 1; @endphp
        @foreach($phanBoDiem as $pb)
        <div class="chart-bar-row">
            <div class="chart-label">{{ $pb->KhoangDiem }}</div>
            <div class="chart-bar-bg">
                <div class="chart-bar-fill"
                     style="width: {{ ($pb->SoLuong / $maxSL) * 100 }}%">
                </div>
            </div>
            <div class="chart-val">{{ $pb->SoLuong }} bài</div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── Top học sinh ────────────────────────────────────── --}}
<div class="form-card" style="margin-bottom:24px">
    <h3 class="section-title">🏆 Top 5 học sinh điểm cao nhất</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="50">#</th>
                <th>Họ tên</th>
                <th width="100">Tổng điểm</th>
                <th width="100">Câu đúng</th>
                <th width="120">Thời gian làm</th>
                <th width="130">Nộp lúc</th>
                <th width="100">Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topHocSinh as $i => $hs)
            <tr>
                <td class="text-center">
                    {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i + 1)) }}
                </td>
                <td>{{ $hs->HoTen }}</td>
                <td class="text-center">
                    <strong class="{{ $hs->TongDiem >= 5 ? 'text-green' : 'text-red' }}">
                        {{ number_format($hs->TongDiem, 2) }}
                    </strong>
                </td>
                <td class="text-center">{{ $hs->SoCauDung }}</td>
                <td class="text-center">{{ gmdate('i:s', $hs->TongThoiGianLamBai) }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($hs->ThoiGianNopBai)->format('d/m H:i') }}</td>
                <td class="text-center">
                    <a href="{{ route('exams.xemBaiLam', [$exam->MaDeThi, $hs->MaBaiLam]) }}"
                       class="btn-outline" style="padding:4px 10px;font-size:13px">👁 Xem</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ── Tỉ lệ đúng từng câu TN ─────────────────────────── --}}
@if($tiLeUngCauTN->isNotEmpty())
<div class="form-card">
    <h3 class="section-title">Tỉ lệ trả lời đúng — Phần I (Trắc nghiệm)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="60">Câu</th>
                <th>Nội dung (trích)</th>
                <th width="100">Số trả lời</th>
                <th width="100">Số đúng</th>
                <th width="120">Tỉ lệ đúng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiLeUngCauTN as $r)
            @php
                $tiLe = $r->TongTraLoi > 0 ? round($r->SoDung / $r->TongTraLoi * 100) : 0;
                $color = $tiLe >= 70 ? '#16a34a' : ($tiLe >= 40 ? '#d97706' : '#dc2626');
            @endphp
            <tr>
                <td class="text-center font-bold">{{ $r->ThuTu }}</td>
                                {{-- Tìm dòng <td>{{ $r->NoiDung }}…</td> và sửa thành: --}}
                <td>
                    @if(isset($r->HinhAnh) && $r->HinhAnh)
                        <img src="{{ asset('storage/' . $r->HinhAnh) }}" style="width: 80px; height: auto; border-radius: 4px;">
                    @else
                        {{ $r->NoiDung ?? '' }}…
                    @endif
                </td>
                <td class="text-center">{{ $r->TongTraLoi }}</td>
                <td class="text-center">{{ $r->SoDung }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="flex:1;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden">
                            <div style="width:{{ $tiLe }}%;height:100%;background:{{ $color }};border-radius:4px"></div>
                        </div>
                        <span style="font-weight:700;color:{{ $color }};min-width:36px">{{ $tiLe }}%</span>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif


{{-- ── Tỉ lệ đúng từng ý DS ─────────────────────────── --}}
@if($tiLeUngCauDS->isNotEmpty())
<div class="form-card" style="margin-bottom:24px">
    <h3 class="section-title">Tỉ lệ trả lời đúng — Phần II (Đúng/Sai)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="60">Câu</th>
                <th width="120">Nội dung</th>
                <th width="40">Ý</th>
                <th width="80">Đáp án</th>
                <th width="100">Số trả lời</th>
                <th width="100">Số đúng</th>
                <th width="120">Tỉ lệ đúng</th>
            </tr>
        </thead>
        <tbody>
            @php $prevCau = null; @endphp
            @foreach($tiLeUngCauDS as $r)
            @php
                $tiLe = $r->TongTraLoi > 0 ? round($r->SoDung / $r->TongTraLoi * 100) : 0;
                $color = $tiLe >= 70 ? '#16a34a' : ($tiLe >= 40 ? '#d97706' : '#dc2626');
                $isFirstRow = $r->ThuTu != $prevCau;
                $soY = $tiLeUngCauDS->where('ThuTu', $r->ThuTu)->count();
            @endphp
            <tr>
                @if($isFirstRow)
                    <td class="text-center font-bold" rowspan="{{ $soY }}" style="vertical-align:middle">
                        {{ $r->ThuTu }}
                    </td>
                    <td rowspan="{{ $soY }}" style="vertical-align:middle">
                        @if(isset($r->HinhAnh) && $r->HinhAnh)
                            <img src="{{ asset('storage/' . $r->HinhAnh) }}" style="width:80px;height:auto;border-radius:4px">
                        @else
                            {{ $r->NoiDung ?? '' }}…
                        @endif
                    </td>
                @endif
                <td class="text-center font-bold">{{ strtoupper($r->KyHieu) }}</td>
                <td class="text-center">
                    <span style="color:{{ $r->DapAnDung ? '#16a34a' : '#dc2626' }};font-weight:700">
                        {{ $r->DapAnDung ? 'Đúng' : 'Sai' }}
                    </span>
                </td>
                <td class="text-center">{{ $r->TongTraLoi }}</td>
                <td class="text-center">{{ $r->SoDung }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="flex:1;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden">
                            <div style="width:{{ $tiLe }}%;height:100%;background:{{ $color }};border-radius:4px"></div>
                        </div>
                        <span style="font-weight:700;color:{{ $color }};min-width:36px">{{ $tiLe }}%</span>
                    </div>
                </td>
            </tr>
            @php $prevCau = $r->ThuTu; @endphp
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ── Tỉ lệ đúng câu TLS ──────────────────────────── --}}
@if($tiLeUngCauTLS->isNotEmpty())
<div class="form-card">
    <h3 class="section-title">Tỉ lệ trả lời đúng — Phần III (Trả lời số)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="60">Câu</th>
                <th>Nội dung</th>
                <th width="100">Đáp án</th>
                <th width="100">Số trả lời</th>
                <th width="100">Số đúng</th>
                <th width="120">Tỉ lệ đúng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiLeUngCauTLS as $r)
            @php
                $tiLe = $r->TongTraLoi > 0 ? round($r->SoDung / $r->TongTraLoi * 100) : 0;
                $color = $tiLe >= 70 ? '#16a34a' : ($tiLe >= 40 ? '#d97706' : '#dc2626');
            @endphp
            <tr>
                <td class="text-center font-bold">{{ $r->ThuTu }}</td>
                <td>
                    @if(isset($r->HinhAnh) && $r->HinhAnh)
                        <img src="{{ asset('storage/' . $r->HinhAnh) }}" style="width:80px;height:auto;border-radius:4px">
                    @else
                        {{ $r->NoiDung ?? '' }}…
                    @endif
                </td>
                <td class="text-center font-bold">
                    {{ $r->DapAnSo }}
                    <small style="color:#64748b">(±{{ $r->SaiSoChapNhan }})</small>
                </td>
                <td class="text-center">{{ $r->TongTraLoi }}</td>
                <td class="text-center">{{ $r->SoDung }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="flex:1;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden">
                            <div style="width:{{ $tiLe }}%;height:100%;background:{{ $color }};border-radius:4px"></div>
                        </div>
                        <span style="font-weight:700;color:{{ $color }};min-width:36px">{{ $tiLe }}%</span>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endif {{-- end nếu có bài làm --}}
@endsection