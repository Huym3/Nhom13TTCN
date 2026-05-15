@extends('layouts.teacher')
@section('title', 'Chi tiết bài làm')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>👁 Bài làm của {{ $baiLam->HoTen }}</h2>
        <p>{{ $exam->TenDeThi }} — Nộp lúc {{ \Carbon\Carbon::parse($baiLam->ThoiGianNopBai)->format('d/m/Y H:i') }}</p>
    </div>
    <a href="{{ route('teacher.exams.stats', $exam->MaDeThi) }}" class="btn-outline">← Quay lại thống kê</a>
</div>

{{-- Tổng quan --}}
<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-number {{ $baiLam->TongDiem >= 5 ? '' : '' }}"
             style="color:{{ $baiLam->TongDiem >= 5 ? '#16a34a' : '#dc2626' }}">
            {{ number_format($baiLam->TongDiem, 2) }}
        </div>
        <div class="stat-label">Tổng điểm</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ number_format($baiLam->DiemPhan1, 2) }}đ</div>
        <div class="stat-label">Phần I</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ number_format($baiLam->DiemPhan2, 2) }}đ</div>
        <div class="stat-label">Phần II</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ number_format($baiLam->DiemPhan3, 2) }}đ</div>
        <div class="stat-label">Phần III</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ gmdate('i:s', $baiLam->TongThoiGianLamBai) }}</div>
        <div class="stat-label">Thời gian làm</div>
    </div>
</div>

{{-- Phần I --}}
@if($ketQuaPhan1->count() > 0)
<div class="form-card" style="margin-bottom:24px">
    <h3 class="section-title">PHẦN I — Trắc nghiệm</h3>
    @foreach($ketQuaPhan1 as $i => $cau)
    <div class="result-question {{ $cau->DungSai ? 'dung' : 'sai' }}" style="margin-bottom:16px;padding:16px;border-radius:8px;border-left:4px solid {{ $cau->DungSai ? '#16a34a' : '#dc2626' }};background:{{ $cau->DungSai ? '#f0fdf4' : '#fef2f2' }}">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <strong>Câu {{ $i + 1 }}</strong>
            <span style="color:{{ $cau->DungSai ? '#16a34a' : '#dc2626' }};font-weight:600">
                {{ $cau->DungSai ? '✅ Đúng (+' . $cau->DiemDatDuoc . 'đ)' : '❌ Sai (0đ)' }}
            </span>
        </div>
        @if(isset($cau->HinhAnh) && $cau->HinhAnh)
            <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width:300px;border-radius:6px;margin-bottom:10px">
        @else
            <p style="margin-bottom:10px">{{ $cau->NoiDungCH }}</p>
        @endif
        <div style="display:flex;flex-direction:column;gap:6px">
            @foreach($cau->tatCaDapAn as $da)
            <div style="padding:8px 12px;border-radius:6px;
                background:{{ $da->LaDapAnDung ? '#dcfce7' : ($da->KyHieu === $cau->DaChon && !$da->LaDapAnDung ? '#fee2e2' : '#f8fafc') }};
                border:1px solid {{ $da->LaDapAnDung ? '#86efac' : ($da->KyHieu === $cau->DaChon ? '#fca5a5' : '#e2e8f0') }}">
                <strong>{{ $da->KyHieu }}.</strong> {{ $da->NoiDungDapAn }}
                @if($da->LaDapAnDung) <span style="color:#16a34a;font-size:12px;margin-left:8px">✓ Đáp án đúng</span> @endif
                @if($da->KyHieu === $cau->DaChon && !$da->LaDapAnDung) <span style="color:#dc2626;font-size:12px;margin-left:8px">✗ HS chọn</span> @endif
                @if($da->KyHieu === $cau->DaChon && $da->LaDapAnDung) <span style="color:#16a34a;font-size:12px;margin-left:8px">✓ HS chọn đúng</span> @endif
            </div>
            @endforeach
        </div>
        @if($cau->GiaiThich)
        <div style="margin-top:10px;padding:10px;background:#fefce8;border-radius:6px;font-size:13px">
            💡 {{ $cau->GiaiThich }}
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif

{{-- Phần II --}}
@if($ketQuaPhan2->count() > 0)
<div class="form-card" style="margin-bottom:24px">
    <h3 class="section-title">PHẦN II — Đúng/Sai</h3>
    @foreach($ketQuaPhan2 as $i => $cau)
    <div style="margin-bottom:16px;padding:16px;border-radius:8px;background:#f8fafc;border:1px solid #e2e8f0">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <strong>Câu {{ $i + 1 }}</strong>
            <span style="font-weight:600">+{{ $cau->diemDat }}đ</span>
        </div>
        @if(isset($cau->HinhAnh) && $cau->HinhAnh)
            <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width:300px;border-radius:6px;margin-bottom:10px">
        @else
            <p style="margin-bottom:10px">{{ $cau->NoiDungCH }}</p>
        @endif
        <table class="data-table">
            <thead>
                <tr><th>Ý</th><th>Đáp án đúng</th><th>HS chọn</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($cau->cacY as $y)
                <tr style="background:{{ $y->DungSai ? '#f0fdf4' : '#fef2f2' }}">
                    <td class="text-center"><strong>{{ strtoupper($y->KyHieu) }}</strong></td>
                    <td class="text-center">{{ $y->DapAnDung ? 'Đúng' : 'Sai' }}</td>
                    <td class="text-center">
                        @if(is_null($y->LuaChonCuaHocSinh)) <em style="color:#94a3b8">Chưa trả lời</em>
                        @else {{ $y->LuaChonCuaHocSinh ? 'Đúng' : 'Sai' }}
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
<div class="form-card">
    <h3 class="section-title">PHẦN III — Trả lời số</h3>
    @foreach($ketQuaPhan3 as $i => $cau)
    <div style="margin-bottom:16px;padding:16px;border-radius:8px;border-left:4px solid {{ $cau->DungSai ? '#16a34a' : '#dc2626' }};background:{{ $cau->DungSai ? '#f0fdf4' : '#fef2f2' }}">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <strong>Câu {{ $i + 1 }}</strong>
            <span style="color:{{ $cau->DungSai ? '#16a34a' : '#dc2626' }};font-weight:600">
                {{ $cau->DungSai ? '✅ Đúng (+0.5đ)' : '❌ Sai (0đ)' }}
            </span>
        </div>
        @if(isset($cau->HinhAnh) && $cau->HinhAnh)
            <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width:300px;border-radius:6px;margin-bottom:10px">
        @else
            <p style="margin-bottom:10px">{{ $cau->NoiDungCH }}</p>
        @endif
        <div style="display:flex;gap:24px;margin-top:8px">
            <span>HS trả lời: <strong>{{ $cau->CauTraLoiSo ?? 'Chưa trả lời' }}</strong></span>
            <span>Đáp án đúng: <strong style="color:#16a34a">{{ $cau->DapAnSo }}</strong></span>
        </div>
        @if($cau->GiaiThich)
        <div style="margin-top:10px;padding:10px;background:#fefce8;border-radius:6px;font-size:13px">
            💡 {{ $cau->GiaiThich }}
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif
@endsection