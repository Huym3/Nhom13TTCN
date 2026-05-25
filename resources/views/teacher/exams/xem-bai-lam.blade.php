@extends('layouts.teacher')
@section('title', 'Chi tiết bài làm')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>👁 Bài làm của <span style="color: var(--blue);">{{ $baiLam->HoTen }}</span></h2>
        <p>{{ $exam->TenDeThi }} — Nộp lúc {{ \Carbon\Carbon::parse($baiLam->ThoiGianNopBai)->format('d/m/Y H:i') }}</p>
    </div>
    <a href="{{ route('teacher.exams.stats', $exam->MaDeThi) }}" class="btn-outline">← Quay lại thống kê</a>
</div>

{{-- Tổng quan --}}
<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card glass">
        <div class="stat-number" style="color:{{ $baiLam->TongDiem >= 5 ? 'var(--green)' : 'var(--red)' }}">
            {{ number_format($baiLam->TongDiem, 2) }}
        </div>
        <div class="stat-label">Tổng điểm</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number">{{ number_format($baiLam->DiemPhan1, 2) }}đ</div>
        <div class="stat-label">Phần I</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number">{{ number_format($baiLam->DiemPhan2, 2) }}đ</div>
        <div class="stat-label">Phần II</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number">{{ number_format($baiLam->DiemPhan3, 2) }}đ</div>
        <div class="stat-label">Phần III</div>
    </div>
    <div class="stat-card glass">
        <div class="stat-number">{{ gmdate('i:s', $baiLam->TongThoiGianLamBai) }}</div>
        <div class="stat-label">Thời gian làm</div>
    </div>
</div>

{{-- Phần I --}}
@if($ketQuaPhan1->count() > 0)
<div class="form-card glass" style="margin-bottom:24px">
    <h3 class="section-title">PHẦN I — Trắc nghiệm</h3>
    @foreach($ketQuaPhan1 as $i => $cau)
    @php
        $borderColor = is_null($cau->DungSai) ? '#cbd5e1' : ($cau->DungSai ? 'var(--green)' : 'var(--red)');
        $bgColor = is_null($cau->DungSai) ? 'rgba(255,255,255,0.4)' : ($cau->DungSai ? 'rgba(5, 150, 105, 0.05)' : 'rgba(220, 38, 38, 0.05)');
    @endphp
    <div style="margin-bottom:16px;padding:16px;border-radius:12px;border-left:4px solid {{ $borderColor }};background:{{ $bgColor }}; border-top: 1px solid var(--glass-border-strong); border-right: 1px solid var(--glass-border-strong); border-bottom: 1px solid var(--glass-border-strong);">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <strong>Câu {{ $i + 1 }}</strong>
            <span style="color:{{ $borderColor }};font-weight:600">
                @if(is_null($cau->DungSai))
                    <span style="color: var(--text-muted);">⏭ Chưa trả lời (0đ)</span>
                @elseif($cau->DungSai)
                    ✅ Đúng (+{{ $cau->DiemDatDuoc }}đ)
                @else
                    ❌ Sai (0đ)
                @endif
            </span>
        </div>
        @if(isset($cau->HinhAnh) && $cau->HinhAnh)
            <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width:300px;border-radius:8px;margin-bottom:12px; border: 1px solid var(--glass-border-strong);">
        @else
            <p style="margin-bottom:12px; color: var(--text-secondary);">{{ $cau->NoiDungCH }}</p>
        @endif
        <div style="display:flex;flex-direction:column;gap:6px">
            @foreach($cau->tatCaDapAn as $da)
            @php
                $bgOption = $da->LaDapAnDung ? 'rgba(34, 197, 94, 0.15)' : ($da->KyHieu === $cau->DaChon && !$da->LaDapAnDung ? 'rgba(239, 68, 68, 0.15)' : 'rgba(255,255,255,0.6)');
                $borderOption = $da->LaDapAnDung ? 'rgba(34, 197, 94, 0.4)' : ($da->KyHieu === $cau->DaChon ? 'rgba(239, 68, 68, 0.4)' : 'var(--glass-border-strong)');
            @endphp
            <div style="padding:8px 12px;border-radius:8px; background:{{ $bgOption }}; border:1px solid {{ $borderOption }}">
                <strong>{{ $da->KyHieu }}.</strong> <span style="color: var(--text-secondary);">{{ $da->NoiDungDapAn }}</span>
                @if($da->LaDapAnDung) <span style="color:var(--green);font-size:12px;margin-left:8px; font-weight: 600;">✓ Đáp án đúng</span> @endif
                @if($da->KyHieu === $cau->DaChon && !$da->LaDapAnDung) <span style="color:var(--red);font-size:12px;margin-left:8px; font-weight: 600;">✗ HS chọn</span> @endif
                @if($da->KyHieu === $cau->DaChon && $da->LaDapAnDung) <span style="color:var(--green);font-size:12px;margin-left:8px; font-weight: 600;">✓ HS chọn đúng</span> @endif
            </div>
            @endforeach
        </div>
        @if($cau->GiaiThich)
        <div style="margin-top:12px;padding:12px;background:rgba(245, 158, 11, 0.1);border-radius:8px;font-size:13px; border: 1px solid rgba(245, 158, 11, 0.2); color: #92400e;">
            💡 <strong>Giải thích:</strong> {{ $cau->GiaiThich }}
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif

{{-- Phần II --}}
@if($ketQuaPhan2->count() > 0)
<div class="form-card glass" style="margin-bottom:24px">
    <h3 class="section-title">PHẦN II — Đúng/Sai</h3>
    @foreach($ketQuaPhan2 as $i => $cau)
    <div style="margin-bottom:16px;padding:16px;border-radius:12px;background:rgba(255,255,255,0.5);border:1px solid var(--glass-border-strong)">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <strong>Câu {{ $i + 1 }}</strong>
            <span style="font-weight:600; color: var(--blue);">+{{ $cau->diemDat }}đ</span>
        </div>
        @if(isset($cau->HinhAnh) && $cau->HinhAnh)
            <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width:300px;border-radius:8px;margin-bottom:12px; border: 1px solid var(--glass-border-strong);">
        @else
            <p style="margin-bottom:12px; color: var(--text-secondary);">{{ $cau->NoiDungCH }}</p>
        @endif
        <table class="data-table">
            <thead>
                <tr><th class="text-center">Ý</th><th class="text-center">Đáp án đúng</th><th class="text-center">HS chọn</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($cau->cacY as $y)
                <tr style="background:{{ $y->DungSai ? 'rgba(5, 150, 105, 0.05)' : 'rgba(220, 38, 38, 0.05)' }}">
                    <td class="text-center"><strong>{{ strtoupper($y->KyHieu) }}</strong></td>
                    <td class="text-center">{{ $y->DapAnDung ? 'Đúng' : 'Sai' }}</td>
                    <td class="text-center">
                        @if(is_null($y->LuaChonCuaHocSinh)) <em style="color:var(--text-muted)">Chưa trả lời</em>
                        @else <span style="font-weight: 500;">{{ $y->LuaChonCuaHocSinh ? 'Đúng' : 'Sai' }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $y->DungSai ? '✅' : '❌' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</div>
@endif

{{-- Phần III --}}
@if($ketQuaPhan3->count() > 0)
<div class="form-card glass">
    <h3 class="section-title">PHẦN III — Trả lời số</h3>
    @foreach($ketQuaPhan3 as $i => $cau)
    <div style="margin-bottom:16px;padding:16px;border-radius:12px;border-left:4px solid {{ $cau->DungSai ? 'var(--green)' : 'var(--red)' }};background:{{ $cau->DungSai ? 'rgba(5, 150, 105, 0.05)' : 'rgba(220, 38, 38, 0.05)' }}; border-top: 1px solid var(--glass-border-strong); border-right: 1px solid var(--glass-border-strong); border-bottom: 1px solid var(--glass-border-strong);">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <strong>Câu {{ $i + 1 }}</strong>
            <span style="color:{{ $cau->DungSai ? 'var(--green)' : 'var(--red)' }};font-weight:600">
                {{ $cau->DungSai ? '✅ Đúng (+0.5đ)' : '❌ Sai (0đ)' }}
            </span>
        </div>
        @if(isset($cau->HinhAnh) && $cau->HinhAnh)
            <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width:300px;border-radius:8px;margin-bottom:12px; border: 1px solid var(--glass-border-strong);">
        @else
            <p style="margin-bottom:12px; color: var(--text-secondary);">{{ $cau->NoiDungCH }}</p>
        @endif
        <div style="display:flex;gap:32px;margin-top:12px; background: rgba(255,255,255,0.6); padding: 12px; border-radius: 8px; border: 1px solid var(--glass-border-strong);">
            <span>HS trả lời: <strong>{{ $cau->CauTraLoiSo ?? 'Chưa trả lời' }}</strong></span>
            <span>Đáp án đúng: <strong style="color:var(--green)">{{ $cau->DapAnSo }}</strong></span>
        </div>
        @if($cau->GiaiThich)
        <div style="margin-top:12px;padding:12px;background:rgba(245, 158, 11, 0.1);border-radius:8px;font-size:13px; border: 1px solid rgba(245, 158, 11, 0.2); color: #92400e;">
            💡 <strong>Giải thích:</strong> {{ $cau->GiaiThich }}
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif
@endsection