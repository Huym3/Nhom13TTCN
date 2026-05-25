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
    <div class="empty-state glass">
        <div class="empty-icon">📭</div>
        <p>Chưa có học sinh nào nộp bài cho đề thi này.</p>
    </div>
@else

{{-- ── Tổng quan ──────────────────────────────────────── --}}
<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card glass">
        <div class="stat-number">{{ $tongQuan->TongBaiLam }}</div>
        <div class="stat-label">Số bài đã nộp</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number">{{ number_format($tongQuan->DiemTrungBinh, 2) }}</div>
        <div class="stat-label">Điểm trung bình</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number" style="color:var(--green)">{{ number_format($tongQuan->DiemCaoNhat, 2) }}</div>
        <div class="stat-label">Điểm cao nhất</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number" style="color:var(--red)">{{ number_format($tongQuan->DiemThapNhat, 2) }}</div>
        <div class="stat-label">Điểm thấp nhất</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number">{{ gmdate('i:s', round($tongQuan->ThoiGianTB)) }}</div>
        <div class="stat-label">Thời gian làm TB</div>
    </div>
</div>

{{-- ── Phân bố điểm ───────────────────────────────────── --}}
<div class="form-card glass" style="margin-bottom:24px">
    <h3 class="section-title">Phân bố điểm</h3>
    <canvas id="chartPhanBo" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($phanBoDiem->pluck('KhoangDiem'));
const data   = @json($phanBoDiem->pluck('SoLuong'));

new Chart(document.getElementById('chartPhanBo'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Số bài',
            data: data,
            backgroundColor: 'rgba(37, 99, 235, 0.75)',
            borderColor: '#2563eb',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.parsed.y} bài`
                }
            }
        },
        scales: {
            x: {
                title: { display: true, text: 'Khoảng điểm', font: { size: 13, family: "'Plus Jakarta Sans', sans-serif" } },
                grid: { display: false }
            },
            y: {
                title: { display: true, text: 'Số học sinh', font: { size: 13, family: "'Plus Jakarta Sans', sans-serif" } },
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>

{{-- ── Top học sinh ────────────────────────────────────── --}}
<div class="form-card glass" style="margin-bottom:24px">
    <h3 class="section-title">🏆 Top 5 học sinh điểm cao nhất</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="50" class="text-center">#</th>
                <th>Họ tên</th>
                <th width="100" class="text-center">Tổng điểm</th>
                <th width="100" class="text-center">Câu đúng</th>
                <th width="120" class="text-center">Thời gian làm</th>
                <th width="130" class="text-center">Nộp lúc</th>
                <th width="100" class="text-center">Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topHocSinh as $i => $hs)
            <tr>
                <td class="text-center">
                    {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i + 1)) }}
                </td>
                <td style="font-weight: 500;">{{ $hs->HoTen }}</td>
                <td class="text-center">
                    <strong class="{{ $hs->TongDiem >= 5 ? 'text-green' : 'text-red' }}">
                        {{ number_format($hs->TongDiem, 2) }}
                    </strong>
                </td>
                <td class="text-center">{{ $hs->SoCauDung }}</td>
                <td class="text-center" style="font-family: var(--font-mono)">{{ gmdate('i:s', $hs->TongThoiGianLamBai) }}</td>
                <td class="text-center" style="font-size: 12px; color: var(--text-muted)">{{ \Carbon\Carbon::parse($hs->ThoiGianNopBai)->format('d/m H:i') }}</td>
                <td class="text-center">
                    <a href="{{ route('teacher.exams.xemBaiLam', [$exam->MaDeThi, $hs->MaBaiLam]) }}"
                       class="btn-outline btn-sm">👁 Xem</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ── Tỉ lệ đúng từng câu TN ─────────────────────────── --}}
@if($tiLeUngCauTN->isNotEmpty())
<div class="form-card glass">
    <h3 class="section-title">Tỉ lệ trả lời đúng — Phần I (Trắc nghiệm)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="60" class="text-center">Câu</th>
                <th>Nội dung (trích)</th>
                <th width="100" class="text-center">Số trả lời</th>
                <th width="100" class="text-center">Số đúng</th>
                <th width="120">Tỉ lệ đúng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiLeUngCauTN as $r)
            @php
                $tiLe = $r->TongTraLoi > 0 ? round($r->SoDung / $r->TongTraLoi * 100) : 0;
                $color = $tiLe >= 70 ? 'var(--green)' : ($tiLe >= 40 ? 'var(--amber)' : 'var(--red)');
            @endphp
            <tr>
                <td class="text-center font-bold">{{ $r->ThuTu }}</td>
                <td>
                    @if(isset($r->HinhAnh) && $r->HinhAnh)
                        <img src="{{ asset('storage/' . $r->HinhAnh) }}" style="width: 80px; height: auto; border-radius: 4px; border: 1px solid var(--glass-border-strong);">
                    @else
                        {{ Str::limit($r->NoiDung ?? '', 50) }}
                    @endif
                </td>
                <td class="text-center">{{ $r->TongTraLoi }}</td>
                <td class="text-center">{{ $r->SoDung }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="flex:1;height:8px;background:rgba(203,213,225,0.4);border-radius:4px;overflow:hidden">
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
<div class="form-card glass" style="margin-bottom:24px">
    <h3 class="section-title">Tỉ lệ trả lời đúng — Phần II (Đúng/Sai)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="60" class="text-center">Câu</th>
                <th width="120">Nội dung</th>
                <th width="40" class="text-center">Ý</th>
                <th width="80" class="text-center">Đáp án</th>
                <th width="100" class="text-center">Số trả lời</th>
                <th width="100" class="text-center">Số đúng</th>
                <th width="120">Tỉ lệ đúng</th>
            </tr>
        </thead>
        <tbody>
            @php $prevCau = null; @endphp
            @foreach($tiLeUngCauDS as $r)
            @php
                $tiLe = $r->TongTraLoi > 0 ? round($r->SoDung / $r->TongTraLoi * 100) : 0;
                $color = $tiLe >= 70 ? 'var(--green)' : ($tiLe >= 40 ? 'var(--amber)' : 'var(--red)');
                $isFirstRow = $r->ThuTu != $prevCau;
                $soY = $tiLeUngCauDS->where('ThuTu', $r->ThuTu)->count();
            @endphp
            <tr>
                @if($isFirstRow)
                    <td class="text-center font-bold" rowspan="{{ $soY }}" style="vertical-align:middle; border-right: 1px solid rgba(203,213,225,0.3)">
                        {{ $r->ThuTu }}
                    </td>
                    <td rowspan="{{ $soY }}" style="vertical-align:middle; border-right: 1px solid rgba(203,213,225,0.3)">
                        @if(isset($r->HinhAnh) && $r->HinhAnh)
                            <img src="{{ asset('storage/' . $r->HinhAnh) }}" style="width:80px;height:auto;border-radius:4px; border: 1px solid var(--glass-border-strong);">
                        @else
                            {{ Str::limit($r->NoiDung ?? '', 40) }}
                        @endif
                    </td>
                @endif
                <td class="text-center font-bold">{{ strtoupper($r->KyHieu) }}</td>
                <td class="text-center">
                    <span style="color:{{ $r->DapAnDung ? 'var(--green)' : 'var(--red)' }};font-weight:700">
                        {{ $r->DapAnDung ? 'Đúng' : 'Sai' }}
                    </span>
                </td>
                <td class="text-center">{{ $r->TongTraLoi }}</td>
                <td class="text-center">{{ $r->SoDung }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="flex:1;height:8px;background:rgba(203,213,225,0.4);border-radius:4px;overflow:hidden">
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
<div class="form-card glass">
    <h3 class="section-title">Tỉ lệ trả lời đúng — Phần III (Trả lời số)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="60" class="text-center">Câu</th>
                <th>Nội dung</th>
                <th width="100" class="text-center">Đáp án</th>
                <th width="100" class="text-center">Số trả lời</th>
                <th width="100" class="text-center">Số đúng</th>
                <th width="120">Tỉ lệ đúng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiLeUngCauTLS as $r)
            @php
                $tiLe = $r->TongTraLoi > 0 ? round($r->SoDung / $r->TongTraLoi * 100) : 0;
                $color = $tiLe >= 70 ? 'var(--green)' : ($tiLe >= 40 ? 'var(--amber)' : 'var(--red)');
            @endphp
            <tr>
                <td class="text-center font-bold">{{ $r->ThuTu }}</td>
                <td>
                    @if(isset($r->HinhAnh) && $r->HinhAnh)
                        <img src="{{ asset('storage/' . $r->HinhAnh) }}" style="width:80px;height:auto;border-radius:4px; border: 1px solid var(--glass-border-strong);">
                    @else
                        {{ Str::limit($r->NoiDung ?? '', 50) }}
                    @endif
                </td>
                <td class="text-center font-bold">
                    {{ $r->DapAnSo }}
                    <small style="color:var(--text-muted); display: block; font-weight: normal; font-size: 11px;">(±{{ $r->SaiSoChapNhan }})</small>
                </td>
                <td class="text-center">{{ $r->TongTraLoi }}</td>
                <td class="text-center">{{ $r->SoDung }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="flex:1;height:8px;background:rgba(203,213,225,0.4);border-radius:4px;overflow:hidden">
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

@endif
@endsection