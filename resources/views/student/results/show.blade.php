@extends('layouts.student')
@section('title', 'Kết quả bài làm')

@section('content')
<div class="result-page">

    {{-- Tổng quan điểm --}}
    <div class="result-summary glass" style="margin-bottom: 16px;">
        <h2>{{ $baiLam->TenDeThi }}</h2>
        <div class="score-big {{ $baiLam->TongDiem >= 5 ? 'pass' : 'fail' }}">
            {{ number_format($baiLam->TongDiem, 2) }}<span style="font-size: 22px; font-weight: 400; opacity: 0.4;"> / 10</span>
        </div>
        <div class="score-detail">
            <div class="score-part">
                <span>Phần I</span>
                <strong>{{ number_format($baiLam->DiemPhan1, 2) }}đ</strong>
            </div>
            <div class="score-part">
                <span>Phần II</span>
                <strong>{{ number_format($baiLam->DiemPhan2, 2) }}đ</strong>
            </div>
            <div class="score-part">
                <span>Phần III</span>
                <strong>{{ number_format($baiLam->DiemPhan3, 2) }}đ</strong>
            </div>
        </div>
        @php
            $dauDung   = $ketQuaPhan1->filter(fn($c) => $c->DungSai === 1)->count();
            $dauSai    = $ketQuaPhan1->filter(fn($c) => $c->DungSai === 0)->count();
            $chuaLamP1 = $ketQuaPhan1->filter(fn($c) => is_null($c->DungSai))->count();
            $chuaLamP2 = $ketQuaPhan2->filter(function($cau) {
                return collect($cau->cacY)->filter(fn($y) => !is_null($y->LuaChonCuaHocSinh))->count() === 0;
            })->count();
            $chuaLamP3 = $ketQuaPhan3->filter(fn($c) => is_null($c->CauTraLoiSo))->count();
            $tongChuaLam = $chuaLamP1 + $chuaLamP2 + $chuaLamP3;
        @endphp
        <div class="result-meta" style="margin-top: 12px;">
            <span>Chưa làm: {{ $tongChuaLam }} câu</span>
            <span>Thời gian: {{ gmdate('i:s', $baiLam->TongThoiGianLamBai) }}</span>
        </div>
    </div>

    {{-- Phần I: Trắc nghiệm --}}
    @if($ketQuaPhan1->count() > 0)
    <div class="result-section">
        <h3>PHẦN I — Trắc nghiệm</h3>
        @foreach($ketQuaPhan1 as $i => $cau)
        <div class="result-question glass {{ is_null($cau->DungSai) ? 'chua-lam' : ($cau->DungSai ? 'dung' : 'sai') }}">
            <div class="rq-header">
                <span class="rq-num">Câu {{ $i + 1 }}</span>
                <span class="rq-status" style="color: {{ is_null($cau->DungSai) ? 'var(--text-muted)' : ($cau->DungSai ? 'var(--green)' : 'var(--red)') }}">
                    @if(is_null($cau->DungSai)) Chưa trả lời (0đ)
                    @elseif($cau->DungSai) Đúng (+{{ $cau->DiemDatDuoc }}đ)
                    @else Sai (0đ)
                    @endif
                </span>
            </div>
            <div class="rq-content">
                @if(isset($cau->HinhAnh) && $cau->HinhAnh)
                    <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width:100%; border-radius:8px; margin-bottom:8px;">
                @else
                    {!! $cau->NoiDungCH !!}
                @endif
            </div>
            <div class="rq-options">
                @foreach($cau->tatCaDapAn as $da)
                <div class="rq-option
                    {{ $da->LaDapAnDung ? 'correct' : '' }}
                    {{ $da->KyHieu === $cau->DaChon && !$da->LaDapAnDung ? 'wrong-choice' : '' }}
                    {{ $da->KyHieu === $cau->DaChon && $da->LaDapAnDung ? 'correct-choice' : '' }}">
                    <strong style="min-width:18px;">{{ $da->KyHieu }}.</strong>
                    {{ $da->NoiDungDapAn }}
                    @if($da->LaDapAnDung) <span class="tag-correct">✓ Đáp án đúng</span> @endif
                    @if($da->KyHieu === $cau->DaChon && !$da->LaDapAnDung) <span class="tag-wrong">✗ Bạn chọn</span> @endif
                </div>
                @endforeach
            </div>
            @if($cau->GiaiThich)
            <div class="rq-explain">💡 {{ $cau->GiaiThich }}</div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Phần II: Đúng/Sai --}}
    @if($ketQuaPhan2->count() > 0)
    <div class="result-section">
        <h3>PHẦN II — Đúng/Sai</h3>
        @foreach($ketQuaPhan2 as $i => $cau)
        @php
            $soYChuaLam = collect($cau->cacY)->filter(fn($y) => is_null($y->LuaChonCuaHocSinh))->count();
            $trangThai = $soYChuaLam == count($cau->cacY) ? 'chua-lam' : ($cau->diemDat > 0 ? 'dung' : 'sai');
        @endphp
        <div class="result-question glass {{ $trangThai }}">
            <div class="rq-header">
                <span class="rq-num">Câu {{ $i + 1 }}</span>
                <span class="rq-status" style="color: {{ $trangThai === 'chua-lam' ? 'var(--text-muted)' : ($cau->diemDat > 0 ? 'var(--green)' : 'var(--red)') }}">
                    @if($trangThai === 'chua-lam') Chưa trả lời (0đ)
                    @else +{{ $cau->diemDat }}đ
                    @endif
                </span>
            </div>
            <div class="rq-content">
                @if(isset($cau->HinhAnh) && $cau->HinhAnh)
                    <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width:100%; border-radius:8px; margin-bottom:8px;">
                @else
                    {!! $cau->NoiDungCH !!}
                @endif
            </div>
            <div style="padding: 0 14px 14px;">
                <table class="ds-result-table">
                    <thead>
                        <tr><th>Ý</th><th>Đáp án đúng</th><th>Bạn chọn</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($cau->cacY as $y)
                        <tr class="{{ $y->DungSai ? 'dung' : 'sai' }}">
                            <td><strong>{{ $y->KyHieu }}</strong></td>
                            <td>{{ $y->DapAnDung ? 'Đúng' : 'Sai' }}</td>
                            <td>
                                @if(is_null($y->LuaChonCuaHocSinh)) <em style="color:var(--text-muted);">Chưa trả lời</em>
                                @else {{ $y->LuaChonCuaHocSinh ? 'Đúng' : 'Sai' }}
                                @endif
                            </td>
                            <td>{{ $y->DungSai ? '✅' : '❌' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Phần III: Trả lời ngắn --}}
    @if($ketQuaPhan3->count() > 0)
    <div class="result-section">
        <h3>PHẦN III — Trả lời ngắn</h3>
        @foreach($ketQuaPhan3 as $i => $cau)
        <div class="result-question glass {{ is_null($cau->DungSai) ? 'chua-lam' : ($cau->DungSai ? 'dung' : 'sai') }}">
            <div class="rq-header">
                <span class="rq-num">Câu {{ $i + 1 }}</span>
                <span class="rq-status" style="color: {{ is_null($cau->DungSai) ? 'var(--text-muted)' : ($cau->DungSai ? 'var(--green)' : 'var(--red)') }}">
                    @if(is_null($cau->DungSai)) Chưa trả lời (0đ)
                    @elseif($cau->DungSai) Đúng (+0.5đ)
                    @else Sai (0đ)
                    @endif
                </span>
            </div>
            <div class="rq-content">
                @if(isset($cau->HinhAnh) && $cau->HinhAnh)
                    <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width:100%; border-radius:8px; margin-bottom:8px;">
                @else
                    {!! $cau->NoiDungCH !!}
                @endif
            </div>
            <div class="tls-result">
                <span>Bạn trả lời: <strong>{{ $cau->CauTraLoiSo ?? 'Chưa trả lời' }}</strong></span>
                <span>Đáp án đúng: <strong style="color: var(--green);">{{ $cau->DapAnSo }}</strong></span>
            </div>
            @if($cau->GiaiThich)
            <div class="rq-explain">💡 {{ $cau->GiaiThich }}</div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <div class="result-actions">
        <a href="{{ route('student.results.index') }}" class="btn-outline">← Lịch sử bài làm</a>
        <a href="{{ route('student.exams') }}" class="btn-primary">Làm đề khác</a>
    </div>
</div>
@endsection