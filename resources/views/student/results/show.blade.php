@extends('layouts.student')
@section('title', 'Kết quả bài làm')

@section('content')
<div class="result-page">

    {{-- Tổng quan điểm --}}
    <div class="result-summary">
        <h2>📊 Kết quả: {{ $baiLam->TenDeThi }}</h2>
        <div class="score-big {{ $baiLam->TongDiem >= 5 ? 'pass' : 'fail' }}">
            {{ number_format($baiLam->TongDiem, 2) }} / 10
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
        <div class="result-meta">
            <span>✅ Đúng: {{ $baiLam->SoCauDung }} câu</span>
            <span>❌ Sai: {{ $baiLam->SoCauSai }} câu</span>
            <span>⏱ Thời gian: {{ gmdate('i:s', $baiLam->TongThoiGianLamBai) }}</span>
        </div>
    </div>

    {{-- Phần I: Chi tiết TN --}}
    @if($ketQuaPhan1->count() > 0)
    <div class="result-section">
        <h3>PHẦN I — Trắc nghiệm</h3>
        @foreach($ketQuaPhan1 as $i => $cau)
        <div class="result-question {{ $cau->DungSai ? 'dung' : 'sai' }}">
            <div class="rq-header">
                <span class="rq-num">Câu {{ $i + 1 }}</span>
                <span class="rq-status">{{ $cau->DungSai ? '✅ Đúng (+' . $cau->DiemDatDuoc . 'đ)' : '❌ Sai (0đ)' }}</span>
            </div>
            <div class="rq-content">{!! $cau->NoiDungCH !!}</div>
            <div class="rq-options">
                @foreach($cau->tatCaDapAn as $da)
                <div class="rq-option
                    {{ $da->LaDapAnDung ? 'correct' : '' }}
                    {{ $da->KyHieu === $cau->DaChon && !$da->LaDapAnDung ? 'wrong-choice' : '' }}
                    {{ $da->KyHieu === $cau->DaChon && $da->LaDapAnDung ? 'correct-choice' : '' }}">
                    <strong>{{ $da->KyHieu }}.</strong> {{ $da->NoiDungDapAn }}
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

    {{-- Phần II: Chi tiết Đúng/Sai --}}
    @if($ketQuaPhan2->count() > 0)
    <div class="result-section">
        <h3>PHẦN II — Đúng/Sai</h3>
        @foreach($ketQuaPhan2 as $i => $cau)
        <div class="result-question">
            <div class="rq-header">
                <span class="rq-num">Câu {{ $i + 1 }}</span>
                <span class="rq-status">+{{ $cau->diemDat }}đ</span>
            </div>
            <div class="rq-content">{!! $cau->NoiDungCH !!}</div>
            <table class="ds-result-table">
                <thead>
                    <tr><th>Ý</th><th>Nội dung</th><th>Đáp án đúng</th><th>Bạn chọn</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($cau->cacY as $y)
                    <tr class="{{ $y->DungSai ? 'dung' : 'sai' }}">
                        <td><strong>{{ $y->KyHieu }}</strong></td>
                        <td>{{ $y->NoiDungY }}</td>
                        <td>{{ $y->DapAnDung ? 'Đúng' : 'Sai' }}</td>
                        <td>
                            @if(is_null($y->LuaChonCuaHocSinh)) <em>Chưa trả lời</em>
                            @else {{ $y->LuaChonCuaHocSinh ? 'Đúng' : 'Sai' }}
                            @endif
                        </td>
                        <td>{{ $y->DungSai ? '✅' : '❌' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Phần III: Chi tiết số --}}
    @if($ketQuaPhan3->count() > 0)
    <div class="result-section">
        <h3>PHẦN III — Trả lời ngắn</h3>
        @foreach($ketQuaPhan3 as $i => $cau)
        <div class="result-question {{ $cau->DungSai ? 'dung' : 'sai' }}">
            <div class="rq-header">
                <span class="rq-num">Câu {{ $i + 1 }}</span>
                <span class="rq-status">{{ $cau->DungSai ? '✅ Đúng (+0.5đ)' : '❌ Sai (0đ)' }}</span>
            </div>
            <div class="rq-content">{!! $cau->NoiDungCH !!}</div>
            <div class="tls-result">
                <span>Bạn trả lời: <strong>{{ $cau->CauTraLoiSo ?? 'Chưa trả lời' }}</strong></span>
                <span>Đáp án đúng: <strong>{{ $cau->DapAnSo }}</strong></span>
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